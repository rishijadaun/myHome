<?php

namespace Database\Seeders;

use App\Models\Amenity;
use App\Models\Area;
use App\Models\City;
use App\Models\LoginHistory;
use App\Models\Notification;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\PropertyRule;
use App\Models\PropertyType;
use App\Models\Review;
use App\Models\Role;
use App\Models\RoommatePost;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserRole;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FreshDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        echo "1. Cleaning existing data...\n";
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        // Clean user and auth data
        DB::table('login_history')->truncate();
        DB::table('notifications')->truncate();
        DB::table('wallets')->truncate();
        DB::table('wallet_transactions')->truncate();
        DB::table('user_roles')->truncate();
        DB::table('user_profiles')->truncate();
        DB::table('users')->truncate();

        // Clean property data
        DB::table('reviews')->truncate();
        DB::table('bookings')->truncate();
        DB::table('booking_history')->truncate();
        DB::table('invoices')->truncate();
        DB::table('payments')->truncate();
        DB::table('property_amenities')->truncate();
        DB::table('property_images')->truncate();
        DB::table('property_rules')->truncate();
        DB::table('property_reports')->truncate();
        DB::table('property_visits')->truncate();
        DB::table('properties')->truncate();

        // Clean roommate posts
        DB::table('roommate_messages')->truncate();
        DB::table('roommate_posts')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
        echo "Cleaned all old records.\n";

        // 2. Ensure Core Roles Exist
        echo "2. Setting up roles & permissions...\n";
        $tenantRole = Role::firstOrCreate(
            ['slug' => 'tenant'],
            ['id' => (string) Str::uuid(), 'name' => 'Tenant', 'level' => 10, 'is_system' => 1, 'is_active' => 1]
        );
        $brokerRole = Role::firstOrCreate(
            ['slug' => 'broker'],
            ['id' => (string) Str::uuid(), 'name' => 'Broker / Landlord', 'level' => 30, 'is_system' => 1, 'is_active' => 1]
        );
        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            ['id' => (string) Str::uuid(), 'name' => 'Administrator', 'level' => 100, 'is_system' => 1, 'is_active' => 1]
        );

        // 3. Create Required Users
        echo "3. Creating Users...\n";
        
        // (A) Tenant User
        $tenantId = (string) Str::uuid();
        $tenant = User::create([
            'id' => $tenantId,
            'email' => 'tenant@staynest.com',
            'phone' => '+919876543210',
            'password_hash' => Hash::make('User@123'),
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'status' => 'active',
            'is_active' => 1,
            'version' => 1,
        ]);
        UserProfile::create([
            'user_id' => $tenantId,
            'first_name' => 'Aman',
            'last_name' => 'Verma',
            'avatar_url' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=200&auto=format&fit=crop&q=80',
            'bio' => 'Software Engineer working in Tech. Looking for peaceful verified stays.',
            'preferences' => json_encode(['food_preference' => 'Vegetarian', 'stay_type' => 'Private Room']),
            'is_active' => 1,
        ]);
        UserRole::create([
            'id' => (string) Str::uuid(),
            'user_id' => $tenantId,
            'role_id' => $tenantRole->id,
            'is_primary' => 1,
            'is_active' => 1,
        ]);
        Wallet::create([
            'id' => (string) Str::uuid(),
            'user_id' => $tenantId,
            'balance' => 2500.00,
            'currency_code' => 'INR',
            'is_active' => 1,
        ]);

        // (B) Broker / PG Owner User
        $brokerId = (string) Str::uuid();
        $broker = User::create([
            'id' => $brokerId,
            'email' => 'owner@staynest.com',
            'phone' => '+919876543211',
            'password_hash' => Hash::make('Owner@123'),
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'kyc_verified_at' => now(),
            'status' => 'active',
            'is_active' => 1,
            'version' => 1,
        ]);
        UserProfile::create([
            'user_id' => $brokerId,
            'first_name' => 'Rajesh',
            'last_name' => 'Sharma',
            'avatar_url' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=200&auto=format&fit=crop&q=80',
            'company_name' => 'StayNest Premium Stays & Co-Living',
            'bio' => 'Experienced PG & Co-living space partner managing premium student and executive stays across India.',
            'preferences' => json_encode([
                'operating_city' => 'Delhi NCR / Bangalore',
                'office_address' => 'Tower B, 4th Floor, Sector 62, Noida, UP',
                'gstin' => '09AAAAA0000A1Z5',
                'rera_number' => 'UPRERAAGT12490'
            ]),
            'is_active' => 1,
        ]);
        UserRole::create([
            'id' => (string) Str::uuid(),
            'user_id' => $brokerId,
            'role_id' => $brokerRole->id,
            'is_primary' => 1,
            'is_active' => 1,
        ]);
        Wallet::create([
            'id' => (string) Str::uuid(),
            'user_id' => $brokerId,
            'balance' => 15000.00,
            'currency_code' => 'INR',
            'is_active' => 1,
        ]);

        // (C) Commercial Property Owner / Corporate Partner
        $commercialBrokerId = (string) Str::uuid();
        $commercialBroker = User::create([
            'id' => $commercialBrokerId,
            'email' => 'commercial.owner@staynest.com',
            'phone' => '+919876543213',
            'password_hash' => Hash::make('Owner@123'),
            'email_verified_at' => now(),
            'status' => 'active',
            'is_active' => 1,
            'version' => 1,
        ]);
        UserProfile::create([
            'user_id' => $commercialBrokerId,
            'first_name' => 'Vikram',
            'last_name' => 'Singhania',
            'avatar_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&auto=format&fit=crop&q=80',
            'company_name' => 'Singhania Commercial Realty & Workspace Partners',
            'bio' => 'Leading corporate commercial leasing and business tech park partner managing Grade-A offices, retail spaces, and co-working hubs across top metro cities.',
            'preferences' => json_encode([
                'operating_city' => 'Noida / Delhi NCR / Bangalore / Mumbai',
                'office_address' => 'Tower 4, 12th Floor, Cyber Park, Sector 62, Noida, UP 201309',
                'gstin' => '07AAAAA1234A1Z5',
                'rera_number' => 'UPRERAPRM88992',
                'operating_area' => 'Sector 62, Cyber City, BKC, Koramangala'
            ]),
            'is_active' => 1,
        ]);
        UserRole::create([
            'id' => (string) Str::uuid(),
            'user_id' => $commercialBrokerId,
            'role_id' => $brokerRole->id,
            'is_primary' => 1,
            'is_active' => 1,
        ]);
        Wallet::create([
            'id' => (string) Str::uuid(),
            'user_id' => $commercialBrokerId,
            'balance' => 50000.00,
            'currency_code' => 'INR',
            'is_active' => 1,
        ]);

        // (D) Admin User
        $adminId = (string) Str::uuid();
        $admin = User::create([
            'id' => $adminId,
            'email' => 'admin@staynest.com',
            'phone' => '+919876543212',
            'password_hash' => Hash::make('Admin@123'),
            'email_verified_at' => now(),
            'status' => 'active',
            'is_active' => 1,
            'version' => 1,
        ]);
        UserProfile::create([
            'user_id' => $adminId,
            'first_name' => 'StayNest',
            'last_name' => 'Admin',
            'is_active' => 1,
        ]);
        UserRole::create([
            'id' => (string) Str::uuid(),
            'user_id' => $adminId,
            'role_id' => $adminRole->id,
            'is_primary' => 1,
            'is_active' => 1,
        ]);

        // 4. Ensure Property Types
        echo "4. Setting up Property Types & Amenities...\n";
        $pgType = PropertyType::firstOrCreate(
            ['slug' => 'pg-hostel'],
            ['id' => (string) Str::uuid(), 'name' => 'PG / Hostel', 'is_active' => 1]
        );
        $colivingType = PropertyType::firstOrCreate(
            ['slug' => 'co-living'],
            ['id' => (string) Str::uuid(), 'name' => 'Co-living Space', 'is_active' => 1]
        );
        $flatType = PropertyType::firstOrCreate(
            ['slug' => 'flat'],
            ['id' => (string) Str::uuid(), 'name' => 'Flat / Apartment', 'is_active' => 1]
        );
        $studioType = PropertyType::firstOrCreate(
            ['slug' => 'studio'],
            ['id' => (string) Str::uuid(), 'name' => 'Studio / Independent Floor', 'is_active' => 1]
        );
        $commercialType = PropertyType::firstOrCreate(
            ['slug' => 'commercial'],
            ['id' => (string) Str::uuid(), 'name' => 'Commercial', 'is_active' => 1]
        );

        // Core Amenities
        $amenityList = [
            ['name' => 'High-Speed Wi-Fi', 'slug' => 'wifi', 'icon' => 'fa-wifi', 'category' => 'connectivity'],
            ['name' => 'Air Conditioning (AC)', 'slug' => 'ac', 'icon' => 'fa-snowflake', 'category' => 'comfort'],
            ['name' => '3 Times Meal / Food', 'slug' => 'food', 'icon' => 'fa-utensils', 'category' => 'food'],
            ['name' => '24/7 Power Backup', 'slug' => 'power-backup', 'icon' => 'fa-bolt', 'category' => 'convenience'],
            ['name' => 'RO Drinking Water', 'slug' => 'ro-water', 'icon' => 'fa-faucet-drip', 'category' => 'essentials'],
            ['name' => 'Daily Housekeeping', 'slug' => 'housekeeping', 'icon' => 'fa-broom', 'category' => 'services'],
            ['name' => 'CCTV & Biometric Security', 'slug' => 'cctv-security', 'icon' => 'fa-shield-halved', 'category' => 'safety'],
            ['name' => 'Washing Machine', 'slug' => 'washing-machine', 'icon' => 'fa-soap', 'category' => 'appliances'],
            ['name' => 'Geyser / Hot Water', 'slug' => 'geyser', 'icon' => 'fa-shower', 'category' => 'bathroom'],
            ['name' => 'Gym & Fitness', 'slug' => 'gym', 'icon' => 'fa-dumbbell', 'category' => 'fitness'],
            ['name' => 'Two Wheeler & Car Parking', 'slug' => 'parking', 'icon' => 'fa-square-parking', 'category' => 'parking'],
            ['name' => 'Conference & Meeting Room', 'slug' => 'conference-room', 'icon' => 'fa-users', 'category' => 'business'],
            ['name' => 'High-Speed Elevators', 'slug' => 'elevator', 'icon' => 'fa-elevator', 'category' => 'convenience'],
            ['name' => 'Cafeteria & Food Court', 'slug' => 'cafeteria', 'icon' => 'fa-mug-hot', 'category' => 'food'],
            ['name' => 'Fire Fighting System', 'slug' => 'fire-safety', 'icon' => 'fa-fire-extinguisher', 'category' => 'safety'],
        ];

        $amenityIds = [];
        foreach ($amenityList as $a) {
            $record = Amenity::firstOrCreate(
                ['slug' => $a['slug']],
                ['id' => (string) Str::uuid(), 'name' => $a['name'], 'icon' => $a['icon'], 'category' => $a['category'], 'is_active' => 1]
            );
            $amenityIds[$a['slug']] = $record->id;
        }

        // 5. Setup 8 Top Cities and Areas
        echo "5. Setting up 8 Top Cities & Areas...\n";
        $topCitiesData = [
            'Delhi' => [
                'name' => 'Delhi',
                'state' => 'Delhi',
                'lat' => 28.6139,
                'lng' => 77.2090,
                'areas' => ['South Extension', 'Hauz Khas', 'Saket', 'Connaught Place', 'Dwarka']
            ],
            'Noida' => [
                'name' => 'Noida',
                'state' => 'Uttar Pradesh',
                'lat' => 28.5355,
                'lng' => 77.3910,
                'areas' => ['Sector 62', 'Sector 18', 'Sector 137', 'Sector 15', 'Electronic City']
            ],
            'Gurgaon' => [
                'name' => 'Gurgaon (Gurugram)',
                'state' => 'Haryana',
                'lat' => 28.4595,
                'lng' => 77.0266,
                'areas' => ['Cyber City', 'Golf Course Road', 'DLF Phase 3', 'Sector 21', 'Sohna Road']
            ],
            'Bangalore' => [
                'name' => 'Bangalore (Bengaluru)',
                'state' => 'Karnataka',
                'lat' => 12.9716,
                'lng' => 77.5946,
                'areas' => ['HSR Layout', 'Koramangala', 'Indiranagar', 'Whitefield', 'Electronic City']
            ],
            'Mumbai' => [
                'name' => 'Mumbai',
                'state' => 'Maharashtra',
                'lat' => 19.0760,
                'lng' => 72.8777,
                'areas' => ['Andheri West', 'Powai', 'Bandra', 'Lower Parel', 'Juhu']
            ],
            'Pune' => [
                'name' => 'Pune',
                'state' => 'Maharashtra',
                'lat' => 18.5204,
                'lng' => 73.8567,
                'areas' => ['Hinjewadi', 'Viman Nagar', 'Koregaon Park', 'Baner', 'Wakad']
            ],
            'Hyderabad' => [
                'name' => 'Hyderabad',
                'state' => 'Telangana',
                'lat' => 17.3850,
                'lng' => 78.4867,
                'areas' => ['Hitec City', 'Gachibowli', 'Madhapur', 'Kondapur', 'Jubilee Hills']
            ],
            'Chennai' => [
                'name' => 'Chennai',
                'state' => 'Tamil Nadu',
                'lat' => 13.0827,
                'lng' => 80.2707,
                'areas' => ['OMR', 'Velachery', 'T Nagar', 'Adyar', 'Anna Nagar']
            ],
        ];

        $cities = [];
        $cityAreas = [];

        $stateMap = DB::table('states')->pluck('id', 'name')->toArray();

        foreach ($topCitiesData as $key => $cData) {
            $stateId = $stateMap[$cData['state']] ?? DB::table('states')->where('name', 'like', "%{$cData['state']}%")->value('id');

            // Exact city matching to prevent Noida matching Greater Noida
            $city = City::where('name', $cData['name'])->first() 
                ?: City::where('slug', Str::slug($cData['name']))->first()
                ?: City::where('name', 'like', "%{$cData['name']}%")->first();

            if (!$city) {
                $city = City::create([
                    'id' => (string) Str::uuid(),
                    'state_id' => $stateId,
                    'name' => $cData['name'],
                    'slug' => Str::slug($cData['name']),
                    'latitude' => $cData['lat'],
                    'longitude' => $cData['lng'],
                    'is_metro' => 1,
                    'is_tier1' => 1,
                    'is_active' => 1,
                    'version' => 1,
                ]);
            }
            $cities[$key] = $city;

            foreach ($cData['areas'] as $aName) {
                $area = Area::where('city_id', $city->id)->where('name', $aName)->first();
                $aLat = ($aName === 'Sector 62') ? 28.6280 : ($cData['lat'] + (rand(-30, 30) / 1000));
                $aLng = ($aName === 'Sector 62') ? 77.3649 : ($cData['lng'] + (rand(-30, 30) / 1000));

                if (!$area) {
                    $area = Area::create([
                        'id' => (string) Str::uuid(),
                        'city_id' => $city->id,
                        'name' => $aName,
                        'slug' => Str::slug($city->name . '-' . $aName),
                        'latitude' => $aLat,
                        'longitude' => $aLng,
                        'is_active' => 1,
                        'version' => 1,
                    ]);
                } else {
                    $area->update(['latitude' => $aLat, 'longitude' => $aLng]);
                }
                $cityAreas[$key][] = $area;
            }
        }

        // 6. High-Quality Image Pools for Properties
        $roomPhotos = [
            'boys' => [
                'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800&auto=format&fit=crop&q=80',
            ],
            'girls' => [
                'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=800&auto=format&fit=crop&q=80',
            ],
            'coliving' => [
                'https://images.unsplash.com/photo-1505691938895-1758d7feb511?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1524758631624-e2822e304c36?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&auto=format&fit=crop&q=80',
            ],
            'flat' => [
                'https://images.unsplash.com/photo-1502005229762-ae1b460020e2?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1600566753376-12c8ab7fb75b?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1600585154526-990dced4db0d?w=800&auto=format&fit=crop&q=80',
            ],
            'studio' => [
                'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?w=800&auto=format&fit=crop&q=80',
            ],
            'commercial' => [
                'https://images.unsplash.com/photo-1497366216548-37526070297c?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1497215728101-856f4ea42174?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1524758631624-e2822e304c36?w=800&auto=format&fit=crop&q=80',
            ],
            'retail' => [
                'https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1567401893414-76b7b1e5a7a5?w=800&auto=format&fit=crop&q=80',
                'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=800&auto=format&fit=crop&q=80',
            ],
        ];

        // 7. Seed 5 ALL-TYPES Listings in each of the 8 Top Cities (Total: 40 Properties)
        echo "6. Seeding 40 properties (5 types across 8 top cities)...\n";
        
        $createdProperties = [];
        $propCount = 0;

        foreach ($topCitiesData as $cityKey => $cData) {
            $city = $cities[$cityKey];
            $areas = $cityAreas[$cityKey];
            $sec62Area = Area::where('city_id', $city->id)->where('name', 'Sector 62')->first() ?: $areas[0];

            if ($cityKey === 'Noida') {
                // 4 Dedicated PGs in Noida Sector 62 + 2BHK Flat + Studio
                $typesConfig = [
                    [
                        'type' => 'boys',
                        'name' => "Stanza Living Kingston House - Boys PG (Noida Sector 62)",
                        'type_id' => $pgType->id,
                        'gender_pref' => 'boys',
                        'bhk' => '1rk',
                        'furnishing' => 'furnished',
                        'rent' => 8500,
                        'deposit' => 17000,
                        'beds' => 36,
                        'avail_beds' => 8,
                        'tag' => 'BOYS PG',
                        'address' => 'B-14, Block B, Sector 62, Near Electronic City Metro Station, Noida, UP 201309',
                        'landmark' => 'Near Sector 62 Metro Station & Stellar IT Park',
                        'lat' => 28.6285,
                        'lng' => 77.3642,
                        'area' => $sec62Area,
                        'desc' => "Spacious luxury Boys PG situated in Sector 62 Noida, right next to Electronic City Metro and Stellar IT Park. Includes 3 hygienic meals daily, high-speed 300 Mbps Wi-Fi, air-conditioned rooms, laundry facility, and 24x7 security guard with CCTV.",
                    ],
                    [
                        'type' => 'girls',
                        'name' => "Zolo Silicon Luxury Girls PG & Stay (Noida Sector 62)",
                        'type_id' => $pgType->id,
                        'gender_pref' => 'girls',
                        'bhk' => '1rk',
                        'furnishing' => 'furnished',
                        'rent' => 9500,
                        'deposit' => 19000,
                        'beds' => 28,
                        'avail_beds' => 6,
                        'tag' => 'GIRLS PG',
                        'address' => 'Plot C-14, Block C, Sector 62, Near JIIT Campus, Noida, UP 201309',
                        'landmark' => 'Opposite JIIT & JSS Academy, Sector 62',
                        'lat' => 28.6272,
                        'lng' => 77.3665,
                        'area' => $sec62Area,
                        'desc' => "Premium and highly secure Girls PG in Sector 62 Noida. Fully furnished with biometric entry, nutritionist-curated meals, power backup, study desks, attached washrooms, and dedicated warden.",
                    ],
                    [
                        'type' => 'coliving',
                        'name' => "Housr Tribe Premium Co-living & PG (Noida Sector 62)",
                        'type_id' => $colivingType->id,
                        'gender_pref' => 'co-ed',
                        'bhk' => 'studio',
                        'furnishing' => 'furnished',
                        'rent' => 14500,
                        'deposit' => 29000,
                        'beds' => 45,
                        'avail_beds' => 10,
                        'tag' => 'CO-LIVING',
                        'address' => 'Tower D, Sector 62 Institutional Area, Near Candor TechSpace, Noida, UP 201309',
                        'landmark' => 'Near Candor TechSpace & Unitech Infospace, Sector 62',
                        'lat' => 28.6291,
                        'lng' => 77.3680,
                        'area' => $sec62Area,
                        'desc' => "Vibrant co-living hub designed for young working professionals and founders in Sector 62 Noida. Features dedicated co-working zone, high-speed fiber internet, rooftop cafe, gym, community events, and games area.",
                    ],
                    [
                        'type' => 'boys',
                        'name' => "Royal Comfort Executive Boys PG (Noida Sector 62)",
                        'type_id' => $pgType->id,
                        'gender_pref' => 'boys',
                        'bhk' => '1rk',
                        'furnishing' => 'furnished',
                        'rent' => 7800,
                        'deposit' => 15600,
                        'beds' => 20,
                        'avail_beds' => 5,
                        'tag' => 'BOYS PG',
                        'address' => 'A-88, Block A, Sector 62, Near Fortis Hospital, Noida, UP 201309',
                        'landmark' => 'Near Stellar IT Park & Fortis Hospital, Sector 62',
                        'lat' => 28.6255,
                        'lng' => 77.3620,
                        'area' => $sec62Area,
                        'desc' => "Affordable and peaceful executive Boys PG in Sector 62 Noida near Fortis Hospital. Equipped with 3 home-style meals, AC, RO purified water, washing machine, and 24/7 security.",
                    ],
                    [
                        'type' => 'flat',
                        'name' => "Greenwood Heights 2BHK Apartment (Noida)",
                        'type_id' => $flatType->id,
                        'gender_pref' => 'anyone',
                        'bhk' => '2bhk',
                        'furnishing' => 'furnished',
                        'rent' => 26000,
                        'deposit' => 52000,
                        'beds' => 4,
                        'avail_beds' => 4,
                        'tag' => '2BHK FLAT',
                        'address' => 'Tower B, Express Greens, Sector 62, Noida, UP 201309',
                        'landmark' => 'Near Sector 62 Metro Station, Noida',
                        'lat' => 28.6280,
                        'lng' => 77.3650,
                        'area' => $sec62Area,
                        'desc' => "Modern fully furnished 2BHK apartment in Sector 62 Noida. Fitted with modular kitchen, air-conditioners in both bedrooms, covered car parking, club house, and 24/7 power backup.",
                    ],
                ];
            } else {
                // Standard 5 Types for other cities
                $typesConfig = [
                    [
                        'type' => 'boys',
                        'name' => "Stanza Prime Boys PG ({$cData['name']})",
                        'type_id' => $pgType->id,
                        'gender_pref' => 'boys',
                        'bhk' => '1rk',
                        'furnishing' => 'furnished',
                        'rent' => rand(7500, 9500),
                        'deposit' => rand(15000, 19000),
                        'beds' => 24,
                        'avail_beds' => 6,
                        'tag' => 'BOYS PG',
                        'desc' => "Spacious luxury Boys PG situated in the heart of {$cData['name']}. Includes 3 hygienic meals daily, high-speed 300 Mbps Wi-Fi, air-conditioned rooms, laundry facility, and 24x7 security guard with CCTV.",
                    ],
                    [
                        'type' => 'girls',
                        'name' => "Aura Executive Girls Stay ({$cData['name']})",
                        'type_id' => $pgType->id,
                        'gender_pref' => 'girls',
                        'bhk' => '1rk',
                        'furnishing' => 'furnished',
                        'rent' => rand(8500, 11500),
                        'deposit' => rand(17000, 23000),
                        'beds' => 30,
                        'avail_beds' => 8,
                        'tag' => 'GIRLS PG',
                        'desc' => "Premium and highly secure Girls PG in {$cData['name']}. Fully furnished with biometric entry, nutritionist-curated meals, power backup, study desks, and attached washrooms.",
                    ],
                    [
                        'type' => 'coliving',
                        'name' => "UrbanNest Co-Living Community ({$cData['name']})",
                        'type_id' => $colivingType->id,
                        'gender_pref' => 'co-ed',
                        'bhk' => 'studio',
                        'furnishing' => 'furnished',
                        'rent' => rand(13000, 17500),
                        'deposit' => rand(26000, 35000),
                        'beds' => 40,
                        'avail_beds' => 12,
                        'tag' => 'CO-LIVING',
                        'desc' => "Vibrant co-living hub designed for young working professionals and founders in {$cData['name']}. Features dedicated co-working zone, high-speed fiber internet, rooftop cafe, community events, and games area.",
                    ],
                    [
                        'type' => 'flat',
                        'name' => "Greenwood Heights 2BHK Apartment ({$cData['name']})",
                        'type_id' => $flatType->id,
                        'gender_pref' => 'anyone',
                        'bhk' => '2bhk',
                        'furnishing' => 'furnished',
                        'rent' => rand(22000, 34000),
                        'deposit' => rand(44000, 68000),
                        'beds' => 4,
                        'avail_beds' => 4,
                        'tag' => '2BHK FLAT',
                        'desc' => "Modern fully furnished 2BHK apartment in {$cData['name']}. Fitted with modular kitchen, air-conditioners in both bedrooms, covered car parking, club house, and 24/7 power backup.",
                    ],
                    [
                        'type' => 'studio',
                        'name' => "Skyline Studio Suite ({$cData['name']})",
                        'type_id' => $studioType->id,
                        'gender_pref' => 'anyone',
                        'bhk' => 'studio',
                        'furnishing' => 'furnished',
                        'rent' => rand(15000, 21000),
                        'deposit' => rand(30000, 42000),
                        'beds' => 2,
                        'avail_beds' => 2,
                        'tag' => 'STUDIO',
                        'desc' => "Ultra-modern private studio suite in {$cData['name']} offering uninterrupted skyline views, private kitchenette, smart TV, ergonomic workstation, king size bed, and ambient lighting.",
                    ],
                ];
            }

            foreach ($typesConfig as $idx => $cfg) {
                $area = $cfg['area'] ?? $areas[$idx % count($areas)];
                $propId = (string) Str::uuid();
                $slug = Str::slug($cfg['name'] . '-' . $area->name . '-' . rand(100, 999));

                $property = Property::create([
                    'id' => $propId,
                    'broker_id' => $brokerId,
                    'city_id' => $city->id,
                    'area_id' => $area->id,
                    'property_type_id' => $cfg['type_id'],
                    'ad_type' => 'rent',
                    'property_category' => 'residential',
                    'name' => $cfg['name'],
                    'slug' => $slug,
                    'description' => $cfg['desc'],
                    'address' => $cfg['address'] ?? ("Building {$idx}0" . rand(1, 9) . ", {$area->name}, {$city->name}"),
                    'landmark' => $cfg['landmark'] ?? ("Near {$area->name} Metro & Commercial Hub"),
                    'latitude' => $cfg['lat'] ?? ($area->latitude ?: $city->latitude),
                    'longitude' => $cfg['lng'] ?? ($area->longitude ?: $city->longitude),
                    'gender_preference' => $cfg['gender_pref'],
                    'bhk_type' => $cfg['bhk'],
                    'furnishing_status' => $cfg['furnishing'],
                    'monthly_rent' => $cfg['rent'],
                    'security_deposit' => $cfg['deposit'],
                    'maintenance_charges' => 1000.00,
                    'booking_token_amount' => 2000.00,
                    'total_beds' => $cfg['beds'],
                    'available_beds' => $cfg['avail_beds'],
                    'notice_period_days' => 30,
                    'rating' => round(4.5 + (rand(0, 4) / 10), 1),
                    'total_reviews' => rand(5, 24),
                    'verification_status' => 'verified',
                    'status' => 'active',
                    'featured' => ($idx < 2) ? 1 : 0,
                    'is_recommended' => 1,
                    'tag' => $cfg['tag'],
                    'is_active' => 1,
                    'version' => 1,
                ]);

                $createdProperties[] = $property;
                $propCount++;

                // Attach Images (3-4 per property)
                $photoUrls = $roomPhotos[$cfg['type']] ?? $roomPhotos['boys'];
                foreach ($photoUrls as $pIdx => $pUrl) {
                    PropertyImage::create([
                        'id' => (string) Str::uuid(),
                        'property_id' => $propId,
                        'image_url' => $pUrl,
                        'image_type' => ($pIdx === 0) ? 'cover' : 'room',
                        'sort_order' => $pIdx,
                        'is_primary' => ($pIdx === 0) ? 1 : 0,
                        'is_active' => 1,
                        'version' => 1,
                    ]);
                }

                // Attach Amenities (6-8 per property)
                $selectedAmenities = ['wifi', 'ac', 'power-backup', 'ro-water', 'housekeeping', 'cctv-security', 'washing-machine', 'geyser'];
                if ($cfg['type'] === 'boys' || $cfg['type'] === 'girls') {
                    $selectedAmenities[] = 'food';
                }
                if ($cfg['type'] === 'coliving' || $cfg['type'] === 'studio') {
                    $selectedAmenities[] = 'gym';
                    $selectedAmenities[] = 'parking';
                }

                foreach ($selectedAmenities as $aSlug) {
                    if (isset($amenityIds[$aSlug])) {
                        DB::table('property_amenities')->insert([
                            'id' => (string) Str::uuid(),
                            'property_id' => $propId,
                            'amenity_id' => $amenityIds[$aSlug],
                            'created_at' => now(),
                        ]);
                    }
                }

                // Attach Rules
                $rules = [
                    'Visitors allowed in common areas until 10:00 PM',
                    'No smoking inside private rooms and common corridors',
                    'Strict silent hours between 11:00 PM and 6:00 AM',
                    'Keep your personal room and attached bathroom clean',
                ];
                foreach ($rules as $rText) {
                    PropertyRule::create([
                        'id' => (string) Str::uuid(),
                        'property_id' => $propId,
                        'rule_text' => $rText,
                        'rule_type' => 'mandatory',
                        'is_active' => 1,
                        'version' => 1,
                    ]);
                }
            }
        }
        echo "Created $propCount residential properties across 8 cities.\n";

        // 7. Seed Commercial Properties for the Commercial Owner (Vikram Singhania)
        echo "6b. Seeding Grade-A Commercial Properties for Commercial Owner (Vikram Singhania)...\n";
        $noidaCity = $cities['Noida'];
        $noidaSec62 = Area::where('city_id', $noidaCity->id)->where('name', 'Sector 62')->first() ?: $cityAreas['Noida'][0];
        $gurgaonCity = $cities['Gurgaon'];
        $gurgaonCyberCity = Area::where('city_id', $gurgaonCity->id)->where('name', 'Cyber City')->first() ?: $cityAreas['Gurgaon'][0];
        $bangaloreCity = $cities['Bangalore'];
        $bangaloreArea = Area::where('city_id', $bangaloreCity->id)->where('name', 'Koramangala')->first() ?: $cityAreas['Bangalore'][0];
        $mumbaiCity = $cities['Mumbai'];
        $mumbaiArea = Area::where('city_id', $mumbaiCity->id)->where('name', 'Bandra')->first() ?: $cityAreas['Mumbai'][0];

        $commercialPropertiesList = [
            [
                'name' => 'Stellar Cyber Park - Grade A Corporate Office (Sector 62 Noida)',
                'type' => 'commercial',
                'city' => $noidaCity,
                'area' => $noidaSec62,
                'category' => 'commercial',
                'tag' => 'COMMERCIAL',
                'rent' => 125000,
                'deposit' => 250000,
                'carpet_area' => 3500,
                'furnishing' => 'furnished',
                'lat' => 28.6288,
                'lng' => 77.3655,
                'address' => 'Tower A, Stellar IT Park, Sector 62, Noida, UP 201309',
                'landmark' => 'Next to Electronic City Metro Station & NH-24',
                'desc' => 'Fully furnished Grade-A corporate office in Sector 62 Noida. Features 45 workstations, 3 executive director cabins, 12-seater conference room, high-speed fiber internet, 100% power backup, and reserved basement parking.',
                'amenities' => ['wifi', 'ac', 'power-backup', 'cctv-security', 'elevator', 'conference-room', 'parking', 'cafeteria', 'fire-safety'],
            ],
            [
                'name' => 'Candor TechSpace Prime Retail Showroom & Plaza (Sector 62 Noida)',
                'type' => 'retail',
                'city' => $noidaCity,
                'area' => $noidaSec62,
                'category' => 'commercial',
                'tag' => 'RETAIL SHOP',
                'rent' => 85000,
                'deposit' => 170000,
                'carpet_area' => 1200,
                'furnishing' => 'furnished',
                'lat' => 28.6275,
                'lng' => 77.3670,
                'address' => 'Shop GF-04, Candor TechSpace Commercial Plaza, Sector 62, Noida, UP 201309',
                'landmark' => 'High-Footfall Main Boulevard near Sector 62 Metro',
                'desc' => 'Prime ground-floor retail showroom with 30ft wide glass frontage and massive footfall from adjacent tech parks. Ideal for banks, branded cafes, electronics showrooms, or retail stores.',
                'amenities' => ['ac', 'power-backup', 'cctv-security', 'parking', 'fire-safety', 'elevator'],
            ],
            [
                'name' => 'DLF CyberHub Executive Corporate Suites (Cyber City Gurgaon)',
                'type' => 'commercial',
                'city' => $gurgaonCity,
                'area' => $gurgaonCyberCity,
                'category' => 'commercial',
                'tag' => 'COMMERCIAL',
                'rent' => 210000,
                'deposit' => 420000,
                'carpet_area' => 4800,
                'furnishing' => 'furnished',
                'lat' => 28.4950,
                'lng' => 77.0890,
                'address' => 'Building 10B, DLF Cyber City, DLF Phase 3, Gurgaon, Haryana 122002',
                'landmark' => 'Near CyberHub Rapid Metro Station',
                'desc' => 'State-of-the-art corporate office suite in DLF Cyber City Gurgaon. Fully fitted with acoustic conference facilities, modern manager cabins, central HVAC, 24/7 security, and visitor parking.',
                'amenities' => ['wifi', 'ac', 'power-backup', 'cctv-security', 'elevator', 'conference-room', 'parking', 'cafeteria', 'fire-safety'],
            ],
            [
                'name' => 'Koramangala Tech Vista Plug & Play Coworking Hub (Bangalore)',
                'type' => 'commercial',
                'city' => $bangaloreCity,
                'area' => $bangaloreArea,
                'category' => 'commercial',
                'tag' => 'COMMERCIAL',
                'rent' => 180000,
                'deposit' => 360000,
                'carpet_area' => 6000,
                'furnishing' => 'furnished',
                'lat' => 12.9352,
                'lng' => 77.6245,
                'address' => 'Vista Towers, 80 Feet Road, Koramangala 4th Block, Bangalore, Karnataka 560034',
                'landmark' => 'Near Sony World Signal & Forum Mall',
                'desc' => 'Vibrant plug-and-play commercial coworking and office floor in Koramangala Bangalore. Designed for high-growth tech startups and MNC teams, with dedicated server room, cafeteria, and event space.',
                'amenities' => ['wifi', 'ac', 'power-backup', 'cctv-security', 'elevator', 'conference-room', 'parking', 'cafeteria', 'fire-safety'],
            ],
            [
                'name' => 'One BKC Financial Corporate Headquarters (Bandra East Mumbai)',
                'type' => 'commercial',
                'city' => $mumbaiCity,
                'area' => $mumbaiArea,
                'category' => 'commercial',
                'tag' => 'COMMERCIAL',
                'rent' => 350000,
                'deposit' => 700000,
                'carpet_area' => 8500,
                'furnishing' => 'furnished',
                'lat' => 19.0657,
                'lng' => 72.8687,
                'address' => 'G Block, Bandra Kurla Complex, Bandra East, Mumbai, Maharashtra 400051',
                'landmark' => 'Opposite National Stock Exchange (NSE) & MCA Club',
                'desc' => 'Iconic premium commercial headquarters at One BKC Mumbai. Features double-height glass reception, floor-to-ceiling windows, executive boardroom with video conferencing, high-speed destination elevators, and multi-level parking.',
                'amenities' => ['wifi', 'ac', 'power-backup', 'cctv-security', 'elevator', 'conference-room', 'parking', 'cafeteria', 'fire-safety'],
            ],
        ];

        foreach ($commercialPropertiesList as $cIdx => $cp) {
            $cPropId = (string) Str::uuid();
            $cSlug = Str::slug($cp['name'] . '-' . rand(100, 999));

            $commercialProperty = Property::create([
                'id' => $cPropId,
                'broker_id' => $commercialBrokerId,
                'city_id' => $cp['city']->id,
                'area_id' => $cp['area']->id,
                'property_type_id' => $commercialType->id,
                'ad_type' => 'rent',
                'property_category' => 'commercial',
                'name' => $cp['name'],
                'slug' => $cSlug,
                'description' => $cp['desc'],
                'address' => $cp['address'],
                'landmark' => $cp['landmark'],
                'latitude' => $cp['lat'],
                'longitude' => $cp['lng'],
                'gender_preference' => 'any',
                'bhk_type' => 'studio',
                'furnishing_status' => $cp['furnishing'],
                'carpet_area_sqft' => $cp['carpet_area'],
                'monthly_rent' => $cp['rent'],
                'security_deposit' => $cp['deposit'],
                'maintenance_charges' => 5000.00,
                'booking_token_amount' => 10000.00,
                'total_beds' => 0,
                'available_beds' => 0,
                'notice_period_days' => 60,
                'rating' => 4.9,
                'total_reviews' => rand(8, 20),
                'verification_status' => 'verified',
                'status' => 'active',
                'featured' => 1,
                'is_recommended' => 1,
                'tag' => $cp['tag'],
                'is_active' => 1,
                'version' => 1,
            ]);

            $createdProperties[] = $commercialProperty;
            $propCount++;

            // Images
            $cPhotos = $roomPhotos[$cp['type']] ?? $roomPhotos['commercial'];
            foreach ($cPhotos as $pIdx => $pUrl) {
                PropertyImage::create([
                    'id' => (string) Str::uuid(),
                    'property_id' => $cPropId,
                    'image_url' => $pUrl,
                    'image_type' => ($pIdx === 0) ? 'cover' : 'commercial_space',
                    'sort_order' => $pIdx,
                    'is_primary' => ($pIdx === 0) ? 1 : 0,
                    'is_active' => 1,
                    'version' => 1,
                ]);
            }

            // Amenities
            foreach ($cp['amenities'] as $aSlug) {
                if (isset($amenityIds[$aSlug])) {
                    DB::table('property_amenities')->insert([
                        'id' => (string) Str::uuid(),
                        'property_id' => $cPropId,
                        'amenity_id' => $amenityIds[$aSlug],
                        'created_at' => now(),
                    ]);
                }
            }

            // Rules
            $cRules = [
                '24/7 building access with smart biometric ID badges',
                'Strict compliance with fire safety & no-smoking regulations',
                'Reserved designated parking for employees & registered visitors',
                'Cafeteria & common pantry cleanliness protocols must be maintained',
            ];
            foreach ($cRules as $crText) {
                PropertyRule::create([
                    'id' => (string) Str::uuid(),
                    'property_id' => $cPropId,
                    'rule_text' => $crText,
                    'rule_type' => 'mandatory',
                    'is_active' => 1,
                    'version' => 1,
                ]);
            }
        }
        echo "Created commercial properties.\n";

        // 8. Seed 5 Different Flatmates / Roommate Posts
        echo "7. Seeding 5 Flatmates / Roommate Posts...\n";
        $flatmatesData = [
            [
                'title' => 'Need Female Flatmate in Chic 3BHK @ HSR Layout, Bangalore',
                'name' => 'Sneha Mukherjee',
                'age' => 24,
                'gender' => 'female',
                'profession' => 'Senior UI/UX Designer @ Swiggy',
                'city' => 'Bangalore',
                'locality' => 'HSR Layout, Sector 2',
                'address' => 'Villa 45, 14th Main, HSR Layout, Bangalore',
                'bhk' => '3bhk',
                'furnishing' => 'furnished',
                'budget' => 15500,
                'pref_gender' => 'female',
                'avatar' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=300&auto=format&fit=crop&q=80',
                'lifestyle' => ['veg' => 0, 'non_veg' => 1, 'no_smoking' => 1, 'wfh' => 1, 'early_bird' => 0],
                'amenities' => ['wifi' => 1, 'ac' => 1, 'kitchen' => 1, 'fridge' => 1, 'washing_machine' => 1, 'power_backup' => 1],
                'desc' => 'Spacious sunlit master bedroom with private balcony in a fully furnished 3BHK flat at HSR Layout. High speed WiFi, AC, and cook/maid in place. Looking for a clean and friendly female professional.',
            ],
            [
                'title' => 'Looking for Male Flatmate in Spacious 2BHK @ Sector 62, Noida',
                'name' => 'Aarav Mehta',
                'age' => 25,
                'gender' => 'male',
                'profession' => 'Software Engineer @ Microsoft',
                'city' => 'Noida',
                'locality' => 'Sector 62, Near Metro',
                'address' => 'Tower B, Express Greens, Sector 62, Noida',
                'bhk' => '2bhk',
                'furnishing' => 'furnished',
                'budget' => 12500,
                'pref_gender' => 'male',
                'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=300&auto=format&fit=crop&q=80',
                'lifestyle' => ['veg' => 1, 'no_smoking' => 1, 'wfh' => 1, 'gym_person' => 1],
                'amenities' => ['wifi' => 1, 'ac' => 1, 'fridge' => 1, 'washing_machine' => 1, 'cook_maid' => 1, 'power_backup' => 1],
                'desc' => 'Fully furnished room with attached washroom available in Sector 62 Noida. Just 5 mins from Metro station. Looking for a non-smoking working professional or student.',
            ],
            [
                'title' => 'Roommate Wanted for 2BHK Flat in South Extension, Delhi',
                'name' => 'Rohan Sharma',
                'age' => 26,
                'gender' => 'male',
                'profession' => 'Chartered Accountant @ Deloitte',
                'city' => 'Delhi',
                'locality' => 'South Extension Part 2',
                'address' => 'E-12, South Extension 2, New Delhi',
                'bhk' => '2bhk',
                'furnishing' => 'furnished',
                'budget' => 14000,
                'pref_gender' => 'male',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&auto=format&fit=crop&q=80',
                'lifestyle' => ['veg' => 1, 'no_smoking' => 1, 'early_bird' => 1],
                'amenities' => ['wifi' => 1, 'ac' => 1, 'fridge' => 1, 'washing_machine' => 1, 'geyser' => 1],
                'desc' => 'Independent private room available in a gated peaceful society in South Extension. Safe neighborhood, 24/7 security and close to yellow line metro.',
            ],
            [
                'title' => 'Looking for Female Roommate in 2BHK Lake View Flat @ Powai, Mumbai',
                'name' => 'Ananya Iyer',
                'age' => 25,
                'gender' => 'female',
                'profession' => 'Product Manager @ Hiranandani Tech',
                'city' => 'Mumbai',
                'locality' => 'Hiranandani Gardens, Powai',
                'address' => 'Castalia Wing A, Hiranandani Gardens, Powai, Mumbai',
                'bhk' => '2bhk',
                'furnishing' => 'furnished',
                'budget' => 22000,
                'pref_gender' => 'female',
                'avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=300&auto=format&fit=crop&q=80',
                'lifestyle' => ['non_veg' => 1, 'no_smoking' => 1, 'wfh' => 1],
                'amenities' => ['wifi' => 1, 'ac' => 1, 'kitchen' => 1, 'fridge' => 1, 'washing_machine' => 1, 'balcony' => 1],
                'desc' => 'Stunning lake view master bedroom in Hiranandani Powai. Modern flat with all amenities, swimming pool and gym access included. Looking for a respectful female flatmate.',
            ],
            [
                'title' => 'Roommate Needed for 3BHK Society Flat @ Hinjewadi Phase 1, Pune',
                'name' => 'Vikram Deshmukh',
                'age' => 27,
                'gender' => 'male',
                'profession' => 'Data Scientist @ Infosys',
                'city' => 'Pune',
                'locality' => 'Hinjewadi Phase 1, Near Tech Park',
                'address' => 'Blue Ridge Township, Hinjewadi Phase 1, Pune',
                'bhk' => '3bhk',
                'furnishing' => 'furnished',
                'budget' => 11000,
                'pref_gender' => 'any',
                'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=300&auto=format&fit=crop&q=80',
                'lifestyle' => ['veg' => 1, 'no_smoking' => 1, 'wfh' => 1, 'gym_person' => 1],
                'amenities' => ['wifi' => 1, 'ac' => 1, 'kitchen' => 1, 'washing_machine' => 1, 'power_backup' => 1, 'parking' => 1],
                'desc' => 'Spacious room in Blue Ridge Hinjewadi Pune. Walking distance to Infosys and TCS. Golf course and gym inside society. Available immediately.',
            ],
        ];

        foreach ($flatmatesData as $f) {
            RoommatePost::create([
                'id' => (string) Str::uuid(),
                'user_id' => $tenantId,
                'title' => $f['title'],
                'slug' => Str::slug($f['title'] . '-' . rand(100, 999)),
                'post_type' => 'have_room',
                'city' => $f['city'],
                'locality' => $f['locality'],
                'full_address' => $f['address'],
                'poster_name' => $f['name'],
                'poster_age' => $f['age'],
                'poster_gender' => $f['gender'],
                'profession' => $f['profession'],
                'occupation_type' => 'working_professional',
                'gender_preference' => $f['pref_gender'],
                'bhk_type' => $f['bhk'],
                'furnishing' => $f['furnishing'],
                'budget_min' => $f['budget'] - 2000,
                'budget_max' => $f['budget'],
                'move_in_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'preferred_duration_months' => 6,
                'lifestyle' => $f['lifestyle'],
                'amenities' => $f['amenities'],
                'description' => $f['desc'],
                'contact_phone' => '+919876543210',
                'contact_whatsapp' => '+919876543210',
                'contact_visible_to_all' => 1,
                'poster_avatar_url' => $f['avatar'],
                'status' => 'active',
                'is_active' => 1,
                'expires_at' => Carbon::now()->addDays(45),
                'view_count' => rand(15, 80),
                'version' => 1,
            ]);
        }
        echo "Created 5 Flatmate listings.\n";

        // 9. Add Verified Reviews across properties
        echo "8. Adding verified reviews...\n";
        $reviewComments = [
            "Awesome PG with delicious 3-times home style meals and lightning fast Wi-Fi! Highly recommended for working professionals.",
            "Very clean, spacious rooms and extremely polite owner. Daily cleaning happens on time. 5 stars!",
            "Great stay experience! The location is just walking distance to the metro station and IT parks. Very safe and peaceful.",
            "Security and hygiene are top notch. Power backup works 24/7 without any interruptions during work from home.",
            "Worth every penny. The co-living vibes, rooftop area, and amenities make it feel just like home.",
        ];

        foreach (array_slice($createdProperties, 0, 15) as $rIdx => $p) {
            Review::create([
                'id' => (string) Str::uuid(),
                'user_id' => $tenantId,
                'property_id' => $p->id,
                'rating' => 5,
                'title' => 'Exceptional & Highly Recommended Stay!',
                'comment' => $reviewComments[$rIdx % count($reviewComments)],
                'broker_reply' => 'Thank you for your wonderful feedback Aman! We are glad you enjoyed your stay with us.',
                'broker_reply_at' => now()->subDays(2),
                'is_verified' => 1,
                'helpful_count' => rand(3, 18),
                'status' => 'approved',
                'is_active' => 1,
                'version' => 1,
            ]);
        }
        echo "Created verified reviews.\n";

        echo "\n>>> Fresh Database Seeding Complete! <<<\n";
    }
}
