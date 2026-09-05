<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Models\City;
use App\Models\Property;
use Illuminate\Http\Request;

class AppController extends Controller
{
    use ApiResponse;

    /**
     * Android Play Store Version Check & Maintenance API
     */
    public function checkUpdate(Request $request)
    {
        return $this->success('App status fetched', [
            'app_name' => 'SpaceSeeks - PG, Flat & Co-Living',
            'package_name' => 'com.spaceseeks.app',
            'latest_version' => '1.0.4',
            'min_version' => '1.0.0',
            'force_update' => false,
            'maintenance_mode' => false,
            'play_store_url' => 'https://play.google.com/store/apps/details?id=com.spaceseeks.app',
            'support_contact' => [
                'phone' => '+91 98765 43210',
                'whatsapp' => '+91 98765 43210',
                'email' => 'support@spaceseeks.com',
            ],
            'changelog' => [
                '• Added Smart AI Stay Matcher Assistant',
                '• Faster Booking & Schedule Visit Workflow',
                '• 100% Zero Brokerage Stays Across India',
            ],
        ]);
    }

    /**
     * Public Properties Discovery API (For Mobile App & SEO Web)
     */
    public function properties(Request $request)
    {
        $query = Property::where('status', 'active')
            ->where('verification_status', 'verified')
            ->with(['city', 'area', 'propertyType', 'primaryImage', 'amenities']);

        // Filter by listing_type / type (e.g. pg, hostel, coliving, flat, apartment, commercial)
        if ($request->filled('type') || $request->filled('listing_type')) {
            $typeSlug = $request->query('type') ?? $request->query('listing_type');
            $query->whereHas('propertyType', function ($q) use ($typeSlug) {
                $q->where('slug', $typeSlug)
                  ->orWhere('name', 'like', "%{$typeSlug}%");
            });
        }

        if ($request->filled('city')) {
            $citySlug = $request->query('city');
            $query->whereHas('city', function ($q) use ($citySlug) {
                $q->where('slug', $citySlug)->orWhere('name', 'like', "%{$citySlug}%");
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender_preference', $request->query('gender'));
        }

        if ($request->filled('max_price')) {
            $query->where('monthly_rent', '<=', $request->query('max_price'));
        }

        if ($request->filled('min_price')) {
            $query->where('monthly_rent', '>=', $request->query('min_price'));
        }

        $properties = $query->latest()->paginate(12);

        return $this->success('Properties fetched successfully', $properties);
    }

    /**
     * Cities & Locations Directory API
     */
    public function locations()
    {
        $cities = City::where('is_active', 1)
            ->with(['areas' => function ($q) {
                $q->where('is_active', 1);
            }])
            ->get();

        return $this->success('Locations fetched successfully', $cities);
    }
}
