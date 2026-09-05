<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminBookingController extends Controller
{
    /**
     * Display all system bookings with filters and metrics.
     */
    public function index(Request $request)
    {
        $query = Booking::with(['property.primaryImage', 'user.profile', 'broker.profile']);

        // Overall stats
        $totalBookings = Booking::count();

        $confirmedCount = Booking::where(function ($q) {
            $q->where('broker_approval', 'approved')->orWhere('booking_status', 'confirmed');
        })->where('booking_status', '!=', 'cancelled')->count();

        $pendingCount = Booking::where('broker_approval', 'pending')
            ->where('booking_status', 'pending')
            ->count();

        $cancelledCount = Booking::where(function ($q) {
            $q->where('broker_approval', 'rejected')->orWhere('booking_status', 'cancelled');
        })->count();

        // Status Filter
        $status = strtoupper($request->query('status', ''));
        if ($status === 'CONFIRMED') {
            $query->where(function ($q) {
                $q->where('broker_approval', 'approved')->orWhere('booking_status', 'confirmed');
            })->where('booking_status', '!=', 'cancelled');
        } elseif ($status === 'PENDING') {
            $query->where('broker_approval', 'pending')->where('booking_status', 'pending');
        } elseif ($status === 'CANCELLED') {
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
        $search = $request->query('search', '');
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
                  ->orWhereHas('user', fn($uq) => $uq->where('email', 'LIKE', "%{$search}%")->orWhere('phone', 'LIKE', "%{$search}%"))
                  ->orWhereHas('property', fn($pq) => $pq->where('name', 'LIKE', "%{$search}%"))
                  ->orWhereHas('broker.profile', function ($bpq) use ($search) {
                      $bpq->where('first_name', 'LIKE', "%{$search}%")
                        ->orWhere('last_name', 'LIKE', "%{$search}%")
                        ->orWhere('full_name', 'LIKE', "%{$search}%");
                  })
                  ->orWhereHas('broker', fn($bq) => $bq->where('email', 'LIKE', "%{$search}%")->orWhere('phone', 'LIKE', "%{$search}%"));
            });
        }

        $bookings = $query->latest('created_at')->paginate(25)->withQueryString();

        return view('admin.bookings', compact(
            'bookings',
            'totalBookings',
            'confirmedCount',
            'pendingCount',
            'cancelledCount',
            'status',
            'dateFilter',
            'search'
        ));
    }

    /**
     * Admin approve / confirm a booking.
     */
    public function approve(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $booking->booking_status = 'confirmed';
        $booking->broker_approval = 'approved';
        $booking->save();

        if ($booking->property && $booking->property->available_beds > 0) {
            $booking->property->decrement('available_beds');
        }

        // Notify Tenant
        if ($booking->user_id) {
            Notification::create([
                'id' => (string) Str::uuid(),
                'user_id' => $booking->user_id,
                'user_type' => 'user',
                'title' => 'Booking Confirmed by Admin 🎉',
                'message' => "Your booking for {$booking->property?->name} (#{$booking->booking_id}) has been confirmed by SpaceSeeks Admin.",
                'type' => 'booking_approved',
                'is_read' => 0,
                'action_url' => '/bookings',
            ]);
        }

        // Notify Broker
        if ($booking->broker_id) {
            Notification::create([
                'id' => (string) Str::uuid(),
                'user_id' => $booking->broker_id,
                'user_type' => 'broker',
                'title' => 'Booking Confirmed by Admin',
                'message' => "Booking #{$booking->booking_id} for {$booking->property?->name} was approved by Admin.",
                'type' => 'booking_approved',
                'is_read' => 0,
                'action_url' => '/broker/bookings',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Booking #{$booking->booking_id} has been confirmed successfully! 🎉",
            'booking' => $booking,
        ]);
    }

    /**
     * Admin reject / cancel a booking.
     */
    public function reject(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $reason = $request->input('reason', 'Cancelled by SpaceSeeks Administration.');
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
                'title' => 'Booking Cancelled',
                'message' => "Your booking #{$booking->booking_id} for {$booking->property?->name} has been cancelled: {$reason}",
                'type' => 'booking_rejected',
                'is_read' => 0,
                'action_url' => '/bookings',
            ]);
        }

        // Notify Broker
        if ($booking->broker_id) {
            Notification::create([
                'id' => (string) Str::uuid(),
                'user_id' => $booking->broker_id,
                'user_type' => 'broker',
                'title' => 'Booking Cancelled by Admin',
                'message' => "Booking #{$booking->booking_id} for {$booking->property?->name} was cancelled by Admin.",
                'type' => 'booking_cancelled',
                'is_read' => 0,
                'action_url' => '/broker/bookings',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => "Booking #{$booking->booking_id} has been cancelled.",
            'booking' => $booking,
        ]);
    }

    /**
     * Export system bookings to CSV.
     */
    public function export(Request $request)
    {
        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Booking ID',
                'Tenant Name',
                'Tenant Phone',
                'Tenant Email',
                'Property Name',
                'Property Address',
                'Owner / Broker Name',
                'Check-In Date',
                'Check-Out Date',
                'Duration (Months)',
                'Base Rent (INR)',
                'Security Deposit (INR)',
                'Total Amount (INR)',
                'Booking Status',
                'Owner Approval',
                'Payment Status',
                'Created At'
            ]);

            Booking::with(['property', 'user', 'broker'])->chunk(100, function ($bookings) use ($handle) {
                foreach ($bookings as $b) {
                    fputcsv($handle, [
                        $b->booking_id,
                        $b->effective_tenant_name,
                        $b->effective_tenant_phone,
                        $b->tenant_email ?: ($b->user?->email ?? ''),
                        $b->property?->name ?? 'N/A',
                        $b->property?->address ?? '',
                        $b->broker?->name ?? 'N/A',
                        $b->check_in_date ? $b->check_in_date->format('Y-m-d') : '',
                        $b->check_out_date ? $b->check_out_date->format('Y-m-d') : '',
                        $b->duration_months,
                        $b->base_rent,
                        $b->security_deposit,
                        $b->total_amount,
                        $b->booking_status,
                        $b->broker_approval,
                        $b->payment_status,
                        $b->created_at ? $b->created_at->format('Y-m-d H:i:s') : '',
                    ]);
                }
            });

            fclose($handle);
        });

        $filename = 'spaceseeks_bookings_' . date('Y-m-d_His') . '.csv';
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}
