<?php

namespace App\Http\Controllers\Broker;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BrokerBookingController extends Controller
{
    /**
     * Display all bookings for the logged-in broker / property owner.
     */
    public function index(Request $request)
    {
        $broker = Auth::user();
        if (!$broker) {
            return redirect()->route('broker.login');
        }

        $brokerId = $broker->id;

        // Base Query
        $query = Booking::where('broker_id', $brokerId)
            ->with(['property.primaryImage', 'property.images', 'user.profile']);

        // Metric Counters (Pre-filters)
        $totalCount = Booking::where('broker_id', $brokerId)->count();
        
        $pendingCount = Booking::where('broker_id', $brokerId)
            ->where('broker_approval', 'pending')
            ->where('booking_status', 'pending')
            ->count();

        $confirmedCount = Booking::where('broker_id', $brokerId)
            ->where(function ($q) {
                $q->where('broker_approval', 'approved')
                  ->orWhere('booking_status', 'confirmed');
            })
            ->whereNotIn('booking_status', ['completed', 'cancelled'])
            ->count();

        $completedCount = Booking::where('broker_id', $brokerId)
            ->where('booking_status', 'completed')
            ->count();

        $cancelledCount = Booking::where('broker_id', $brokerId)
            ->where(function ($q) {
                $q->where('broker_approval', 'rejected')
                  ->orWhere('booking_status', 'cancelled');
            })
            ->count();

        // Filter by Status Tab
        $activeTab = strtoupper($request->query('status', 'ALL'));
        if ($activeTab === 'PENDING') {
            $query->where('broker_approval', 'pending')->where('booking_status', 'pending');
        } elseif ($activeTab === 'CONFIRMED') {
            $query->where(function ($q) {
                $q->where('broker_approval', 'approved')->orWhere('booking_status', 'confirmed');
            })->whereNotIn('booking_status', ['completed', 'cancelled']);
        } elseif ($activeTab === 'COMPLETED') {
            $query->where('booking_status', 'completed');
        } elseif ($activeTab === 'CANCELLED') {
            $query->where(function ($q) {
                $q->where('broker_approval', 'rejected')->orWhere('booking_status', 'cancelled');
            });
        }

        // Date Filter
        $dateFilter = $request->query('date', '');
        if ($dateFilter === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($dateFilter === 'week') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($dateFilter === 'month') {
            $query->whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year);
        }

        // Search Filter
        $search = $request->query('search');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_id', 'LIKE', "%{$search}%")
                  ->orWhere('tenant_name', 'LIKE', "%{$search}%")
                  ->orWhere('tenant_phone', 'LIKE', "%{$search}%")
                  ->orWhere('tenant_email', 'LIKE', "%{$search}%")
                  ->orWhereHas('user.profile', function ($pq) use ($search) {
                      $pq->where('first_name', 'LIKE', "%{$search}%")
                        ->orWhere('last_name', 'LIKE', "%{$search}%")
                        ->orWhere('full_name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('user', fn($uq) => $uq->where('phone', 'LIKE', "%{$search}%")->orWhere('email', 'LIKE', "%{$search}%"))
                  ->orWhereHas('property', fn($pq) => $pq->where('name', 'LIKE', "%{$search}%"));
            });
        }

        $bookings = $query->latest('created_at')->paginate(15)->withQueryString();

        return view('broker.bookings', compact(
            'broker',
            'bookings',
            'totalCount',
            'confirmedCount',
            'pendingCount',
            'completedCount',
            'cancelledCount',
            'activeTab',
            'dateFilter',
            'search'
        ));
    }

    /**
     * Approve / Confirm booking request.
     */
    public function approve(Request $request, $id)
    {
        $broker = Auth::user();
        if (!$broker) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $booking = Booking::where('id', $id)
            ->where('broker_id', $broker->id)
            ->firstOrFail();

        $booking->booking_status = 'confirmed';
        $booking->broker_approval = 'approved';
        $booking->save();

        // Decrement available beds on property if applicable
        if ($booking->property && $booking->property->available_beds > 0) {
            $booking->property->decrement('available_beds');
        }

        // Notify Tenant
        if ($booking->user_id) {
            Notification::create([
                'id' => (string) Str::uuid(),
                'user_id' => $booking->user_id,
                'user_type' => 'user',
                'title' => 'Booking Approved! 🎉',
                'message' => "Great news! Your booking for {$booking->property?->name} (#{$booking->booking_id}) has been ACCEPTED by the owner.",
                'type' => 'booking_approved',
                'is_read' => 0,
                'action_url' => '/bookings',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Booking #{$booking->booking_id} for {$booking->effective_tenant_name} has been ACCEPTED & CONFIRMED! 🎉",
            'booking' => $booking,
        ]);
    }

    /**
     * Reject / Decline booking request.
     */
    public function reject(Request $request, $id)
    {
        $broker = Auth::user();
        if (!$broker) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $booking = Booking::where('id', $id)
            ->where('broker_id', $broker->id)
            ->firstOrFail();

        $reason = $request->input('reason', 'Declined by property owner due to unavailability.');
        $booking->booking_status = 'cancelled';
        $booking->broker_approval = 'rejected';
        $booking->cancellation_reason = $reason;
        $booking->save();

        // Notify Tenant
        if ($booking->user_id) {
            Notification::create([
                'id' => (string) Str::uuid(),
                'user_id' => $booking->user_id,
                'user_type' => 'user',
                'title' => 'Booking Request Update',
                'message' => "Your booking request for {$booking->property?->name} (#{$booking->booking_id}) could not be accommodated: {$reason}",
                'type' => 'booking_rejected',
                'is_read' => 0,
                'action_url' => '/bookings',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Booking #{$booking->booking_id} has been DECLINED.",
            'booking' => $booking,
        ]);
    }

    /**
     * Mark stay as Completed (Check-out / Stay Finished).
     */
    public function complete(Request $request, $id)
    {
        $broker = Auth::user();
        if (!$broker) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $booking = Booking::where('id', $id)
            ->where('broker_id', $broker->id)
            ->firstOrFail();

        $booking->booking_status = 'completed';
        $booking->broker_approval = 'approved';
        $booking->save();

        // Increment back available beds if applicable
        if ($booking->property) {
            $booking->property->increment('available_beds');
        }

        // Notify Tenant
        if ($booking->user_id) {
            Notification::create([
                'id' => (string) Str::uuid(),
                'user_id' => $booking->user_id,
                'user_type' => 'user',
                'title' => 'Stay Completed 🌟',
                'message' => "Thank you for staying at {$booking->property?->name}! Please consider leaving a review for other prospective tenants.",
                'type' => 'booking_completed',
                'is_read' => 0,
                'action_url' => '/bookings',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Booking #{$booking->booking_id} has been marked as COMPLETED! 🎉",
            'booking' => $booking,
        ]);
    }

    /**
     * Export broker bookings to CSV.
     */
    public function export(Request $request)
    {
        $broker = Auth::user();
        if (!$broker) {
            return redirect()->route('broker.login');
        }

        $bookings = Booking::where('broker_id', $broker->id)
            ->with(['property', 'user'])
            ->latest('created_at')
            ->get();

        $filename = 'staynest_bookings_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($bookings) {
            $handle = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($handle, [
                'Booking ID',
                'Created Date',
                'Tenant Name',
                'Tenant Phone',
                'Tenant Email',
                'Property Name',
                'Room Plan',
                'Check-in Date',
                'Duration (Months)',
                'Monthly Rent (INR)',
                'Security Deposit (INR)',
                'Total Amount (INR)',
                'Status',
                'Owner Approval',
                'Special Requests'
            ]);

            foreach ($bookings as $bk) {
                fputcsv($handle, [
                    $bk->booking_id,
                    $bk->created_at ? $bk->created_at->format('Y-m-d H:i:s') : '',
                    $bk->effective_tenant_name,
                    $bk->effective_tenant_phone,
                    $bk->tenant_email ?: ($bk->user?->email ?? ''),
                    $bk->property?->name ?? 'N/A',
                    $bk->room_type_name ?: 'Standard Stay',
                    $bk->check_in_date ? $bk->check_in_date->format('Y-m-d') : 'Immediate',
                    $bk->duration_months ?: 11,
                    $bk->base_rent,
                    $bk->security_deposit ?: 0,
                    $bk->total_amount ?: ($bk->base_rent + $bk->security_deposit),
                    strtoupper($bk->booking_status),
                    strtoupper($bk->broker_approval),
                    $bk->special_requests ?: 'None'
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
