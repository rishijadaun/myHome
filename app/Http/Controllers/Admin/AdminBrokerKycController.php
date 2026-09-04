<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminBrokerKycController extends Controller
{
    /**
     * Display a listing of partner broker KYC submissions & verification statuses.
     */
    public function index(Request $request)
    {
        $brokerRole = Role::where('slug', 'broker')->first();

        // 1. Calculate live KYC metric counts
        $allBrokers = $brokerRole 
            ? $brokerRole->users()->with(['profile', 'relationshipManager', 'properties.city'])->get()
            : collect([]);

        $totalBrokers = $allBrokers->count();

        $pendingKycCount = 0;
        $verifiedKycCount = 0;
        $rejectedKycCount = 0;
        $missingKycCount = 0;

        foreach ($allBrokers as $broker) {
            $status = $this->determineKycStatus($broker);
            if ($status === 'pending') {
                $pendingKycCount++;
            } elseif ($status === 'verified') {
                $verifiedKycCount++;
            } elseif ($status === 'rejected') {
                $rejectedKycCount++;
            } else {
                $missingKycCount++;
            }
        }

        // 2. Query Builder with Filters & Search
        $query = $brokerRole 
            ? $brokerRole->users()->with(['profile', 'relationshipManager', 'properties.city', 'wallet'])
            : User::query()->whereRaw('0 = 1');

        $search = trim($request->query('search', ''));
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('users.email', 'like', "%{$search}%")
                  ->orWhere('users.phone', 'like', "%{$search}%")
                  ->orWhereHas('profile', function ($p) use ($search) {
                      $p->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('full_name', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%");
                  });
            });
        }

        $currentTab = strtoupper($request->query('tab', 'ALL'));

        if ($currentTab === 'PENDING') {
            $query->where(function ($q) {
                $q->whereNull('users.kyc_verified_at')
                  ->whereNotIn('users.status', ['suspended', 'inactive'])
                  ->whereHas('profile', function ($p) {
                      $p->where('preferences', 'like', '%"file_path"%');
                  });
            });
        } elseif ($currentTab === 'VERIFIED') {
            $query->whereNotNull('users.kyc_verified_at');
        } elseif ($currentTab === 'REJECTED') {
            $query->where(function ($q) {
                $q->whereIn('users.status', ['suspended', 'inactive'])
                  ->orWhere(function ($sub) {
                      $sub->whereNull('users.kyc_verified_at')
                          ->whereHas('profile', function ($p) {
                              $p->where('preferences', 'like', '%"status":"rejected"%');
                          });
                  });
            });
        } elseif ($currentTab === 'MISSING') {
            $query->whereNull('users.kyc_verified_at')
                  ->whereDoesntHave('profile', function ($p) {
                      $p->where('preferences', 'like', '%"file_path"%');
                  });
        }

        $brokers = $query->latest('users.created_at')->paginate(12)->withQueryString();

        // Attach computed KYC summary to each broker
        $brokers->getCollection()->transform(function ($broker) {
            $broker->computed_kyc_status = $this->determineKycStatus($broker);
            $broker->kyc_docs = $this->extractDocuments($broker);
            return $broker;
        });

        return view('admin.broker-kyc.index', compact(
            'brokers',
            'totalBrokers',
            'pendingKycCount',
            'verifiedKycCount',
            'rejectedKycCount',
            'missingKycCount',
            'currentTab',
            'search'
        ));
    }

    /**
     * Show single broker details & documents for review modal.
     */
    public function show($id)
    {
        if (empty($id) || $id === 'null' || $id === 'undefined') {
            return response()->json(['success' => false, 'message' => 'Invalid broker ID provided.'], 400);
        }

        $broker = User::with(['profile', 'relationshipManager', 'properties.city', 'wallet'])
            ->find($id);

        if (!$broker) {
            return response()->json(['success' => false, 'message' => 'Broker partner not found.'], 404);
        }

        $profile = $broker->profile;
        $preferences = $profile ? ($profile->preferences ?? []) : [];
        if (!is_array($preferences)) {
            $preferences = json_decode($preferences, true) ?? [];
        }

        $docs = $preferences['documents'] ?? [];
        $bank = $preferences['bank_details'] ?? [];

        $docTypes = [
            'id_proof' => 'Government ID Proof (Aadhar / PAN)',
            'license_proof' => 'RERA License / Property Deed',
            'bank_proof' => 'Cancelled Cheque / Bank Passbook',
            'other' => 'Other Regulatory Document',
        ];

        $formattedDocs = [];
        foreach ($docTypes as $key => $label) {
            $docData = $docs[$key] ?? null;
            $formattedDocs[$key] = [
                'type' => $key,
                'label' => $label,
                'is_uploaded' => !empty($docData) && !empty($docData['file_path']),
                'name' => $docData['name'] ?? null,
                'file_path' => $docData['file_path'] ?? null,
                'doc_number' => $docData['doc_number'] ?? null,
                'status' => $docData['status'] ?? 'not_uploaded',
                'allow_reupload' => !empty($docData['allow_reupload']),
                'reupload_note' => $docData['reupload_note'] ?? null,
                'rejection_reason' => $docData['rejection_reason'] ?? null,
                'uploaded_at' => !empty($docData['uploaded_at']) ? Carbon::parse($docData['uploaded_at'])->format('M d, Y h:i A') : null,
                'reviewed_at' => !empty($docData['reviewed_at']) ? Carbon::parse($docData['reviewed_at'])->format('M d, Y h:i A') : null,
            ];
        }

        return response()->json([
            'success' => true,
            'broker' => [
                'id' => $broker->id,
                'name' => $profile ? $profile->full_name : $broker->email,
                'email' => $broker->email,
                'phone' => $broker->phone ?? 'Not provided',
                'company_name' => $profile->company_name ?? 'Individual Broker Partner',
                'avatar_url' => $profile->avatar_url ?? null,
                'operating_city' => $preferences['operating_city'] ?? 'Noida / Delhi NCR',
                'operating_area' => $preferences['operating_area'] ?? 'Sector 62',
                'office_address' => $preferences['office_address'] ?? 'Not specified',
                'gstin' => $preferences['gstin'] ?? 'Not provided',
                'rera_number' => $preferences['rera_number'] ?? 'Not provided',
                'joined_at' => $broker->created_at ? $broker->created_at->format('M d, Y') : 'Recent',
                'kyc_status' => $this->determineKycStatus($broker),
                'kyc_verified_at' => $broker->kyc_verified_at ? $broker->kyc_verified_at->format('M d, Y h:i A') : null,
                'account_status' => $broker->status,
                'bank_details' => [
                    'account_holder_name' => $bank['account_holder_name'] ?? ($profile->full_name ?? ''),
                    'bank_name' => $bank['bank_name'] ?? 'Not provided',
                    'account_number' => $bank['account_number'] ?? 'Not provided',
                    'ifsc_code' => $bank['ifsc_code'] ?? 'Not provided',
                    'account_type' => $bank['account_type'] ?? 'Savings',
                    'upi_id' => $bank['upi_id'] ?? 'Not provided',
                ],
                'relationship_manager' => $broker->relationshipManager ? [
                    'name' => $broker->relationshipManager->name,
                    'designation' => $broker->relationshipManager->designation,
                    'phone' => $broker->relationshipManager->phone,
                    'email' => $broker->relationshipManager->email,
                ] : null,
                'documents' => $formattedDocs,
            ]
        ]);
    }

    /**
     * 1-Click Approve all submitted KYC documents and verify the partner broker.
     */
    public function approve(Request $request, $id)
    {
        if (empty($id) || $id === 'null' || $id === 'undefined') {
            return response()->json(['success' => false, 'message' => 'Invalid broker ID provided.'], 400);
        }

        $broker = User::find($id);
        if (!$broker) {
            return response()->json(['success' => false, 'message' => 'Broker partner not found.'], 404);
        }

        $profile = UserProfile::firstOrCreate(['user_id' => $broker->id]);

        $preferences = $profile->preferences ?? [];
        if (!is_array($preferences)) {
            $preferences = json_decode($preferences, true) ?? [];
        }

        $docs = $preferences['documents'] ?? [];

        // Mark all uploaded documents as verified and lock re-upload by default
        $docKeys = ['id_proof', 'license_proof', 'bank_proof', 'other'];
        foreach ($docKeys as $key) {
            if (isset($docs[$key]) && is_array($docs[$key])) {
                $docs[$key]['status'] = 'verified';
                $docs[$key]['allow_reupload'] = false;
                $docs[$key]['reviewed_at'] = now()->toDateTimeString();
                unset($docs[$key]['rejection_reason']);
                unset($docs[$key]['reupload_note']);
            }
        }

        $preferences['documents'] = $docs;
        $profile->preferences = $preferences;
        $profile->save();

        // Update broker user status
        $broker->kyc_verified_at = now();
        $broker->status = 'active';
        $broker->is_active = true;
        $broker->save();

        // Send In-App Notification
        Notification::create([
            'id' => (string) Str::uuid(),
            'user_id' => $broker->id,
            'user_type' => 'broker',
            'title' => 'KYC Documents Verified 🎉',
            'message' => 'Congratulations! All your partner KYC documents have been reviewed and approved. Your verified partner badge is now active.',
            'type' => 'kyc_approved',
            'is_read' => 0,
            'action_url' => '/broker/profile?tab=kyc',
        ]);

        $brokerName = $profile->full_name ?: $broker->email;

        return response()->json([
            'success' => true,
            'message' => "Broker \"{$brokerName}\" KYC has been fully approved & verified!",
            'kyc_status' => 'verified',
            'kyc_verified_at' => now()->format('M d, Y h:i A')
        ]);
    }

    /**
     * Reject KYC application with admin remarks/reason.
     */
    public function reject(Request $request, $id)
    {
        if (empty($id) || $id === 'null' || $id === 'undefined') {
            return response()->json(['success' => false, 'message' => 'Invalid broker ID provided.'], 400);
        }

        $validated = $request->validate([
            'reason' => ['required', 'string', 'min:3', 'max:500'],
            'doc_type' => ['nullable', 'string'],
        ], [
            'reason.required' => 'Please provide a clear rejection reason for the broker.'
        ]);

        $broker = User::find($id);
        if (!$broker) {
            return response()->json(['success' => false, 'message' => 'Broker partner not found.'], 404);
        }

        $profile = UserProfile::firstOrCreate(['user_id' => $broker->id]);

        $preferences = $profile->preferences ?? [];
        if (!is_array($preferences)) {
            $preferences = json_decode($preferences, true) ?? [];
        }

        $docs = $preferences['documents'] ?? [];
        $targetDoc = $validated['doc_type'] ?? 'all';

        if ($targetDoc === 'all' || empty($targetDoc)) {
            foreach ($docs as $key => &$doc) {
                if (is_array($doc)) {
                    $doc['status'] = 'rejected';
                    $doc['rejection_reason'] = $validated['reason'];
                    $doc['reviewed_at'] = now()->toDateTimeString();
                }
            }
        } elseif (isset($docs[$targetDoc]) && is_array($docs[$targetDoc])) {
            $docs[$targetDoc]['status'] = 'rejected';
            $docs[$targetDoc]['rejection_reason'] = $validated['reason'];
            $docs[$targetDoc]['reviewed_at'] = now()->toDateTimeString();
        }

        $preferences['documents'] = $docs;
        $profile->preferences = $preferences;
        $profile->save();

        // Clear verification timestamp
        $broker->kyc_verified_at = null;
        $broker->save();

        // Send In-App Notification to Broker
        Notification::create([
            'id' => (string) Str::uuid(),
            'user_id' => $broker->id,
            'user_type' => 'broker',
            'title' => 'KYC Document Action Required ⚠️',
            'message' => 'Your KYC document review encountered issues: "' . $validated['reason'] . '". Please upload updated documents.',
            'type' => 'kyc_rejected',
            'is_read' => 0,
            'action_url' => '/broker/profile?tab=kyc',
        ]);

        $brokerName = $profile->full_name ?: $broker->email;

        return response()->json([
            'success' => true,
            'message' => "KYC for broker \"{$brokerName}\" marked as rejected with reason recorded.",
            'kyc_status' => 'rejected'
        ]);
    }

    /**
     * Verify or reject an individual KYC document (Aadhar, License, or Cheque).
     */
    public function verifyDoc(Request $request, $id)
    {
        if (empty($id) || $id === 'null' || $id === 'undefined') {
            return response()->json(['success' => false, 'message' => 'Invalid broker ID provided.'], 400);
        }

        $validated = $request->validate([
            'doc_type' => ['required', 'string', 'in:id_proof,license_proof,bank_proof,other'],
            'action' => ['required', 'string', 'in:verify,reject'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        if ($validated['action'] === 'reject' && empty(trim($validated['reason'] ?? ''))) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a reason when rejecting a document.'
            ], 422);
        }

        $broker = User::find($id);
        if (!$broker) {
            return response()->json(['success' => false, 'message' => 'Broker partner not found.'], 404);
        }

        $profile = UserProfile::firstOrCreate(['user_id' => $broker->id]);

        $preferences = $profile->preferences ?? [];
        if (!is_array($preferences)) {
            $preferences = json_decode($preferences, true) ?? [];
        }

        $docs = $preferences['documents'] ?? [];

        if (!isset($docs[$validated['doc_type']]) || !is_array($docs[$validated['doc_type']])) {
            return response()->json([
                'success' => false,
                'message' => 'This document has not been uploaded by the broker yet.'
            ], 404);
        }

        if ($validated['action'] === 'verify') {
            $docs[$validated['doc_type']]['status'] = 'verified';
            $docs[$validated['doc_type']]['allow_reupload'] = false;
            $docs[$validated['doc_type']]['reviewed_at'] = now()->toDateTimeString();
            unset($docs[$validated['doc_type']]['rejection_reason']);
            unset($docs[$validated['doc_type']]['reupload_note']);
        } else {
            $docs[$validated['doc_type']]['status'] = 'rejected';
            $docs[$validated['doc_type']]['allow_reupload'] = true;
            $docs[$validated['doc_type']]['rejection_reason'] = $validated['reason'];
            $docs[$validated['doc_type']]['reviewed_at'] = now()->toDateTimeString();
            $broker->kyc_verified_at = null;
        }

        $preferences['documents'] = $docs;
        $profile->preferences = $preferences;
        $profile->save();

        // Check if all uploaded documents are verified
        $hasAnyUpload = false;
        $hasAnyPendingOrRejected = false;
        foreach (['id_proof', 'license_proof', 'bank_proof', 'other'] as $req) {
            if (!empty($docs[$req]) && is_array($docs[$req]) && !empty($docs[$req]['file_path'])) {
                $hasAnyUpload = true;
                if (($docs[$req]['status'] ?? '') !== 'verified') {
                    $hasAnyPendingOrRejected = true;
                }
            }
        }

        $allUploadedVerified = $hasAnyUpload && !$hasAnyPendingOrRejected;

        if ($allUploadedVerified && $validated['action'] === 'verify') {
            $broker->kyc_verified_at = now();
            $broker->status = 'active';
            $broker->is_active = true;
            $broker->save();

            Notification::create([
                'id' => (string) Str::uuid(),
                'user_id' => $broker->id,
                'user_type' => 'broker',
                'title' => 'All KYC Documents Verified 🎉',
                'message' => 'All required KYC documents have been reviewed and approved! Verified partner badge is activated.',
                'type' => 'kyc_approved',
                'is_read' => 0,
                'action_url' => '/broker/profile?tab=kyc',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $validated['action'] === 'verify' 
                ? 'Document verified and locked successfully!' 
                : 'Document marked as rejected.',
            'doc_type' => $validated['doc_type'],
            'doc_status' => $docs[$validated['doc_type']]['status'],
            'allow_reupload' => !empty($docs[$validated['doc_type']]['allow_reupload']),
            'overall_kyc_status' => $this->determineKycStatus($broker),
            'all_verified' => $allUploadedVerified
        ]);
    }

    /**
     * Enable or Disable / Lock re-upload for a specific broker KYC document.
     */
    public function toggleReupload(Request $request, $id)
    {
        if (empty($id) || $id === 'null' || $id === 'undefined') {
            return response()->json(['success' => false, 'message' => 'Invalid broker ID provided.'], 400);
        }

        $validated = $request->validate([
            'doc_type' => ['required', 'string', 'in:id_proof,license_proof,bank_proof,other'],
            'allow' => ['required', 'boolean'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $broker = User::find($id);
        if (!$broker) {
            return response()->json(['success' => false, 'message' => 'Broker partner not found.'], 404);
        }
        $profile = UserProfile::firstOrCreate(['user_id' => $broker->id]);

        $preferences = $profile->preferences ?? [];
        if (!is_array($preferences)) {
            $preferences = json_decode($preferences, true) ?? [];
        }

        $docs = $preferences['documents'] ?? [];
        $type = $validated['doc_type'];

        if (!isset($docs[$type]) || !is_array($docs[$type])) {
            return response()->json([
                'success' => false,
                'message' => 'This document has not been uploaded yet.'
            ], 404);
        }

        $allow = (bool)$validated['allow'];
        $docs[$type]['allow_reupload'] = $allow;
        if ($allow && !empty(trim($validated['reason'] ?? ''))) {
            $docs[$type]['reupload_note'] = trim($validated['reason']);
        } elseif (!$allow) {
            unset($docs[$type]['reupload_note']);
        }

        $preferences['documents'] = $docs;
        $profile->preferences = $preferences;
        $profile->save();

        $docLabels = [
            'id_proof' => 'Government ID Proof',
            'license_proof' => 'RERA License / Property Deed',
            'bank_proof' => 'Bank Account Cheque Proof',
            'other' => 'Regulatory Document'
        ];
        $label = $docLabels[$type] ?? 'KYC Document';

        if ($allow) {
            Notification::create([
                'id' => (string) Str::uuid(),
                'user_id' => $broker->id,
                'user_type' => 'broker',
                'title' => "Re-upload Enabled for {$label} 🔓",
                'message' => "Admin has granted permission to update/re-upload your {$label}." . (!empty($validated['reason']) ? " Note: \"{$validated['reason']}\"" : ''),
                'type' => 'kyc_reupload_enabled',
                'is_read' => 0,
                'action_url' => '/broker/profile?tab=kyc',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $allow 
                ? "Re-upload permission granted for {$label}." 
                : "Re-upload permission locked for {$label}.",
            'doc_type' => $type,
            'allow_reupload' => $allow,
            'reupload_note' => $docs[$type]['reupload_note'] ?? null
        ]);
    }

    /**
     * Batch Approve / Reject Broker KYC Submissions.
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'broker_ids' => ['required', 'array', 'min:1'],
            'broker_ids.*' => ['required', 'uuid'],
            'action' => ['required', 'string', 'in:approve,reject'],
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $brokers = User::whereIn('id', $validated['broker_ids'])->get();
        $processedCount = 0;

        foreach ($brokers as $broker) {
            $profile = UserProfile::firstOrCreate(['user_id' => $broker->id]);
            $preferences = $profile->preferences ?? [];
            if (!is_array($preferences)) {
                $preferences = json_decode($preferences, true) ?? [];
            }

            $docs = $preferences['documents'] ?? [];

            if ($validated['action'] === 'approve') {
                foreach (['id_proof', 'license_proof', 'bank_proof', 'other'] as $k) {
                    if (isset($docs[$k]) && is_array($docs[$k])) {
                        $docs[$k]['status'] = 'verified';
                        $docs[$k]['allow_reupload'] = false;
                        $docs[$k]['reviewed_at'] = now()->toDateTimeString();
                        unset($docs[$k]['rejection_reason']);
                        unset($docs[$k]['reupload_note']);
                    }
                }
                $broker->kyc_verified_at = now();
                $broker->status = 'active';
                $broker->is_active = true;
                $broker->save();
            } else {
                $reason = $validated['reason'] ?: 'Document details could not be verified.';
                foreach ($docs as $k => &$doc) {
                    if (is_array($doc)) {
                        $doc['status'] = 'rejected';
                        $doc['allow_reupload'] = true;
                        $doc['rejection_reason'] = $reason;
                        $doc['reviewed_at'] = now()->toDateTimeString();
                    }
                }
                $broker->kyc_verified_at = null;
                $broker->save();
            }

            $preferences['documents'] = $docs;
            $profile->preferences = $preferences;
            $profile->save();
            $processedCount++;
        }

        $verb = $validated['action'] === 'approve' ? 'approved & verified' : 'rejected';

        return response()->json([
            'success' => true,
            'message' => "Successfully {$verb} KYC for {$processedCount} broker(s)!"
        ]);
    }

    /**
     * Helper to determine accurate broker KYC status.
     */
    private function determineKycStatus(User $broker): string
    {
        $profile = $broker->profile;
        $preferences = $profile ? ($profile->preferences ?? []) : [];
        if (!is_array($preferences)) {
            $preferences = json_decode($preferences, true) ?? [];
        }

        $docs = $preferences['documents'] ?? [];
        if (!is_array($docs)) {
            $docs = [];
        }

        $hasRejected = false;
        $hasPending = false;
        $hasAnyUpload = false;

        foreach (['id_proof', 'license_proof', 'bank_proof', 'other'] as $type) {
            $doc = $docs[$type] ?? null;
            if (!empty($doc) && is_array($doc) && !empty($doc['file_path'])) {
                $hasAnyUpload = true;
                $status = $doc['status'] ?? 'pending_review';
                if ($status === 'rejected') {
                    $hasRejected = true;
                } elseif ($status === 'pending_review' || empty($status)) {
                    $hasPending = true;
                }
            }
        }

        // 1. If any document is rejected, or broker account is suspended/inactive with uploads
        if ($hasRejected || ($hasAnyUpload && in_array($broker->status, ['suspended', 'inactive']))) {
            return 'rejected';
        }

        // 2. If broker is verified by admin (kyc_verified_at is set)
        if (!empty($broker->kyc_verified_at)) {
            return 'verified';
        }

        // 3. If any document is uploaded and awaiting review
        if ($hasPending || $hasAnyUpload) {
            return 'pending';
        }

        // 4. No documents uploaded yet
        return 'missing';
    }

    /**
     * Helper to extract documents with safe defaults.
     */
    private function extractDocuments(User $broker): array
    {
        $profile = $broker->profile;
        $preferences = $profile ? ($profile->preferences ?? []) : [];
        if (!is_array($preferences)) {
            $preferences = json_decode($preferences, true) ?? [];
        }

        $docs = $preferences['documents'] ?? [];
        $result = [];

        foreach (['id_proof', 'license_proof', 'bank_proof'] as $type) {
            $doc = $docs[$type] ?? null;
            $result[$type] = [
                'exists' => !empty($doc) && !empty($doc['file_path']),
                'name' => $doc['name'] ?? null,
                'file_path' => $doc['file_path'] ?? null,
                'doc_number' => $doc['doc_number'] ?? null,
                'status' => $doc['status'] ?? 'not_uploaded',
                'allow_reupload' => !empty($doc['allow_reupload']),
                'reupload_note' => $doc['reupload_note'] ?? null,
                'rejection_reason' => $doc['rejection_reason'] ?? null,
                'uploaded_at' => $doc['uploaded_at'] ?? null,
            ];
        }

        return $result;
    }
}
