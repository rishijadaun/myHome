<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminContactController extends Controller
{
    /**
     * Display a listing of all contact inquiries with statistics and filters.
     */
    public function index(Request $request)
    {
        // 1. Live Counters
        $totalInquiries = ContactInquiry::count();
        $newInquiries = ContactInquiry::where('status', 'new')->count();
        $inProgressInquiries = ContactInquiry::where('status', 'in_progress')->count();
        $resolvedInquiries = ContactInquiry::where('status', 'resolved')->count();
        $todayInquiries = ContactInquiry::where('created_at', '>=', now()->startOfDay())->count();

        // 2. Query Builder with Filters
        $query = ContactInquiry::query();

        // Search Filter
        $searchQuery = trim((string) $request->query('search', ''));
        if (!empty($searchQuery)) {
            $query->search($searchQuery);
        }

        // Status Filter
        $statusFilter = strtolower(trim((string) $request->query('status', 'all')));
        if ($statusFilter !== 'all' && in_array($statusFilter, ['new', 'in_progress', 'resolved', 'archived'])) {
            $query->where('status', $statusFilter);
        }

        // User Type Filter
        $typeFilter = strtolower(trim((string) $request->query('user_type', 'all')));
        if ($typeFilter !== 'all' && in_array($typeFilter, ['tenant', 'owner', 'partner', 'support', 'other'])) {
            $query->where('user_type', $typeFilter);
        }

        // Date Sorting
        $sortOrder = strtolower((string) $request->query('sort', 'latest')) === 'oldest' ? 'asc' : 'desc';
        $query->orderBy('created_at', $sortOrder);

        $inquiries = $query->paginate(15)->withQueryString();
        $userTypes = ContactInquiry::userTypes();
        $statuses = ContactInquiry::statuses();

        return view('admin.contacts', compact(
            'inquiries',
            'totalInquiries',
            'newInquiries',
            'inProgressInquiries',
            'resolvedInquiries',
            'todayInquiries',
            'searchQuery',
            'statusFilter',
            'typeFilter',
            'sortOrder',
            'userTypes',
            'statuses'
        ));
    }

    /**
     * Get specific inquiry details in JSON format for quick preview modal.
     */
    public function show($id)
    {
        $inquiry = ContactInquiry::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $inquiry->id,
                'name' => $inquiry->name,
                'email' => $inquiry->email,
                'phone' => $inquiry->phone,
                'user_type' => $inquiry->user_type,
                'user_type_label' => $inquiry->user_type_label,
                'city' => $inquiry->city ?? 'Not specified',
                'message' => $inquiry->message,
                'status' => $inquiry->status,
                'admin_notes' => $inquiry->admin_notes ?? '',
                'ip_address' => $inquiry->ip_address ?? 'N/A',
                'created_at_formatted' => $inquiry->created_at->format('M d, Y h:i A'),
                'created_at_diff' => $inquiry->created_at->diffForHumans(),
            ],
        ]);
    }

    /**
     * Update inquiry status and admin notes.
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:new,in_progress,resolved,archived'],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $inquiry = ContactInquiry::findOrFail($id);
        $inquiry->status = $validated['status'];
        if ($request->has('admin_notes')) {
            $inquiry->admin_notes = $validated['admin_notes'];
        }
        $inquiry->save();

        return response()->json([
            'success' => true,
            'message' => "Inquiry #{$inquiry->id} status updated to " . ucfirst(str_replace('_', ' ', $inquiry->status)) . ".",
            'status' => $inquiry->status,
            'admin_notes' => $inquiry->admin_notes,
        ]);
    }

    /**
     * Delete contact inquiry.
     */
    public function destroy(Request $request, $id)
    {
        $inquiry = ContactInquiry::findOrFail($id);
        $name = $inquiry->name;
        $inquiryId = $inquiry->id;
        $inquiry->delete();

        return response()->json([
            'success' => true,
            'message' => "Contact inquiry from \"{$name}\" (ID #{$inquiryId}) was permanently removed.",
        ]);
    }

    /**
     * Export all contact inquiries to Excel compatible CSV with full columns and proper formatting.
     */
    public function export(Request $request): StreamedResponse
    {
        $query = ContactInquiry::query();

        // Optional filter support during export
        if ($request->filled('search')) {
            $query->search(trim($request->query('search')));
        }
        if ($request->filled('status') && $request->query('status') !== 'all') {
            $query->where('status', $request->query('status'));
        }
        if ($request->filled('user_type') && $request->query('user_type') !== 'all') {
            $query->where('user_type', $request->query('user_type'));
        }

        $query->latest('created_at');

        $fileName = 'StayNest_Contact_Inquiries_' . Carbon::now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Microsoft Excel compatibility
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // CSV Column Headers
            fputcsv($handle, [
                'Inquiry ID',
                'Full Name',
                'Email Address',
                'Phone Number',
                'User Category',
                'City / Preferred Location',
                'Inquiry Message',
                'Status',
                'Admin Notes',
                'IP Address',
                'Submitted Date & Time',
            ]);

            $query->chunk(200, function ($records) use ($handle) {
                foreach ($records as $item) {
                    fputcsv($handle, [
                        $item->id,
                        $item->name,
                        $item->email,
                        $item->phone,
                        $item->user_type_label,
                        $item->city ?? 'N/A',
                        $item->message,
                        ucfirst(str_replace('_', ' ', $item->status)),
                        $item->admin_notes ?? '',
                        $item->ip_address ?? 'N/A',
                        $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : 'N/A',
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }
}
