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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'ad_type' => ['nullable', 'string', 'in:rent,sale'],
            'property_category' => ['nullable', 'string', 'in:residential,commercial,land-plot,land_plot'],
            'expected_price' => ['nullable', 'numeric', 'min:1000', 'max:1000000000'],
            'booking_token_amount' => ['nullable', 'numeric', 'min:0', 'max:100000000'],
            'price_negotiable' => ['nullable', 'boolean'],
            'ownership_type' => ['nullable', 'string', 'max:50'],
            'possession_status' => ['nullable', 'string', 'max:50'],
            'carpet_area_sqft' => ['nullable', 'integer', 'min:1', 'max:500000'],
            'bhk_type' => ['nullable', 'string', 'max:50'],
            'furnishing_status' => ['nullable', 'string', 'max:50'],
            'name' => ['required', 'string', 'min:3', 'max:200'],
            'city' => ['required', 'string', 'min:2', 'max:100'],
            'area' => ['nullable', 'string', 'max:150'],
            'address' => ['required', 'string', 'min:5'],
            'landmark' => ['nullable', 'string', 'max:200'],
            'pincode' => ['nullable', 'string', 'regex:/^[1-9][0-9]{5}$/'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'gender_preference' => ['nullable', 'string', 'in:boys,girls,co-ed,all,any,not_applicable'],
            'monthly_rent' => ['nullable', 'numeric', 'min:100', 'max:1000000000'],
            'security_deposit' => ['nullable', 'numeric', 'min:0', 'max:200000000'],
            'maintenance_charges' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'notice_period_days' => ['nullable', 'integer', 'min:0', 'max:365'],
            'total_beds' => ['nullable', 'integer', 'min:1', 'max:5000'],
            'available_beds' => ['nullable', 'integer', 'min:0', 'max:5000'],
            'description' => ['required', 'string', 'min:20', 'max:2000'],
            'house_rules' => ['required', 'string', 'min:5', 'max:2000'],
            'amenities' => ['nullable', 'array'],
            'room_sharing' => ['nullable', 'array'],
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
            'monthly_rent.min' => 'Monthly starting rent cannot be less than ₹100.',
            'description.required' => 'Please provide a detailed property description (at least 20 characters).',
            'description.min' => 'Property description must be at least 20 characters long.',
            'house_rules.required' => 'Please specify house rules / guidelines.',
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

        try {
            DB::beginTransaction();

            // 1. Identify or Create Owner / Broker User
            $authUser = $request->user() ?? Auth::user();
            $isAdmin = $authUser && ($authUser->roles()->whereIn('slug', ['super_admin', 'admin'])->exists() || ($authUser->role ?? '') === 'admin');

            // If guest OR if logged in as Admin submitting on behalf of a landlord/owner:
            if (!$authUser || $isAdmin) {
                $rawPhone = !empty($validated['owner_phone']) ? preg_replace('/\D/', '', $validated['owner_phone']) : null;
                $phone = $rawPhone ? (strlen($rawPhone) >= 10 ? substr($rawPhone, -10) : $rawPhone) : null;
                $email = !empty($validated['owner_email']) ? trim($validated['owner_email']) : null;

                $ownerUser = null;
                if ($phone || $email) {
                    $ownerUser = User::where(function ($q) use ($phone, $email) {
                        if ($phone) {
                            $q->where('phone', $phone)->orWhere('phone', 'like', "%{$phone}");
                        }
                        if ($email) {
                            $q->orWhere('email', $email);
                        }
                    })->first();
                }

                if (!$ownerUser && ($phone || $email || !empty($validated['owner_name']))) {
                    $generatedEmail = $email ?: ('owner_' . time() . '_' . Str::random(4) . '@staynest.com');
                    $ownerUser = User::create([
                        'id' => (string) Str::uuid(),
                        'email' => $generatedEmail,
                        'phone' => $phone,
                        'password_hash' => Hash::make(Str::random(12)),
                        'status' => 'active',
                        'is_active' => 1,
                    ]);

                    $nameParts = explode(' ', $validated['owner_name'] ?? 'Property Owner', 2);
                    UserProfile::create([
                        'id' => (string) Str::uuid(),
                        'user_id' => $ownerUser->id,
                        'first_name' => $nameParts[0] ?? 'Property',
                        'last_name' => $nameParts[1] ?? 'Owner',
                    ]);

                    $brokerRole = Role::where('slug', 'broker')->first();
                    if ($brokerRole) {
                        UserRole::firstOrCreate([
                            'user_id' => $ownerUser->id,
                            'role_id' => $brokerRole->id,
                        ], [
                            'id' => (string) Str::uuid(),
                            'is_primary' => 1,
                            'is_active' => 1,
                        ]);
                    }
                }

                $assignedBrokerId = $ownerUser ? $ownerUser->id : ($authUser ? $authUser->id : null);
            } else {
                $assignedBrokerId = $authUser->id;
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
            $propertyType = $this->resolvePropertyType($validated['listing_type'] ?? null);

            // 5. Create Property Record (Pending Admin Approval)
            $ptSlug = strtolower($propertyType->slug ?? '');
            $finalGender = $validated['gender_preference'] ?? null;
            if (in_array($ptSlug, ['commercial', 'shop', 'office', 'retail', 'commercial-space'])) {
                $finalGender = null;
            } elseif (in_array($ptSlug, ['flat', 'flat-apartment', 'house', 'apartment', 'villa'])) {
                $finalGender = $finalGender ?: 'all';
            } else {
                $finalGender = $finalGender ?: 'co-ed';
            }

            $isSale = ($validated['ad_type'] ?? 'rent') === 'sale';
            $expectedPrice = !empty($validated['expected_price']) ? $validated['expected_price'] : ($isSale ? ($validated['monthly_rent'] ?? null) : null);
            $rentPrice = !empty($validated['monthly_rent']) ? $validated['monthly_rent'] : ($expectedPrice ?: 5000);

            $property = Property::create([
                'id' => (string) Str::uuid(),
                'broker_id' => $assignedBrokerId,
                'city_id' => $city->id,
                'area_id' => $area->id,
                'property_type_id' => $propertyType->id,
                'ad_type' => $validated['ad_type'] ?? 'rent',
                'property_category' => $validated['property_category'] ?? 'residential',
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']) . '-' . substr((string) Str::uuid(), 0, 6),
                'description' => $validated['description'] ?? null,
                'address' => $validated['address'],
                'landmark' => $validated['landmark'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'gender_preference' => $finalGender,
                'total_beds' => $validated['total_beds'] ?? 10,
                'available_beds' => $validated['available_beds'] ?? ($validated['total_beds'] ?? 10),
                'monthly_rent' => $rentPrice,
                'expected_price' => $expectedPrice,
                'booking_token_amount' => $validated['booking_token_amount'] ?? null,
                'price_negotiable' => !empty($validated['price_negotiable']),
                'ownership_type' => $validated['ownership_type'] ?? null,
                'possession_status' => $validated['possession_status'] ?? null,
                'carpet_area_sqft' => $validated['carpet_area_sqft'] ?? null,
                'bhk_type' => $validated['bhk_type'] ?? null,
                'furnishing_status' => $validated['furnishing_status'] ?? null,
                'security_deposit' => $validated['security_deposit'] ?? ($isSale ? 0 : $rentPrice),
                'maintenance_charges' => $validated['maintenance_charges'] ?? 0,
                'notice_period_days' => $validated['notice_period_days'] ?? 30,
                'verification_status' => 'pending', // PENDING ADMIN APPROVAL
                'status' => 'draft',               // Remains draft until approved
                'featured' => false,
                'is_active' => 1,
                'version' => 1,
                'created_by' => $authUser ? $authUser->id : $assignedBrokerId,
            ]);

            // 6. Attach Amenities
            if (!empty($validated['amenities']) && is_array($validated['amenities'])) {
                $amenityIds = [];
                foreach ($validated['amenities'] as $item) {
                    $slugOrName = str_replace('_', '-', strtolower(trim($item)));
                    $amenity = Amenity::where('slug', $slugOrName)->orWhere('slug', $item)->first();
                    if (!$amenity && Str::isUuid($item)) {
                        $amenity = Amenity::where('id', $item)->first();
                    }
                    if (!$amenity) {
                        $amenity = Amenity::whereRaw('LOWER(name) = ?', [strtolower(trim($item))])->first();
                    }
                    if (!$amenity) {
                        if ($slugOrName === 'fridge' || $slugOrName === 'refrigerator') {
                            $amenity = Amenity::whereIn('slug', ['refrigerator', 'fridge'])->first();
                        } elseif ($slugOrName === 'parking') {
                            $amenity = Amenity::where('slug', 'parking')->first();
                        } elseif ($slugOrName === 'ac' || $slugOrName === 'air-conditioner' || $slugOrName === 'air-conditioning') {
                            $amenity = Amenity::where('slug', 'ac')->first();
                        }
                    }

                    if ($amenity) {
                        $amenityIds[] = $amenity->id;
                    }
                }
                if (!empty($amenityIds)) {
                    DB::table('property_amenities')->where('property_id', $property->id)->delete();
                    $pivotRows = [];
                    foreach (array_unique($amenityIds) as $aId) {
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

            // 7.8 Sync PG Room Sharing Types & Configurations
            $this->syncPropertyRooms($property, $validated['room_sharing'] ?? []);

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

    /**
     * Get single property details for edit mode in list-property wizard.
     */
    public function details($id)
    {
        $property = Property::with([
            'city',
            'area',
            'propertyType',
            'images',
            'amenities',
            'rules',
            'broker.profile',
        ])->where('id', $id)->orWhere('slug', $id)->firstOrFail();

        // Format amenities slugs
        $amenitySlugs = $property->amenities->pluck('slug')->toArray();

        // Format photos list
        $photos = $property->images->sortBy('sort_order')->pluck('image_url')->toArray();
        if (empty($photos) && $property->primaryImage) {
            $photos[] = $property->primaryImage->image_url;
        }

        // Format house rules
        $houseRules = $property->rules->pluck('rule_text')->implode("\n");

        $data = [
            'id' => $property->id,
            'name' => $property->name,
            'listing_type' => $property->propertyType?->slug ?? 'pg-hostel',
            'property_category' => $property->property_category ?? 'residential',
            'ad_type' => $property->ad_type ?? ($property->is_sale ? 'sale' : 'rent'),
            'is_sale' => (bool) $property->is_sale,
            'expected_price' => $property->expected_price ? (float) $property->expected_price : null,
            'booking_token_amount' => $property->booking_token_amount ? (float) $property->booking_token_amount : null,
            'price_negotiable' => (bool) $property->price_negotiable,
            'ownership_type' => $property->ownership_type ?? 'Freehold',
            'possession_status' => $property->possession_status ?? 'Ready to Move',
            'carpet_area_sqft' => $property->carpet_area_sqft ? (int) $property->carpet_area_sqft : null,
            'bhk_type' => $property->bhk_type,
            'furnishing_status' => $property->furnishing_status,
            'commercial_space_type' => $property->commercial_space_type,
            'city' => $property->city?->name ?? '',
            'city_id' => $property->city_id,
            'area' => $property->area?->name ?? $property->landmark ?? '',
            'area_id' => $property->area_id,
            'address' => $property->address ?? '',
            'landmark' => $property->landmark ?? '',
            'pincode' => $property->area?->pincode ?? '',
            'latitude' => $property->latitude,
            'longitude' => $property->longitude,
            'gender_preference' => $property->gender_preference ?? 'co-ed',
            'monthly_rent' => (float) $property->monthly_rent,
            'security_deposit' => (float) ($property->security_deposit ?? $property->monthly_rent),
            'maintenance_charges' => (float) ($property->maintenance_charges ?? 0),
            'notice_period_days' => (int) ($property->notice_period_days ?? 30),
            'total_beds' => (int) ($property->total_beds ?? 10),
            'available_beds' => (int) ($property->available_beds ?? 0),
            'description' => $property->description ?? '',
            'house_rules' => $houseRules,
            'owner_name' => $property->broker?->profile?->first_name 
                ? ($property->broker->profile->first_name . ' ' . ($property->broker->profile->last_name ?? ''))
                : ($property->broker?->name ?? 'Property Manager'),
            'owner_phone' => $property->broker?->phone ?? '',
            'owner_email' => $property->broker?->email ?? '',
            'amenities' => $amenitySlugs,
            'room_sharing' => $property->room_configurations->map(function ($rc) {
                $isBooked = ($rc->room_status === 'occupied' || $rc->room_status === 'booked' || $rc->room_status === 'sold_out' || (int)$rc->available_beds === 0);
                return [
                    'type' => $rc->room_type_slug,
                    'name' => $rc->room_type_name,
                    'rent' => (float) $rc->monthly_rent,
                    'is_available' => !$isBooked,
                    'status' => $isBooked ? 'booked' : 'available',
                    'selected' => true,
                ];
            })->values()->toArray(),
            'photos' => $photos,
            'tag' => $property->tag,
            'tag_meta' => $property->tag_meta,
            'status' => $property->status ?? 'active',
            'is_active' => (bool) $property->is_active,
        ];

        return $this->success('Property details retrieved successfully', $data);
    }

    /**
     * Update existing property from list-property wizard.
     */
    public function update(Request $request, $id)
    {
        $property = Property::where('id', $id)->orWhere('slug', $id)->firstOrFail();
        $user = $request->user() ?? Auth::user();
        $isAdmin = $user && ($user->roles()->whereIn('slug', ['super_admin', 'admin'])->exists() || ($user->role ?? '') === 'admin');

        // Convert empty string owner_email to null so email validator doesn't fail on empty string
        if ($request->has('owner_email') && trim((string) $request->input('owner_email')) === '') {
            $request->merge(['owner_email' => null]);
        }

        $validated = $request->validate([
            'listing_type' => ['nullable', 'string', 'max:50'],
            'ad_type' => ['nullable', 'string', 'in:rent,sale'],
            'property_category' => ['nullable', 'string', 'in:residential,commercial,land-plot,land_plot'],
            'expected_price' => ['nullable', 'numeric', 'min:1000', 'max:1000000000'],
            'booking_token_amount' => ['nullable', 'numeric', 'min:0', 'max:100000000'],
            'price_negotiable' => ['nullable', 'boolean'],
            'ownership_type' => ['nullable', 'string', 'max:50'],
            'possession_status' => ['nullable', 'string', 'max:50'],
            'carpet_area_sqft' => ['nullable', 'integer', 'min:1', 'max:500000'],
            'bhk_type' => ['nullable', 'string', 'max:50'],
            'furnishing_status' => ['nullable', 'string', 'max:50'],
            'name' => ['required', 'string', 'min:2', 'max:200'],
            'city' => ['required', 'string', 'min:2', 'max:100'],
            'area' => ['nullable', 'string', 'max:150'],
            'address' => ['required', 'string', 'min:3'],
            'landmark' => ['nullable', 'string', 'max:200'],
            'pincode' => ['nullable', 'string'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'gender_preference' => ['nullable', 'string', 'in:boys,girls,co-ed,all,any,not_applicable'],
            'monthly_rent' => ['nullable', 'numeric', 'min:100', 'max:1000000000'],
            'security_deposit' => ['nullable', 'numeric', 'min:0', 'max:200000000'],
            'maintenance_charges' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'notice_period_days' => ['nullable', 'integer', 'min:0', 'max:365'],
            'total_beds' => ['nullable', 'integer', 'min:1', 'max:5000'],
            'available_beds' => ['nullable', 'integer', 'min:0', 'max:5000'],
            'description' => ['nullable', 'string', 'max:2000'],
            'house_rules' => ['nullable', 'string', 'max:2000'],
            'amenities' => ['nullable', 'array'],
            'room_sharing' => ['nullable', 'array'],
            'photos' => ['nullable', 'array'],
            'owner_name' => ['nullable', 'string', 'max:100'],
            'owner_phone' => ['nullable', 'string', 'max:15'],
            'owner_email' => ['nullable', 'email', 'max:150'],
            'status' => ['nullable', 'in:active,draft,inactive'],
        ], [
            'monthly_rent.min' => 'Monthly starting rent cannot be less than ₹100.',
            'total_beds.max' => 'Total bed capacity cannot exceed 5,000.',
            'available_beds.max' => 'Available beds cannot exceed 5,000.',
        ]);

        DB::beginTransaction();
        try {
            // Resolve City
            $cityName = !empty($validated['city']) ? trim($validated['city']) : 'City';
            $citySlug = Str::slug($cityName);
            $city = City::where('slug', $citySlug)->orWhere('name', 'like', "%{$cityName}%")->first();
            if (!$city) {
                $defaultStateId = DB::table('states')->value('id') ?? 'c9adb837-8dab-11f1-a4cf-1062e5a5cd6c';
                $city = City::create([
                    'id' => (string) Str::uuid(),
                    'state_id' => $defaultStateId,
                    'name' => $cityName,
                    'slug' => $citySlug,
                    'is_active' => 1,
                    'is_metro' => 0,
                    'is_tier1' => 0,
                    'version' => 1
                ]);
            }

            // Resolve Area
            $areaId = $property->area_id;
            if (!empty($validated['area'])) {
                $area = Area::firstOrCreate(
                    ['city_id' => $city->id, 'name' => trim($validated['area'])],
                    [
                        'id' => (string) Str::uuid(),
                        'slug' => Str::slug(trim($validated['area'])) . '-' . Str::random(4),
                        'pincode' => $validated['pincode'] ?? null,
                        'is_active' => 1,
                        'version' => 1
                    ]
                );
                if (!empty($validated['pincode']) && empty($area->pincode)) {
                    $area->pincode = $validated['pincode'];
                    $area->save();
                }
                $areaId = $area->id;
            }

            // Resolve Type & Gender Preference
            $propertyType = $this->resolvePropertyType($validated['listing_type'] ?? null);
            $ptSlug = strtolower($propertyType->slug ?? '');
            $finalGender = $validated['gender_preference'] ?? null;
            if (in_array($ptSlug, ['commercial', 'shop', 'office', 'retail', 'commercial-space'])) {
                $finalGender = null;
            } elseif (in_array($ptSlug, ['flat', 'flat-apartment', 'house', 'apartment', 'villa'])) {
                $finalGender = $finalGender ?: 'all';
            } else {
                $finalGender = $finalGender ?: ($property->gender_preference ?: 'co-ed');
            }

            $isSale = ($validated['ad_type'] ?? ($property->ad_type ?: 'rent')) === 'sale';
            $expectedPrice = !empty($validated['expected_price']) ? $validated['expected_price'] : ($isSale ? ($validated['monthly_rent'] ?? $property->expected_price) : null);
            $rentPrice = !empty($validated['monthly_rent']) ? $validated['monthly_rent'] : ($expectedPrice ?: $property->monthly_rent);

            $property->property_type_id = $propertyType->id;
            if (isset($validated['ad_type'])) $property->ad_type = $validated['ad_type'];
            if (isset($validated['property_category'])) $property->property_category = $validated['property_category'];
            if ($expectedPrice !== null) $property->expected_price = $expectedPrice;
            if (isset($validated['booking_token_amount'])) $property->booking_token_amount = $validated['booking_token_amount'];
            if (isset($validated['price_negotiable'])) $property->price_negotiable = !empty($validated['price_negotiable']);
            if (isset($validated['ownership_type'])) $property->ownership_type = $validated['ownership_type'];
            if (isset($validated['possession_status'])) $property->possession_status = $validated['possession_status'];
            if (isset($validated['carpet_area_sqft'])) $property->carpet_area_sqft = $validated['carpet_area_sqft'];
            if (isset($validated['bhk_type'])) $property->bhk_type = $validated['bhk_type'];
            if (isset($validated['furnishing_status'])) $property->furnishing_status = $validated['furnishing_status'];

            $property->address = $validated['address'];
            $property->landmark = $validated['landmark'] ?? $property->landmark;
            $property->gender_preference = $finalGender;
            $property->monthly_rent = $rentPrice;
            $property->security_deposit = $validated['security_deposit'] ?? $property->security_deposit;
            $property->maintenance_charges = $validated['maintenance_charges'] ?? $property->maintenance_charges;
            $property->notice_period_days = $validated['notice_period_days'] ?? $property->notice_period_days;
            $property->total_beds = $validated['total_beds'] ?? $property->total_beds;
            $property->available_beds = $validated['available_beds'] ?? $property->available_beds;
            
            $descriptionText = !empty($validated['description']) ? trim($validated['description']) : ($property->description ?: "Premium accommodation in {$city->name} with modern amenities.");
            $property->description = $descriptionText;

            if (!empty($validated['latitude'])) $property->latitude = $validated['latitude'];
            if (!empty($validated['longitude'])) $property->longitude = $validated['longitude'];

            if (!empty($validated['status'])) {
                $property->status = $validated['status'];
                $property->is_active = ($validated['status'] === 'active' ? 1 : 0);
            }

            $property->save();

            // Sync Amenities
            if (isset($validated['amenities']) && is_array($validated['amenities'])) {
                $amenityIds = [];
                foreach ($validated['amenities'] as $item) {
                    $slugOrName = str_replace('_', '-', strtolower(trim($item)));
                    $amenity = Amenity::where('slug', $slugOrName)->orWhere('slug', $item)->first();
                    if (!$amenity && Str::isUuid($item)) {
                        $amenity = Amenity::where('id', $item)->first();
                    }
                    if (!$amenity) {
                        $amenity = Amenity::whereRaw('LOWER(name) = ?', [strtolower(trim($item))])->first();
                    }
                    if (!$amenity) {
                        if ($slugOrName === 'fridge' || $slugOrName === 'refrigerator') {
                            $amenity = Amenity::whereIn('slug', ['refrigerator', 'fridge'])->first();
                        } elseif ($slugOrName === 'parking') {
                            $amenity = Amenity::where('slug', 'parking')->first();
                        } elseif ($slugOrName === 'ac' || $slugOrName === 'air-conditioner' || $slugOrName === 'air-conditioning') {
                            $amenity = Amenity::where('slug', 'ac')->first();
                        }
                    }

                    if ($amenity) {
                        $amenityIds[] = $amenity->id;
                    }
                }
                DB::table('property_amenities')->where('property_id', $property->id)->delete();
                $pivotRows = [];
                foreach (array_unique($amenityIds) as $aId) {
                    $pivotRows[] = [
                        'id' => (string) Str::uuid(),
                        'property_id' => $property->id,
                        'amenity_id' => $aId,
                        'created_at' => now(),
                    ];
                }
                if (!empty($pivotRows)) {
                    DB::table('property_amenities')->insert($pivotRows);
                }
            }

            // Sync Photos with Base64 handler
            $uploadDir = public_path('uploads/properties');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (!empty($validated['photos']) && is_array($validated['photos'])) {
                PropertyImage::where('property_id', $property->id)->delete();
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
                }
            }

            // Sync Rules if provided
            if (isset($validated['house_rules'])) {
                PropertyRule::where('property_id', $property->id)->delete();
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

            // Update Owner/Broker contact if provided
            $rawPhone = !empty($validated['owner_phone']) ? preg_replace('/\D/', '', $validated['owner_phone']) : null;
            $ownerPhone = $rawPhone ? (strlen($rawPhone) >= 10 ? substr($rawPhone, -10) : $rawPhone) : null;
            $ownerEmail = !empty($validated['owner_email']) ? trim($validated['owner_email']) : null;
            $ownerName = !empty($validated['owner_name']) ? trim($validated['owner_name']) : null;

            if ($ownerPhone || $ownerEmail || $ownerName) {
                // If Admin is updating someone else's property, NEVER change ownership to Admin!
                if ($isAdmin) {
                    if ($property->broker) {
                        $brokerIsAdmin = $property->broker->roles()->whereIn('slug', ['super_admin', 'admin'])->exists() || ($property->broker->role ?? '') === 'admin';
                        if (!$brokerIsAdmin) {
                            if ($ownerPhone) {
                                $phoneExists = User::where('id', '!=', $property->broker->id)
                                    ->where(fn($q) => $q->where('phone', $ownerPhone)->orWhere('phone', 'like', "%{$ownerPhone}"))
                                    ->exists();
                                if (!$phoneExists) {
                                    $property->broker->phone = $ownerPhone;
                                }
                            }
                            if ($ownerEmail) {
                                $emailExists = User::where('id', '!=', $property->broker->id)
                                    ->where('email', $ownerEmail)
                                    ->exists();
                                if (!$emailExists) {
                                    $property->broker->email = $ownerEmail;
                                }
                            }
                            $property->broker->save();

                            if ($ownerName && $property->broker->profile) {
                                $nameParts = explode(' ', $ownerName, 2);
                                $property->broker->profile->first_name = $nameParts[0] ?? 'Property';
                                $property->broker->profile->last_name = $nameParts[1] ?? 'Manager';
                                $property->broker->profile->save();
                            }
                        }
                    }
                } else {
                    // Regular broker/owner updating their own property
                    if ($user && $property->broker && $property->broker_id === $user->id) {
                        if ($ownerName && $user->profile) {
                            $nameParts = explode(' ', $ownerName, 2);
                            $user->profile->first_name = $nameParts[0] ?? 'Property';
                            $user->profile->last_name = $nameParts[1] ?? 'Manager';
                            $user->profile->save();
                        }
                    }
                }
            }

            // Sync Room Sharing configurations if provided
            if (array_key_exists('room_sharing', $validated)) {
                $this->syncPropertyRooms($property, $validated['room_sharing'] ?? []);
            }

            DB::commit();

            return $this->success("Property \"{$property->name}\" updated successfully!", [
                'property_id' => $property->id,
                'name' => $property->name,
                'status' => $property->status,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error('Failed to update property: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Synchronize PG Room Sharing Types & Capacity for a Property.
     */
    private function syncPropertyRooms(Property $property, array $roomSharing): void
    {
        // 1. Ensure Block & Floor exist for property
        $block = DB::table('blocks')->where('property_id', $property->id)->first();
        if (!$block) {
            $blockId = (string) Str::uuid();
            DB::table('blocks')->insert([
                'id' => $blockId,
                'property_id' => $property->id,
                'name' => 'Main Block',
                'is_active' => 1,
                'version' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $blockId = $block->id;
        }

        $floor = DB::table('floors')->where('block_id', $blockId)->first();
        if (!$floor) {
            $floorId = (string) Str::uuid();
            DB::table('floors')->insert([
                'id' => $floorId,
                'block_id' => $blockId,
                'floor_number' => 1,
                'name' => '1st Floor',
                'is_active' => 1,
                'version' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $floorId = $floor->id;
        }

        // Delete existing rooms on this floor to strictly mirror what owner selected
        DB::table('rooms')->where('floor_id', $floorId)->delete();

        if (empty($roomSharing)) {
            return;
        }

        foreach ($roomSharing as $idx => $item) {
            $slug = strtolower(trim($item['type'] ?? 'double'));
            if (empty($slug)) continue;

            $rt = DB::table('room_types')->where('slug', $slug)->first();
            if (!$rt) {
                $occupancy = $slug === 'single' ? 1 : ($slug === 'double' ? 2 : ($slug === 'triple' ? 3 : ($slug === 'four' ? 4 : 2)));
                $rtId = (string) Str::uuid();
                DB::table('room_types')->insert([
                    'id' => $rtId,
                    'name' => ucfirst($slug) . ($slug === 'single' ? ' Occupancy' : ' Sharing'),
                    'slug' => $slug,
                    'max_occupancy' => $occupancy,
                    'is_active' => 1,
                    'version' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $rt = DB::table('room_types')->where('id', $rtId)->first();
            }

            $roomRent = max(500, (float)($item['rent'] ?? $property->monthly_rent));
            
            $isAvailable = true;
            if (isset($item['is_available'])) {
                $isAvailable = filter_var($item['is_available'], FILTER_VALIDATE_BOOLEAN);
            } elseif (isset($item['status'])) {
                $isAvailable = !in_array(strtolower(trim($item['status'])), ['booked', 'occupied', 'sold_out', 'full']);
            }

            $roomStatus = $isAvailable ? 'available' : 'occupied';
            $availBeds = $isAvailable ? max(1, (int)($item['available_beds'] ?? max(1, $rt->max_occupancy - 1))) : 0;

            DB::table('rooms')->insert([
                'id' => (string) Str::uuid(),
                'floor_id' => $floorId,
                'room_type_id' => $rt->id,
                'room_number' => strtoupper(substr($slug, 0, 1)) . '-' . (101 + $idx),
                'total_beds' => $rt->max_occupancy,
                'available_beds' => $availBeds,
                'monthly_rent' => $roomRent,
                'security_deposit' => $property->security_deposit ?? ($roomRent * 2),
                'attached_bathroom' => 1,
                'ac_available' => 1,
                'balcony' => 1,
                'status' => $roomStatus,
                'is_active' => 1,
                'version' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Resolve or provision a PropertyType record based on listing type slug or name.
     */
    protected function resolvePropertyType(?string $rawType): PropertyType
    {
        $typeSlug = !empty($rawType) ? Str::slug($rawType) : 'pg-hostel';
        
        if (in_array($typeSlug, ['flat-apartment', 'flat', 'apartment', 'house', 'flat-house'])) {
            $type = PropertyType::whereIn('slug', ['flat-apartment', 'flat', 'apartment', 'house', 'flat-house'])->first();
            if (!$type) {
                $type = PropertyType::create([
                    'id' => (string) Str::uuid(),
                    'name' => 'Flat / Apartment',
                    'slug' => 'flat-apartment',
                    'is_active' => 1
                ]);
            }
            return $type;
        }

        if (in_array($typeSlug, ['pg-hostel', 'pg', 'hostel', 'co-living'])) {
            $type = PropertyType::whereIn('slug', ['pg-hostel', 'pg', 'hostel', 'co-living'])->first();
            if (!$type) {
                $type = PropertyType::create([
                    'id' => (string) Str::uuid(),
                    'name' => 'PG / Hostel',
                    'slug' => 'pg-hostel',
                    'is_active' => 1
                ]);
            }
            return $type;
        }

        if (in_array($typeSlug, ['commercial', 'commercial-property', 'shop-office', 'shop', 'office'])) {
            $type = PropertyType::whereIn('slug', ['commercial', 'commercial-property', 'shop-office', 'shop', 'office'])->first();
            if (!$type) {
                $type = PropertyType::create([
                    'id' => (string) Str::uuid(),
                    'name' => 'Commercial',
                    'slug' => 'commercial',
                    'is_active' => 1
                ]);
            }
            return $type;
        }

        $type = PropertyType::where('slug', $typeSlug)
            ->orWhere('name', 'like', "%{$typeSlug}%")
            ->first();

        if (!$type) {
            $type = PropertyType::create([
                'id' => (string) Str::uuid(),
                'name' => ucwords(str_replace('-', ' ', $typeSlug)),
                'slug' => $typeSlug,
                'is_active' => 1
            ]);
        }

        return $type;
    }
}
