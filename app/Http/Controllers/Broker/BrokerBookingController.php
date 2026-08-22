<?php

namespace App\Http\Controllers\Broker;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Notification;
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

        // Query all bookings for this broker's properties
        $query = Booking::where('broker_id', $brokerId)
            ->with(['property.primaryImage', 'property.images', 'user.profile']);

        // Metric Counters
        $totalCount = Booking::where('broker_id', $brokerId)->count();
        
        $confirmedCount = Booking::where('broker_id', $brokerId)
            ->where(function ($q) {
                $q->where('broker_approval', 'approved')
                  ->orWhere('booking_status', 'confirmed');
            })
            ->where('booking_status', '!=', 'cancelled')
            ->count();

        $pendingCount = Booking::where('broker_id', $brokerId)
            ->where('broker_approval', 'pending')
            ->where('booking_status', 'pending')
            ->count();

        $cancelledCount = Booking::where('broker_id', $brokerId)
            ->where(function ($q) {
                $q->where('broker_approval', 'rejected')
                  ->orWhere('booking_status', 'cancelled');
            })
            ->count();

        // Filter by tab if requested
        $activeTab = strtoupper($request->query('status', 'ALL'));
        if ($activeTab === 'PENDING') {
            $query->where('broker_approval', 'pending')->where('booking_status', 'pending');
        } elseif ($activeTab === 'CONFIRMED') {
            $query->where(function ($q) {
                $q->where('broker_approval', 'approved')->orWhere('booking_status', 'confirmed');
            })->where('booking_status', '!=', 'cancelled');
        } elseif ($activeTab === 'CANCELLED') {
            $query->where(function ($q) {
                $q->where('broker_approval', 'rejected')->orWhere('booking_status', 'cancelled');
            });
        }

        // Search filter
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_id', 'LIKE', "%{$search}%")
                  ->orWhere('tenant_name', 'LIKE', "%{$search}%")
                  ->orWhere('tenant_phone', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', fn($uq) => $uq->where('name', 'LIKE', "%{$search}%")->orWhere('phone', 'LIKE', "%{$search}%"))
                  ->orWhereHas('property', fn($pq) => $pq->where('name', 'LIKE', "%{$search}%"));
            });
        }

        $bookings = $query->latest('created_at')->paginate(20)->withQueryString();

        return view('broker.bookings', compact(
            'broker',
            'bookings',
            'totalCount',
            'confirmedCount',
            'pendingCount',
            'cancelledCount',
            'activeTab'
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
            'message' => "Booking #{$booking->booking_id} for {$booking->effective_tenant_name} has been APPROVED! 🎉",
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
}
