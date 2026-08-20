<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Property;
use App\Models\PropertyReport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminReportController extends Controller
{
    /**
     * Display a listing of all reported listings with filters and metrics.
     */
    public function index(Request $request)
    {
        // 1. Live Statistics
        $totalReports = PropertyReport::count();
        $pendingReports = PropertyReport::where('status', 'pending')->count();
        $investigatingReports = PropertyReport::where('status', 'investigating')->count();
        $resolvedReports = PropertyReport::whereIn('status', ['resolved', 'action_taken'])->count();
        $dismissedReports = PropertyReport::where('status', 'dismissed')->count();
        $reportedPropertiesCount = PropertyReport::distinct('property_id')->count('property_id');

        // 2. Query Builder with Eager Loading
        $query = PropertyReport::with([
            'property.primaryImage',
            'property.city',
            'property.area',
            'property.broker.profile',
            'user.profile'
        ]);

        // Status Filter
        $statusFilter = strtolower(trim((string) $request->query('status', 'all')));
        if ($statusFilter !== 'all' && in_array($statusFilter, ['pending', 'investigating', 'resolved', 'dismissed', 'action_taken'])) {
            $query->where('status', $statusFilter);
        }

        // Reason Filter
        $reasonFilter = trim((string) $request->query('reason', 'all'));
        if ($reasonFilter !== 'all' && !empty($reasonFilter)) {
            $query->where('reason', 'like', "%{$reasonFilter}%");
        }

        // Specific Property Filter
        if ($request->filled('property_id')) {
            $query->where('property_id', $request->query('property_id'));
        }

        // Search Filter (Across reason, description, reporter name, email, phone, property name, address)
        $searchQuery = trim((string) $request->query('search', ''));
        if (!empty($searchQuery)) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('reason', 'like', "%{$searchQuery}%")
                  ->orWhere('description', 'like', "%{$searchQuery}%")
                  ->orWhere('reporter_name', 'like', "%{$searchQuery}%")
                  ->orWhere('reporter_email', 'like', "%{$searchQuery}%")
                  ->orWhere('reporter_phone', 'like', "%{$searchQuery}%")
                  ->orWhere('admin_notes', 'like', "%{$searchQuery}%")
                  ->orWhereHas('property', function ($pq) use ($searchQuery) {
                      $pq->where('name', 'like', "%{$searchQuery}%")
                         ->orWhere('address', 'like', "%{$searchQuery}%")
                         ->orWhere('landmark', 'like', "%{$searchQuery}%");
                  });
            });
        }

        // Date Sorting
        $sortOrder = strtolower((string) $request->query('sort', 'latest')) === 'oldest' ? 'asc' : 'desc';
        $query->orderBy('created_at', $sortOrder);

        $reports = $query->paginate(15)->withQueryString();

        // Get unique properties list for dropdown filter
        $flaggedProperties = Property::whereIn('id', function ($sub) {
            $sub->select('property_id')->from('property_reports');
        })->select('id', 'name')->orderBy('name')->get();

        // Common Reason presets
        $reasonsList = [
            'Fake Photos / Misleading Information',
            'Incorrect Rent / Hidden Charges',
            'Safety & Security Violation',
            'Harassment or Misbehavior by Host/Staff',
            'Fraud / Scam Listing',
            'Property Already Sold / Unavailable',
            'Hygiene / Maintenance Issues',
            'Other Violation'
        ];

        return view('admin.reports', compact(
            'reports',
            'totalReports',
            'pendingReports',
            'investigatingReports',
            'resolvedReports',
            'dismissedReports',
            'reportedPropertiesCount',
            'statusFilter',
            'reasonFilter',
            'searchQuery',
            'sortOrder',
            'flaggedProperties',
            'reasonsList'
        ));
    }

    /**
     * Get specific report details in JSON format for quick preview / modal.
     */
    public function show($id)
    {
        $report = PropertyReport::with([
            'property.primaryImage',
            'property.images',
            'property.city',
            'property.area',
            'property.broker.profile',
            'user.profile'
        ])->findOrFail($id);

        $property = $report->property;

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $report->id,
                'reason' => $report->reason,
                'description' => $report->description ?: 'No detailed description provided by reporter.',
                'status' => $report->status,
                'status_label' => ucfirst(str_replace('_', ' ', $report->status)),
                'admin_notes' => $report->admin_notes ?? '',
                'ip_address' => $report->ip_address ?? 'N/A',
                'created_at_formatted' => $report->created_at->format('M d, Y h:i A'),
                'created_at_diff' => $report->created_at->diffForHumans(),
                
                // Reporter details
                'reporter' => [
                    'name' => $report->reporter_name ?? ($report->user->name ?? 'Guest User'),
                    'email' => $report->reporter_email ?? ($report->user->email ?? 'Not provided'),
                    'phone' => $report->reporter_phone ?? ($report->user->phone ?? 'Not provided'),
                    'is_registered' => !empty($report->user_id),
                    'user_id' => $report->user_id,
                ],

                // Property details
                'property' => $property ? [
                    'id' => $property->id,
                    'name' => $property->name,
                    'slug' => $property->slug ?? $property->id,
                    'image' => $property->display_image_url,
                    'address' => $property->address,
                    'landmark' => $property->landmark,
                    'city' => $property->city->name ?? 'N/A',
                    'area' => $property->area->name ?? 'N/A',
                    'monthly_rent' => (int) $property->monthly_rent,
                    'formatted_price' => '₹' . number_format($property->monthly_rent) . '/mo',
                    'gender_preference' => strtoupper($property->gender_preference ?? 'CO-ED'),
                    'status' => $property->status,
                    'verification_status' => $property->verification_status,
                    'is_active' => (bool) $property->is_active,
                    'detail_url' => route('user.detail', $property->id),
                    'broker' => $property->broker ? [
                        'name' => $property->broker->profile->first_name ?? $property->broker->name,
                        'email' => $property->broker->email,
                        'phone' => $property->broker->phone ?? 'N/A',
                    ] : null,
                ] : null,
            ]
        ]);
    }

    /**
     * Update report status and admin notes.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,investigating,resolved,dismissed,action_taken'],
            'admin_notes' => ['nullable', 'string', 'max:3000'],
        ]);

        $report = PropertyReport::findOrFail($id);
        $report->status = $validated['status'];
        if ($request->has('admin_notes')) {
            $report->admin_notes = $validated['admin_notes'];
        }
        $report->save();

        return response()->json([
            'success' => true,
            'message' => "Report #{$report->id} status updated to " . ucfirst(str_replace('_', ' ', $report->status)) . ".",
            'status' => $report->status,
            'admin_notes' => $report->admin_notes,
        ]);
    }

    /**
     * Moderate / take action on reported property directly.
     */
    public function takePropertyAction(Request $request, $id)
    {
        $validated = $request->validate([
            'action' => ['required', 'string', 'in:suspend,activate,unpublish,verify'],
            'reason' => ['nullable', 'string', 'max:1000'],
        ]);

        $report = PropertyReport::with('property')->findOrFail($id);
        $property = $report->property;

        if (!$property) {
            return response()->json([
                'success' => false,
                'message' => 'Associated property not found or has already been deleted.'
            ], 404);
        }

        $adminUser = Auth::user();
        $adminName = $adminUser ? ($adminUser->profile->first_name ?? $adminUser->name) : 'Admin';
        $timestamp = now()->format('Y-m-d H:i');

        switch ($validated['action']) {
            case 'suspend':
                $property->status = 'inactive';
                $property->is_active = false;
                $actionText = 'Listing suspended (Status: Inactive)';
                break;
            case 'activate':
                $property->status = 'active';
                $property->is_active = true;
                $actionText = 'Listing activated (Status: Active)';
                break;
            case 'unpublish':
                $property->verification_status = 'rejected';
                $property->status = 'inactive';
                $property->is_active = false;
                $actionText = 'Listing rejected and unpublished from search';
                break;
            case 'verify':
                $property->verification_status = 'verified';
                $property->status = 'active';
                $property->is_active = true;
                $actionText = 'Listing re-verified as safe';
                break;
        }

        $property->save();

        // Update Report status and log action in admin notes
        $report->status = in_array($validated['action'], ['suspend', 'unpublish']) ? 'action_taken' : 'resolved';
        $logEntry = "[{$timestamp} by {$adminName}]: {$actionText}. " . ($validated['reason'] ?? '');
        $report->admin_notes = trim(($report->admin_notes ? $report->admin_notes . "\n" : '') . $logEntry);
        $report->save();

        // Notify Broker if broker exists
        if ($property->broker_id) {
            try {
                Notification::create([
                    'user_id' => $property->broker_id,
                    'user_type' => 'broker',
                    'title' => 'Listing Moderation Update ⚠️',
                    'message' => "Your listing \"{$property->name}\" was reviewed by admin moderation: {$actionText}.",
                    'type' => 'property_moderation',
                    'data' => json_encode(['property_id' => $property->id, 'action' => $validated['action']]),
                    'is_read' => false,
                ]);
            } catch (\Exception $e) {
                // Ignore notification failure
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Successfully executed: {$actionText} for \"{$property->name}\".",
            'property_status' => $property->status,
            'property_verification' => $property->verification_status,
            'report_status' => $report->status,
            'admin_notes' => $report->admin_notes,
        ]);
    }

    /**
     * Permanently delete / dismiss a property report.
     */
    public function destroy(Request $request, $id)
    {
        $report = PropertyReport::findOrFail($id);
        $reportId = $report->id;
        $report->delete();

        return response()->json([
            'success' => true,
            'message' => "Report #{$reportId} was successfully removed from database.",
        ]);
    }
}
