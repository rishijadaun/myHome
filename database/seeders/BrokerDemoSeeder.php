<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Bed;
use App\Models\Block;
use App\Models\Booking;
use App\Models\City;
use App\Models\Floor;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\PropertyType;
use App\Models\Review;
use App\Models\Role;
use App\Models\Room;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BrokerDemoSeeder extends Seeder
{
    public function run(): void
    {
        $broker = User::where('email', 'vikram@broker.com')->first();
        if (!$broker) return;

        $noida = City::where('name', 'like', '%Noida%')->first() ?? City::first();
        $bangalore = City::where('name', 'like', '%Bangalore%')->first() ?? City::first();
        $delhi = City::where('name', 'like', '%Delhi%')->first() ?? City::first();

        $noidaArea = Area::firstOrCreate(['city_id' => $noida->id, 'name' => 'Sector 62'], [
            'id' => (string) Str::uuid(),
            'slug' => 'sector-62-' . Str::random(4),
            'is_active' => 1,
            'version' => 1,
        ]);
        $bangaloreArea = Area::firstOrCreate(['city_id' => $bangalore->id, 'name' => 'Indiranagar'], [
            'id' => (string) Str::uuid(),
            'slug' => 'indiranagar-' . Str::random(4),
            'is_active' => 1,
            'version' => 1,
        ]);
        $delhiArea = Area::firstOrCreate(['city_id' => $delhi->id, 'name' => 'South Extension'], [
            'id' => (string) Str::uuid(),
            'slug' => 'south-extension-' . Str::random(4),
            'is_active' => 1,
            'version' => 1,
        ]);

        $pgType = PropertyType::where('slug', 'pg')->orWhere('slug', 'pg-hostel')->first() ?? PropertyType::first();
        $colivingType = PropertyType::where('slug', 'co-living')->first() ?? PropertyType::first();

        $sampleProperties = [
            [
                'name' => 'Sunrise Premium Boys PG',
                'city_id' => $noida->id,
                'area_id' => $noidaArea?->id,
                'property_type_id' => $pgType->id,
                'gender_preference' => 'boys',
                'monthly_rent' => 8500,
                'security_deposit' => 17000,
                'total_beds' => 24,
                'available_beds' => 6,
                'address' => 'Plot 45, Sector 62, Electronic City, Noida',
                'landmark' => 'Near Metro Station',
                'image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Aura Luxury Women Stay',
                'city_id' => $bangalore->id,
                'area_id' => $bangaloreArea?->id,
                'property_type_id' => $pgType->id,
                'gender_preference' => 'girls',
                'monthly_rent' => 11000,
                'security_deposit' => 22000,
                'total_beds' => 30,
                'available_beds' => 8,
                'address' => '100ft Road, Indiranagar, Bangalore',
                'landmark' => 'Behind Toit Pub',
                'image' => 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Urban Nest Co-Living Spaces',
                'city_id' => $bangalore->id,
                'area_id' => $bangaloreArea?->id,
                'property_type_id' => $colivingType->id,
                'gender_preference' => 'co-ed',
                'monthly_rent' => 14500,
                'security_deposit' => 29000,
                'total_beds' => 40,
                'available_beds' => 10,
                'address' => '27th Main, Sector 1, HSR Layout, Bangalore',
                'landmark' => 'Near Agara Lake',
                'image' => 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
            ],
            [
                'name' => 'Royal Heritage Executive PG',
                'city_id' => $delhi->id,
                'area_id' => $delhiArea?->id,
                'property_type_id' => $pgType->id,
                'gender_preference' => 'boys',
                'monthly_rent' => 9500,
                'security_deposit' => 19000,
                'total_beds' => 20,
                'available_beds' => 4,
                'address' => 'Block C, South Extension Part II, New Delhi',
                'landmark' => 'Near AIIMS Metro',
                'image' => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80',
            ],
        ];

        $tenantRole = Role::where('slug', 'tenant')->first();

        // Seed some tenants
        $tenants = [];
        $tenantData = [
            ['first_name' => 'Rahul', 'last_name' => 'Sharma', 'email' => 'rahul.tenant@gmail.com', 'phone' => '+919876510001'],
            ['first_name' => 'Priya', 'last_name' => 'Patel', 'email' => 'priya.tenant@gmail.com', 'phone' => '+919876510002'],
            ['first_name' => 'Amit', 'last_name' => 'Kumar', 'email' => 'amit.tenant@gmail.com', 'phone' => '+919876510003'],
            ['first_name' => 'Sneha', 'last_name' => 'Verma', 'email' => 'sneha.tenant@gmail.com', 'phone' => '+919876510004'],
            ['first_name' => 'Ankit', 'last_name' => 'Mishra', 'email' => 'ankit.tenant@gmail.com', 'phone' => '+919876510005'],
        ];

        foreach ($tenantData as $t) {
            $user = User::firstOrCreate(['email' => $t['email']], [
                'id' => (string) Str::uuid(),
                'phone' => $t['phone'],
                'password_hash' => Hash::make('User@123'),
                'status' => 'active',
                'is_active' => 1,
                'version' => 1,
            ]);

            UserProfile::firstOrCreate(['user_id' => $user->id], [
                'first_name' => $t['first_name'],
                'last_name' => $t['last_name'],
                'full_name' => $t['first_name'] . ' ' . $t['last_name'],
                'is_active' => 1,
                'version' => 1,
            ]);

            if ($tenantRole) {
                UserRole::firstOrCreate(['user_id' => $user->id, 'role_id' => $tenantRole->id], [
                    'id' => (string) Str::uuid(),
                    'is_primary' => 1,
                    'is_active' => 1,
                    'version' => 1,
                ]);
            }

            $tenants[] = $user;
        }

        foreach ($sampleProperties as $propData) {
            $prop = Property::firstOrCreate(['name' => $propData['name'], 'broker_id' => $broker->id], [
                'id' => (string) Str::uuid(),
                'city_id' => $propData['city_id'],
                'area_id' => $propData['area_id'],
                'property_type_id' => $propData['property_type_id'],
                'gender_preference' => $propData['gender_preference'],
                'monthly_rent' => $propData['monthly_rent'],
                'security_deposit' => $propData['security_deposit'],
                'total_beds' => $propData['total_beds'],
                'available_beds' => $propData['available_beds'],
                'address' => $propData['address'],
                'landmark' => $propData['landmark'],
                'description' => 'Modern amenities, high-speed WiFi, 3 meals daily, and 24/7 security.',
                'verification_status' => 'verified',
                'status' => 'active',
                'is_active' => 1,
                'version' => 1,
            ]);

            PropertyImage::firstOrCreate(['property_id' => $prop->id, 'is_primary' => 1], [
                'id' => (string) Str::uuid(),
                'image_url' => $propData['image'],
                'caption' => $prop->name,
                'sort_order' => 1,
                'is_active' => 1,
            ]);

            $blockId = (string) Str::uuid();
            \Illuminate\Support\Facades\DB::table('blocks')->insertOrIgnore([
                'id' => $blockId,
                'property_id' => $prop->id,
                'name' => 'Main Block',
                'is_active' => 1,
                'version' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $floorId = (string) Str::uuid();
            \Illuminate\Support\Facades\DB::table('floors')->insertOrIgnore([
                'id' => $floorId,
                'block_id' => $blockId,
                'floor_number' => 1,
                'name' => 'First Floor',
                'is_active' => 1,
                'version' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $roomId = (string) Str::uuid();
            \Illuminate\Support\Facades\DB::table('rooms')->insertOrIgnore([
                'id' => $roomId,
                'floor_id' => $floorId,
                'room_type_id' => 'c9a778e6-8dab-11f1-a4cf-1062e5a5cd6c',
                'room_number' => '101',
                'total_beds' => 2,
                'available_beds' => 1,
                'monthly_rent' => $prop->monthly_rent,
                'status' => 'available',
                'is_active' => 1,
                'version' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $bedId = (string) Str::uuid();
            \Illuminate\Support\Facades\DB::table('beds')->insertOrIgnore([
                'id' => $bedId,
                'room_id' => $roomId,
                'bed_number' => 'Bed-A',
                'bed_type' => 'single',
                'status' => 'occupied',
                'is_active' => 1,
                'version' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Seed bookings for this property
            foreach ($tenants as $idx => $t) {
                $status = ($idx % 2 === 0) ? 'pending' : 'confirmed';
                $bookingNum = 'BK-BR-' . strtoupper(Str::random(6));

                $validRoom = \Illuminate\Support\Facades\DB::table('rooms')->first();
                $validBed = \Illuminate\Support\Facades\DB::table('beds')->first();

                Booking::firstOrCreate(['user_id' => $t->id, 'property_id' => $prop->id], [
                    'id' => (string) Str::uuid(),
                    'booking_id' => 'BK-' . strtoupper(Str::random(8)),
                    'room_id' => $validRoom ? $validRoom->id : $roomId,
                    'bed_id' => $validBed ? $validBed->id : $bedId,
                    'broker_id' => $broker->id,
                    'check_in_date' => now()->addDays($idx * 2),
                    'check_out_date' => now()->addDays(90 + $idx * 2),
                    'duration_months' => 3,
                    'base_rent' => $prop->monthly_rent,
                    'security_deposit' => $prop->security_deposit ?? ($prop->monthly_rent * 2),
                    'total_amount' => $prop->monthly_rent,
                    'paid_amount' => ($status === 'confirmed') ? $prop->monthly_rent : 0.00,
                    'booking_status' => $status,
                    'payment_status' => ($status === 'confirmed') ? 'paid' : 'pending',
                    'broker_approval' => ($status === 'confirmed') ? 'approved' : 'pending',
                    'is_active' => 1,
                    'version' => 1,
                    'created_at' => now()->subDays($idx * 3),
                ]);

                // Seed review
                if ($status === 'confirmed') {
                    Review::firstOrCreate(['user_id' => $t->id, 'property_id' => $prop->id], [
                        'id' => (string) Str::uuid(),
                        'rating' => 4.8,
                        'title' => 'Exceptional Living Experience',
                        'comment' => 'The property is pristine, WiFi is blazing fast, and the staff is very accommodating.',
                        'is_verified' => 1,
                        'status' => 'approved',
                        'is_active' => 1,
                        'version' => 1,
                    ]);
                }
            }
        }
    }
}
