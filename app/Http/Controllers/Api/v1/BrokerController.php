<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Models\Booking;
use App\Models\Property;
use App\Models\PropertyVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrokerController extends Controller
{
    use ApiResponse;

    /**
     * Broker Dashboard Summary API
     */
    public function dashboard(Request $request)
    {
        $broker = $request->user();

        $totalProperties = Property::where('broker_id', $broker->id)->count();
        $totalBeds = Property::where('broker_id', $broker->id)->sum('total_beds');
        $availableBeds = Property::where('broker_id', $broker->id)->sum('available_beds');

        $pendingBookings = Booking::where('broker_id', $broker->id)
            ->where('booking_status', 'pending')
            ->count();

        $activeBookings = Booking::where('broker_id', $broker->id)
            ->where('booking_status', 'confirmed')
            ->count();

        $totalRevenue = Booking::where('broker_id', $broker->id)
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        return $this->success('Broker dashboard loaded', [
            'broker' => [
                'id' => $broker->id,
                'name' => trim(($broker->profile?->first_name ?? '') . ' ' . ($broker->profile?->last_name ?? '')),
                'company_name' => $broker->profile?->company_name,
                'email' => $broker->email,
                'phone' => $broker->phone,
            ],
            'stats' => [
                'total_properties' => $totalProperties,
                'total_beds' => $totalBeds,
                'occupied_beds' => max(0, $totalBeds - $availableBeds),
                'available_beds' => $availableBeds,
                'pending_bookings' => $pendingBookings,
                'active_bookings' => $activeBookings,
                'total_revenue' => number_format($totalRevenue, 2, '.', ''),
            ],
        ]);
    }

    /**
     * Broker's Properties Listing API
     */
    public function listings(Request $request)
    {
        $broker = $request->user();

        $properties = Property::where('broker_id', $broker->id)
            ->with(['city', 'area'])
            ->latest()
            ->paginate(15);

        return $this->success('Broker listings fetched', $properties);
    }

    /**
     * Add New Property API
     */
    public function storeProperty(Request $request)
    {
        $broker = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'city_id' => ['required', 'string', 'exists:cities,id'],
            'area_id' => ['required', 'string', 'exists:areas,id'],
            'address' => ['required', 'string'],
            'landmark' => ['nullable', 'string', 'max:200'],
            'gender_preference' => ['required', 'in:boys,girls,co-ed'],
            'monthly_rent' => ['required', 'numeric', 'min:1'],
            'security_deposit' => ['nullable', 'numeric', 'min:0'],
            'total_beds' => ['required', 'integer', 'min:1'],
            'available_beds' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        $property = Property::create([
            'id' => (string) Str::uuid(),
            'broker_id' => $broker->id,
            'name' => $validated['name'],
            'city_id' => $validated['city_id'],
            'area_id' => $validated['area_id'],
            'property_type_id' => 'c9a76400-8dab-11f1-a4cf-1062e5a5cd6c', // PG / Hostel default
            'address' => $validated['address'],
            'landmark' => $validated['landmark'] ?? null,
            'gender_preference' => $validated['gender_preference'],
            'monthly_rent' => $validated['monthly_rent'],
            'security_deposit' => $validated['security_deposit'] ?? 0.00,
            'total_beds' => $validated['total_beds'],
            'available_beds' => $validated['available_beds'],
            'description' => $validated['description'] ?? null,
            'status' => 'active',
            'is_active' => 1,
            'version' => 1,
        ]);

        return $this->success('Property listed successfully', $property, 201);
    }

    /**
     * Broker's Tenant Bookings API
     */
    public function bookings(Request $request)
    {
        $broker = $request->user();

        $bookings = Booking::where('broker_id', $broker->id)
            ->with(['user.profile', 'property'])
            ->latest()
            ->paginate(15);

        return $this->success('Broker bookings fetched', $bookings);
    }

    /**
     * Approve / Reject Booking API
     */
    public function updateBookingStatus(Request $request, $id)
    {
        $broker = $request->user();

        $booking = Booking::where('id', $id)
            ->where('broker_id', $broker->id)
            ->firstOrFail();

        $validated = $request->validate([
            'status' => ['required', 'in:confirmed,cancelled'],
            'cancellation_reason' => ['nullable', 'string'],
        ]);

        $booking->booking_status = $validated['status'];
        $booking->broker_approval = $validated['status'] === 'confirmed' ? 'approved' : 'rejected';
        if (!empty($validated['cancellation_reason'])) {
            $booking->cancellation_reason = $validated['cancellation_reason'];
        }
        $booking->save();

        return $this->success("Booking marked as {$validated['status']}", $booking);
    }

    /**
     * Broker's Scheduled Visits API
     */
    public function visits(Request $request)
    {
        $broker = $request->user();

        $visits = PropertyVisit::whereHas('property', function ($q) use ($broker) {
            $q->where('broker_id', $broker->id);
        })->with(['user.profile', 'property.city', 'property.area'])
          ->latest()
          ->paginate(15);

        return $this->success('Broker visits fetched', $visits);
    }
}
