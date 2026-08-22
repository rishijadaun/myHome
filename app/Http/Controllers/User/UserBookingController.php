<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Notification;
use App\Models\Property;
use App\Models\User;
use App\Services\ContentModerationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserBookingController extends Controller
{
    /**
     * Display User's Bookings Page.
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('user.login')->with('warning', 'Please sign in to view your bookings.');
        }

        $userId = Auth::id();

        // Fetch all bookings for the logged-in user
        $allBookings = Booking::where('user_id', $userId)
            ->with(['property.primaryImage', 'property.images', 'property.city', 'property.area', 'broker'])
            ->latest('created_at')
            ->get();

        // Filter into Upcoming, Completed, and Cancelled
        $upcoming = $allBookings->filter(function ($b) {
            $isCancelled = ($b->broker_approval === 'rejected' || $b->booking_status === 'cancelled');
            $isCompleted = ($b->booking_status === 'completed' || $b->booking_status === 'moved_out');
            return !$isCancelled && !$isCompleted;
        })->values();

        $completed = $allBookings->filter(function ($b) {
            return ($b->booking_status === 'completed' || $b->booking_status === 'moved_out');
        })->values();

        $cancelled = $allBookings->filter(function ($b) {
            return ($b->broker_approval === 'rejected' || $b->booking_status === 'cancelled');
        })->values();

        $upcomingCount = $upcoming->count();
        $completedCount = $completed->count();
        $cancelledCount = $cancelled->count();

        return view('user.bookings', compact(
            'upcoming',
            'completed',
            'cancelled',
            'upcomingCount',
            'completedCount',
            'cancelledCount'
        ));
    }

    /**
     * Store a new booking request.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'require_login' => true,
                'message' => 'Please sign in to book this stay.',
                'redirect_url' => route('user.login')
            ], 401);
        }

        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'duration_months' => 'required|integer|min:1|max:36',
            'room_type_name' => 'nullable|string|max:100',
            'base_rent' => 'nullable|numeric|min:0',
            'tenant_name' => 'nullable|string|max:150',
            'tenant_phone' => 'nullable|string|max:30',
            'tenant_email' => 'nullable|email|max:150',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        // Server-Side Content Moderation Check (Gali, Profanity, Abuse, Prohibited Content)
        if (!empty($validated['tenant_name'])) {
            $modName = ContentModerationService::validateContent(['name' => $validated['tenant_name']]);
            if (!$modName['passed']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your Full Name contains inappropriate terms. Please enter a valid name.',
                ], 422);
            }
        }

        if (!empty($validated['special_requests'])) {
            $modReq = ContentModerationService::validateContent(['description' => $validated['special_requests']]);
            if (!$modReq['passed']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Special Requests contain prohibited content: ' . ($modReq['reason'] ?? 'inappropriate terms.') . ' Please remove them.',
                ], 422);
            }
        }

        $property = Property::with(['broker'])->findOrFail($validated['property_id']);
        $user = Auth::user();

        // Calculate rent amounts
        $baseRent = !empty($validated['base_rent']) && (float)$validated['base_rent'] > 0 
            ? (float)$validated['base_rent'] 
            : (float)($property->monthly_rent ?: 5000);

        $securityDeposit = (float)($property->security_deposit ?: 0);
        $maintenanceCharges = (float)($property->maintenance_charges ?: 0);
        $totalAmount = $baseRent + $securityDeposit;

        // Determine Broker ID (Property Owner or Fallback Admin)
        $brokerId = $property->broker_id;
        if (empty($brokerId)) {
            $adminUser = User::whereHas('roles', fn($q) => $q->where('slug', 'super_admin'))->first();
            $brokerId = $adminUser ? $adminUser->id : $user->id;
        }

        // Room Type Label
        $roomTypeName = $validated['room_type_name'] ?? 'Standard Stay';

        // Check-in & Check-out dates
        $checkIn = Carbon::parse($validated['check_in_date']);
        $durationMonths = (int)$validated['duration_months'];
        $checkOut = $checkIn->copy()->addMonths($durationMonths);

        $tenantName = !empty($validated['tenant_name']) ? $validated['tenant_name'] : ($user->name ?? 'Guest Tenant');
        $tenantPhone = !empty($validated['tenant_phone']) ? $validated['tenant_phone'] : ($user->phone ?? '');
        $tenantEmail = !empty($validated['tenant_email']) ? $validated['tenant_email'] : ($user->email ?? '');

        // Create the booking record
        $booking = Booking::create([
            'id' => (string) Str::uuid(),
            'booking_id' => 'BK-' . strtoupper(Str::random(8)),
            'user_id' => $user->id,
            'tenant_name' => $tenantName,
            'tenant_phone' => $tenantPhone,
            'tenant_email' => $tenantEmail,
            'property_id' => $property->id,
            'room_type_name' => $roomTypeName,
            'broker_id' => $brokerId,
            'check_in_date' => $checkIn->toDateString(),
            'check_out_date' => $checkOut->toDateString(),
            'duration_months' => $durationMonths,
            'base_rent' => $baseRent,
            'security_deposit' => $securityDeposit,
            'maintenance_charges' => $maintenanceCharges,
            'total_amount' => $totalAmount,
            'paid_amount' => 0.00,
            'payment_status' => 'pending',
            'booking_status' => 'pending',
            'broker_approval' => 'pending',
            'special_requests' => $validated['special_requests'] ?? null,
            'is_active' => true,
        ]);

        // Send notification to the Property Owner / Broker
        if ($brokerId && $brokerId !== $user->id) {
            Notification::create([
                'id' => (string) Str::uuid(),
                'user_id' => $brokerId,
                'user_type' => 'broker',
                'title' => 'New Booking Request Received! 📋',
                'message' => "{$booking->tenant_name} has requested a stay at {$property->name} starting {$checkIn->format('M d, Y')}.",
                'type' => 'booking_created',
                'is_read' => 0,
                'action_url' => '/broker/bookings',
            ]);
        }

        // Send notification to the Tenant
        Notification::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'user_type' => 'user',
            'title' => 'Booking Request Sent! 🚀',
            'message' => "Your request for {$property->name} (#{$booking->booking_id}) is sent to the owner for approval. Zero payment required now.",
            'type' => 'booking_pending',
            'is_read' => 0,
            'action_url' => '/bookings',
        ]);

        return response()->json([
            'success' => true,
            'message' => "🎉 Booking request sent successfully! Booking ID: {$booking->booking_id}. The property owner will review and confirm your stay.",
            'booking_id' => $booking->booking_id,
            'redirect_url' => route('user.bookings'),
        ]);
    }

    /**
     * Cancel an existing booking by tenant.
     */
    public function cancel(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $booking = Booking::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($booking->booking_status === 'cancelled' || $booking->broker_approval === 'rejected') {
            return response()->json(['success' => false, 'message' => 'Booking is already cancelled.']);
        }

        $booking->booking_status = 'cancelled';
        $booking->cancellation_reason = $request->input('reason', 'Cancelled by tenant');
        $booking->save();

        // Notify Broker
        if ($booking->broker_id) {
            Notification::create([
                'id' => (string) Str::uuid(),
                'user_id' => $booking->broker_id,
                'user_type' => 'broker',
                'title' => 'Booking Cancelled by Tenant',
                'message' => "Booking #{$booking->booking_id} for {$booking->property?->name} was cancelled by tenant.",
                'type' => 'booking_cancelled',
                'is_read' => 0,
                'action_url' => '/broker/bookings',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking has been cancelled successfully.',
        ]);
    }
}
