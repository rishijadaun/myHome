<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ApiResponse;
use App\Models\Amenity;
use App\Models\Area;
use App\Models\City;
use App\Models\Notification;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\PropertyRule;
use App\Models\PropertyType;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserRole;
use App\Services\ContentModerationService;
use App\Services\ImageModerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PropertySubmissionController extends Controller
{
    use ApiResponse;

    /**
     * Get dynamic listing types and amenities for form dropdowns
     */
    public function types()
    {
        $types = PropertyType::where('is_active', 1)->get();

        if ($types->isEmpty()) {
            $defaultTypes = [
                ['id' => 'c9a76400-8dab-11f1-a4cf-1062e5a5cd6c', 'name' => 'PG / Hostel', 'slug' => 'pg-hostel', 'is_active' => 1],
                ['id' => 'c9a76614-8dab-11f1-a4cf-1062e5a5cd6c', 'name' => 'Co-living Space', 'slug' => 'co-living', 'is_active' => 1],
                ['id' => (string) Str::uuid(), 'name' => 'Flat / Apartment', 'slug' => 'flat-apartment', 'is_active' => 1],
                ['id' => (string) Str::uuid(), 'name' => 'Independent House / Villa', 'slug' => 'house-villa', 'is_active' => 1],
                ['id' => (string) Str::uuid(), 'name' => 'Commercial / Office Space', 'slug' => 'commercial', 'is_active' => 1],
            ];
            foreach ($defaultTypes as $item) {
                PropertyType::updateOrCreate(['slug' => $item['slug']], $item);
            }
            $types = PropertyType::where('is_active', 1)->get();
        }

        $amenities = Amenity::where('is_active', 1)->get();
        if ($amenities->isEmpty()) {
            $defaultAmenities = [
                ['id' => (string) Str::uuid(), 'name' => 'High-Speed WiFi', 'slug' => 'wifi', 'icon' => 'wifi', 'category' => 'basic'],
                ['id' => (string) Str::uuid(), 'name' => 'Air Conditioning', 'slug' => 'ac', 'icon' => 'snowflake', 'category' => 'premium'],
                ['id' => (string) Str::uuid(), 'name' => 'Daily Meals / Food', 'slug' => 'food', 'icon' => 'utensils', 'category' => 'basic'],
                ['id' => (string) Str::uuid(), 'name' => 'Laundry & Washing', 'slug' => 'laundry', 'icon' => 'tshirt', 'category' => 'basic'],
                ['id' => (string) Str::uuid(), 'name' => 'Power Backup 24x7', 'slug' => 'power-backup', 'icon' => 'bolt', 'category' => 'basic'],
                ['id' => (string) Str::uuid(), 'name' => 'CCTV & Security', 'slug' => 'cctv', 'icon' => 'shield-alt', 'category' => 'safety'],
                ['id' => (string) Str::uuid(), 'name' => 'Housekeeping', 'slug' => 'housekeeping', 'icon' => 'broom', 'category' => 'basic'],
                ['id' => (string) Str::uuid(), 'name' => 'Attached Bathroom', 'slug' => 'attached-washroom', 'icon' => 'bath', 'category' => 'room'],
                ['id' => (string) Str::uuid(), 'name' => 'Refrigerator', 'slug' => 'refrigerator', 'icon' => 'temperature-low', 'category' => 'appliance'],
                ['id' => (string) Str::uuid(), 'name' => 'RO Drinking Water', 'slug' => 'ro-water', 'icon' => 'tint', 'category' => 'basic'],
            ];
            foreach ($defaultAmenities as $a) {
                Amenity::updateOrCreate(['slug' => $a['slug']], $a);
            }
            $amenities = Amenity::where('is_active', 1)->get();
        }

        $cities = City::where('is_active', 1)->with('areas')->get();

        return $this->success('Listing metadata fetched successfully', [
            'property_types' => $types,
            'amenities' => $amenities,
            'cities' => $cities,
        ]);
    }

    /**
     * Submit Property / PG Listing API (Requires Admin Approval)
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'listing_type' => ['nullable', 'string', 'max:50'],
            'name' => ['required', 'string', 'min:3', 'max:200'],
            'city' => ['required', 'string', 'min:2', 'max:100'],
            'area' => ['nullable', 'string', 'max:150'],
            'address' => ['required', 'string', 'min:5'],
            'landmark' => ['nullable', 'string', 'max:200'],
            'pincode' => ['nullable', 'string', 'regex:/^[1-9][0-9]{5}$/'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'gender_preference' => ['nullable', 'in:boys,girls,co-ed'],
            'monthly_rent' => ['required', 'numeric', 'min:500'],
            'security_deposit' => ['nullable', 'numeric', 'min:0'],
            'maintenance_charges' => ['nullable', 'numeric', 'min:0'],
            'notice_period_days' => ['nullable', 'integer', 'min:0', 'max:365'],
            'total_beds' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'available_beds' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'description' => ['required', 'string', 'min:20', 'max:2000'],
            'house_rules' => ['required', 'string', 'min:5', 'max:2000'],
            'amenities' => ['nullable', 'array'],
            'photos' => ['nullable', 'array'],
            'photos.*' => ['nullable', 'string'],
            'owner_name' => ['required', 'string', 'min:2', 'max:100'],
            'owner_phone' => ['required', 'string', 'min:10', 'max:15'],
            'owner_email' => ['nullable', 'email', 'max:150'],
        ], [
            'name.required' => 'Please enter a property or PG title.',
            'name.min' => 'Property title must be at least 3 characters long.',
            'city.required' => 'Please specify the city.',
            'address.required' => 'Full address is required for tenant navigation.',
            'monthly_rent.required' => 'Please enter the starting monthly rent.',
            'monthly_rent.min' => 'Monthly starting rent cannot be less than ₹500.',
            'security_deposit.min' => 'Security deposit cannot be a negative amount.',
            'maintenance_charges.min' => 'Maintenance charges cannot be negative.',
            'total_beds.min' => 'Total bed capacity must be at least 1.',
            'available_beds.min' => 'Available beds cannot be negative.',
            'description.required' => 'Please provide a detailed property description (at least 20 characters).',
            'description.min' => 'Property description must be at least 20 characters long.',
            'house_rules.required' => 'Please specify house rules / tenant guidelines.',
            'house_rules.min' => 'House rules must be at least 5 characters long.',
            'owner_name.required' => 'Please provide the contact person / owner name.',
            'owner_phone.required' => 'Please provide a valid 10-digit mobile number for verification.',
        ]);

        // =========================================================================
        // AUTOMATED AI CONTENT MODERATION FILTER (ANTI-VULGARITY / DRUGS / SEXUAL / HARASSMENT)
        // =========================================================================
        $moderation = ContentModerationService::validateContent($validated);

        if (!$moderation['passed']) {
            return $this->error(
                $moderation['reason'],
                [
                    'category' => $moderation['category'],
                    'flagged_field' => $moderation['flagged_field'],
                    'flagged_term' => $moderation['flagged_term']
                ],
                422
            );
        }

        // =========================================================================
        // AUTOMATED AI IMAGE MODERATION FILTER (OPENAI OMNIMODELS MODERATION)
        // =========================================================================
        if (!empty($validated['photos']) && is_array($validated['photos'])) {
            $imageModeration = app(ImageModerationService::class)->scanImages($validated['photos']);
            if (!$imageModeration['is_safe']) {
                return $this->error(
                    $imageModeration['reason'],
                    [
                        'category' => 'Image Content Safety Violation',
                        'flagged_index' => $imageModeration['flagged_index']
                    ],
                    422
                );
            }
        }

        try {
            DB::beginTransaction();

            // 1. Identify or Create Owner / Broker User
            $user = $request->user();
            if (!$user) {
                $email = $validated['owner_email'] ?? ('owner_' . time() . '@staynest.com');
                $phone = $validated['owner_phone'] ?? null;

                $user = User::where('email', $email)->orWhere(function ($q) use ($phone) {
                    if ($phone) $q->where('phone', $phone);
                })->first();

                if (!$user) {
                    $user = User::create([
                        'id' => (string) Str::uuid(),
                        'email' => $email,
                        'phone' => $phone,
                        'password_hash' => Hash::make(Str::random(12)),
                        'status' => 'active',
                        'is_active' => 1,
                    ]);

                    $nameParts = explode(' ', $validated['owner_name'] ?? 'Property Owner', 2);
                    UserProfile::create([
                        'id' => (string) Str::uuid(),
                        'user_id' => $user->id,
                        'first_name' => $nameParts[0] ?? 'Property',
                        'last_name' => $nameParts[1] ?? 'Owner',
                    ]);

                    $brokerRole = Role::where('slug', 'broker')->first();
                    if ($brokerRole) {
                        UserRole::create([
                            'id' => (string) Str::uuid(),
                            'user_id' => $user->id,
                            'role_id' => $brokerRole->id,
                            'is_primary' => 1,
                            'is_active' => 1,
                        ]);
                    }
                }
            }

            // 2. Resolve City
            $citySlug = Str::slug($validated['city']);
            $city = City::where('slug', $citySlug)->orWhere('name', 'like', "%{$validated['city']}%")->first();
            if (!$city) {
                $city = City::create([
                    'id' => (string) Str::uuid(),
                    'state_id' => 'c9adb837-8dab-11f1-a4cf-1062e5a5cd6c', // default Karnataka / Delhi
                    'name' => $validated['city'],
                    'slug' => $citySlug,
                    'is_active' => 1,
                ]);
            }

            // 3. Resolve Area
            $areaName = !empty($validated['area']) ? $validated['area'] : $validated['city'];
            $areaSlug = Str::slug($areaName);
            $area = Area::where('city_id', $city->id)->where(function ($q) use ($areaSlug, $areaName) {
                $q->where('slug', $areaSlug)->orWhere('name', 'like', "%{$areaName}%");
            })->first();

            if (!$area) {
                $area = Area::create([
                    'id' => (string) Str::uuid(),
                    'city_id' => $city->id,
                    'name' => $areaName,
                    'slug' => $areaSlug . '-' . substr(Str::uuid(), 0, 4),
                    'pincode' => $validated['pincode'] ?? null,
                    'is_active' => 1,
                ]);
            }

            // 4. Resolve Property Type
            $typeSlug = !empty($validated['listing_type']) ? Str::slug($validated['listing_type']) : 'pg-hostel';
            $propertyType = PropertyType::where('slug', $typeSlug)
                ->orWhere('name', 'like', "%{$typeSlug}%")
                ->first();

            if (!$propertyType) {
                $propertyType = PropertyType::firstOrCreate(
                    ['slug' => $typeSlug],
                    [
                        'id' => (string) Str::uuid(),
                        'name' => ucwords(str_replace('-', ' ', $typeSlug)),
                        'is_active' => 1
                    ]
                );
            }

            // 5. Create Property Record (Pending Admin Approval)
            $property = Property::create([
                'id' => (string) Str::uuid(),
                'broker_id' => $user->id,
                'city_id' => $city->id,
                'area_id' => $area->id,
                'property_type_id' => $propertyType->id,
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']) . '-' . substr((string) Str::uuid(), 0, 6),
                'description' => $validated['description'] ?? null,
                'address' => $validated['address'],
                'landmark' => $validated['landmark'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'gender_preference' => $validated['gender_preference'] ?? 'co-ed',
                'total_beds' => $validated['total_beds'] ?? 10,
                'available_beds' => $validated['available_beds'] ?? ($validated['total_beds'] ?? 10),
                'monthly_rent' => $validated['monthly_rent'],
                'security_deposit' => $validated['security_deposit'] ?? $validated['monthly_rent'],
                'notice_period_days' => $validated['notice_period_days'] ?? 30,
                'verification_status' => 'pending', // PENDING ADMIN APPROVAL
                'status' => 'draft',               // Remains draft until approved
                'featured' => false,
                'is_active' => 1,
                'version' => 1,
                'created_by' => $user->id,
            ]);

            // 6. Attach Amenities
            if (!empty($validated['amenities']) && is_array($validated['amenities'])) {
                $amenityIds = [];
                foreach ($validated['amenities'] as $item) {
                    $amenity = Amenity::where('slug', $item)->orWhere('id', $item)->orWhere('name', $item)->first();
                    if ($amenity) {
                        $amenityIds[] = $amenity->id;
                    }
                }
                if (!empty($amenityIds)) {
                    DB::table('property_amenities')->where('property_id', $property->id)->delete();
                    $pivotRows = [];
                    foreach ($amenityIds as $aId) {
                        $pivotRows[] = [
                            'id' => (string) Str::uuid(),
                            'property_id' => $property->id,
                            'amenity_id' => $aId,
                            'created_at' => now(),
                        ];
                    }
                    DB::table('property_amenities')->insert($pivotRows);
                }
            }

            // 7. Attach Images (Handle Base64 or URLs cleanly)
            $uploadDir = public_path('uploads/properties');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $savedImageCount = 0;
            if (!empty($validated['photos']) && is_array($validated['photos'])) {
                foreach ($validated['photos'] as $idx => $photoData) {
                    if (empty($photoData) || !is_string($photoData)) continue;

                    $photoUrl = trim($photoData);

                    // Check if photo is Base64 data URL
                    if (preg_match('/^data:image\/(\w+);base64,/', $photoUrl, $typeMatches)) {
                        $extension = strtolower($typeMatches[1]);
                        if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                            $extension = 'jpg';
                        }
                        $base64Clean = substr($photoUrl, strpos($photoUrl, ',') + 1);
                        $decodedData = base64_decode($base64Clean);

                        if ($decodedData !== false) {
                            $fileName = 'prop_' . substr(str_replace('-', '', $property->id), 0, 8) . '_' . time() . '_' . $idx . '.' . $extension;
                            $filePath = $uploadDir . '/' . $fileName;
                            file_put_contents($filePath, $decodedData);
                            $photoUrl = '/uploads/properties/' . $fileName;
                        }
                    }

                    PropertyImage::create([
                        'id' => (string) Str::uuid(),
                        'property_id' => $property->id,
                        'image_url' => $photoUrl,
                        'image_type' => $idx === 0 ? 'main' : 'gallery',
                        'sort_order' => $idx,
                        'is_primary' => $idx === 0 ? 1 : 0,
                        'is_active' => 1,
                    ]);
                    $savedImageCount++;
                }
            }

            if ($savedImageCount === 0) {
                // Default fallback image if none uploaded
                PropertyImage::create([
                    'id' => (string) Str::uuid(),
                    'property_id' => $property->id,
                    'image_url' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                    'image_type' => 'main',
                    'sort_order' => 0,
                    'is_primary' => 1,
                    'is_active' => 1,
                ]);
            }

            // 7.5 Attach House Rules
            if (!empty($validated['house_rules'])) {
                $ruleLines = preg_split('/[\r\n]+/', $validated['house_rules']);
                foreach ($ruleLines as $rLine) {
                    $rLine = trim($rLine);
                    if (!empty($rLine)) {
                        PropertyRule::create([
                            'id' => (string) Str::uuid(),
                            'property_id' => $property->id,
                            'rule_text' => $rLine,
                            'rule_type' => 'mandatory',
                            'is_active' => 1,
                            'version' => 1
                        ]);
                    }
                }
            }

            // 8. Create Notification for Admin
            Notification::create([
                'id' => (string) Str::uuid(),
                'user_id' => 'c9b37298-8dab-11f1-a4cf-1062e5a5cd6c', // Admin ID
                'user_type' => 'admin',
                'title' => 'New Listing Submitted for Review',
                'message' => "New property \"{$property->name}\" in {$city->name} ({$propertyType->name}) is awaiting admin approval.",
                'type' => 'property_submission',
                'is_read' => 0,
                'action_url' => '/admin/pgs',
            ]);

            DB::commit();

            $trackingId = 'STAY-' . strtoupper(substr(str_replace('-', '', $property->id), 0, 8));

            return $this->success('Listing submitted successfully for admin approval!', [
                'tracking_id' => $trackingId,
                'property_id' => $property->id,
                'name' => $property->name,
                'slug' => $property->slug,
                'listing_type' => $propertyType->name,
                'verification_status' => 'pending',
                'status' => 'draft',
                'message' => 'Your property listing has been queued for verification. Our review team will review and approve it within 24 hours.',
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Failed to submit listing: ' . $e->getMessage(), 500);
        }
    }
}
