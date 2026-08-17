<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\City;
use App\Models\Notification;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\Role;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminDashboardSeeder extends Seeder
{
    public function run(): void
    {
        $brokerRole = Role::firstOrCreate(['slug' => 'broker'], ['id' => (string) Str::uuid(), 'name' => 'Broker', 'level' => 70, 'is_system' => true, 'is_active' => true]);
        $tenantRole = Role::firstOrCreate(['slug' => 'tenant'], ['id' => (string) Str::uuid(), 'name' => 'Tenant', 'level' => 10, 'is_system' => true, 'is_active' => true]);

        // 1. Ensure sample brokers exist
        $brokersData = [
            [
                'email' => 'neha.patel@staynest.com',
                'phone' => '+919876500001',
                'first_name' => 'Neha',
                'last_name' => 'Patel',
                'status' => 'pending_verification',
                'kyc' => null,
            ],
            [
                'email' => 'rajesh.sharma@staynest.com',
                'phone' => '+919876500002',
                'first_name' => 'Rajesh',
                'last_name' => 'Sharma',
                'status' => 'pending_verification',
                'kyc' => null,
            ],
            [
                'email' => 'anil.kumar@staynest.com',
                'phone' => '+919876500003',
                'first_name' => 'Anil',
                'last_name' => 'Kumar',
                'status' => 'active',
                'kyc' => now(),
            ],
            [
                'email' => 'vikram@broker.com',
                'phone' => '+919876543211',
                'first_name' => 'Vikram',
                'last_name' => 'Singh',
                'status' => 'active',
                'kyc' => now(),
            ],
        ];

        $brokerModels = [];
        foreach ($brokersData as $b) {
            $user = User::where('email', $b['email'])->first();
            if (!$user) {
                $user = User::create([
                    'id' => (string) Str::uuid(),
                    'email' => $b['email'],
                    'phone' => $b['phone'],
                    'password_hash' => Hash::make('broker123'),
                    'status' => $b['status'],
                    'is_active' => true,
                    'kyc_verified_at' => $b['kyc'],
                    'created_at' => now()->subDays(rand(2, 45)),
                ]);

                UserProfile::create([
                    'user_id' => $user->id,
                    'first_name' => $b['first_name'],
                    'last_name' => $b['last_name'],
                    'full_name' => $b['first_name'] . ' ' . $b['last_name'],
                    'is_active' => true,
                ]);

                UserRole::create([
                    'id' => (string) Str::uuid(),
                    'user_id' => $user->id,
                    'role_id' => $brokerRole->id,
                    'is_primary' => true,
                    'is_active' => true,
                ]);
            }
            $brokerModels[] = $user;
        }

        // 2. Ensure sample tenants exist
        $tenantsData = [
            ['name' => 'Rahul Sharma', 'email' => 'rahul.tenant@gmail.com', 'phone' => '+919811223344'],
            ['name' => 'Priya Patel', 'email' => 'priya.tenant@gmail.com', 'phone' => '+919822334455'],
            ['name' => 'Amit Verma', 'email' => 'amit.tenant@gmail.com', 'phone' => '+919833445566'],
            ['name' => 'Sneha Reddy', 'email' => 'sneha.tenant@gmail.com', 'phone' => '+919844556677'],
            ['name' => 'Rohan Mehta', 'email' => 'rohan.tenant@gmail.com', 'phone' => '+919855667788'],
            ['name' => 'Ananya Joshi', 'email' => 'ananya.tenant@gmail.com', 'phone' => '+919866778899'],
        ];

        $tenantModels = [];
        foreach ($tenantsData as $t) {
            $user = User::where('email', $t['email'])->first();
            if (!$user) {
                $names = explode(' ', $t['name']);
                $user = User::create([
                    'id' => (string) Str::uuid(),
                    'email' => $t['email'],
                    'phone' => $t['phone'],
                    'password_hash' => Hash::make('tenant123'),
                    'status' => 'active',
                    'is_active' => true,
                    'created_at' => now()->subDays(rand(5, 60)),
                ]);

                UserProfile::create([
                    'user_id' => $user->id,
                    'first_name' => $names[0],
                    'last_name' => $names[1] ?? '',
                    'full_name' => $t['name'],
                    'is_active' => true,
                ]);

                UserRole::create([
                    'id' => (string) Str::uuid(),
                    'user_id' => $user->id,
                    'role_id' => $tenantRole->id,
                    'is_primary' => true,
                    'is_active' => true,
                ]);
            }
            $tenantModels[] = $user;
        }

        // 3. Ensure properties with blocks, floors, rooms, beds
        $properties = Property::all();
        $roomTypes = DB::table('room_types')->get();

        foreach ($properties as $prop) {
            // Check if block exists
            $block = DB::table('blocks')->where('property_id', $prop->id)->first();
            if (!$block) {
                $blockId = (string) Str::uuid();
                DB::table('blocks')->insert([
                    'id' => $blockId,
                    'property_id' => $prop->id,
                    'name' => 'Main Block A',
                    'is_active' => 1,
                    'version' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

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

                for ($r = 1; $r <= 3; $r++) {
                    $roomId = (string) Str::uuid();
                    $rType = $roomTypes[$r % count($roomTypes)] ?? null;
                    DB::table('rooms')->insert([
                        'id' => $roomId,
                        'floor_id' => $floorId,
                        'room_type_id' => $rType ? $rType->id : 'c9a9eeb7-8dab-11f1-a4cf-1062e5a5cd6c',
                        'room_number' => '10' . $r,
                        'total_beds' => 2,
                        'available_beds' => 1,
                        'monthly_rent' => $prop->monthly_rent > 0 ? $prop->monthly_rent : 8500,
                        'security_deposit' => 15000,
                        'attached_bathroom' => 1,
                        'ac_available' => 1,
                        'balcony' => 1,
                        'status' => 'available',
                        'is_active' => 1,
                        'version' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    for ($b = 1; $b <= 2; $b++) {
                        DB::table('beds')->insert([
                            'id' => (string) Str::uuid(),
                            'room_id' => $roomId,
                            'bed_number' => '10' . $r . '-' . ($b == 1 ? 'A' : 'B'),
                            'bed_type' => 'single',
                            'status' => 'available',
                            'is_active' => 1,
                            'version' => 1,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }

        // 4. Seed sample bookings if less than 8
        if (Booking::count() < 8) {
            $allRooms = DB::table('rooms')->get();
            $allBeds = DB::table('beds')->get();

            $statuses = ['confirmed', 'confirmed', 'pending', 'confirmed', 'completed', 'cancelled'];
            $paymentStatuses = ['paid', 'paid', 'pending', 'paid', 'paid', 'refunded'];

            for ($i = 0; $i < 15; $i++) {
                $tenant = $tenantModels[$i % count($tenantModels)];
                $prop = $properties[$i % count($properties)];
                $room = $allRooms[$i % count($allRooms)];
                $bed = DB::table('beds')->where('room_id', $room->id)->first() ?? $allBeds[0];
                $broker = $brokerModels[$i % count($brokerModels)];
                $status = $statuses[$i % count($statuses)];
                $pStatus = $paymentStatuses[$i % count($paymentStatuses)];

                $rent = (float) ($prop->monthly_rent > 0 ? $prop->monthly_rent : 9500);
                $deposit = 15000;
                $total = $rent + $deposit;
                $paid = $pStatus === 'paid' ? $total : ($pStatus === 'pending' ? 0 : 5000);
                $createdDaysAgo = rand(0, 25);
                $createdAt = Carbon::now()->subDays($createdDaysAgo)->subHours(rand(1, 18));

                Booking::create([
                    'id' => (string) Str::uuid(),
                    'booking_id' => 'BK-' . strtoupper(Str::random(7)),
                    'user_id' => $tenant->id,
                    'property_id' => $prop->id,
                    'room_id' => $room->id,
                    'bed_id' => $bed->id,
                    'broker_id' => $broker->id,
                    'check_in_date' => Carbon::now()->addDays(rand(1, 14))->format('Y-m-d'),
                    'check_out_date' => Carbon::now()->addMonths(11)->format('Y-m-d'),
                    'duration_months' => 11,
                    'base_rent' => $rent,
                    'security_deposit' => $deposit,
                    'maintenance_charges' => 500,
                    'discount_amount' => 0,
                    'total_amount' => $total,
                    'paid_amount' => $paid,
                    'payment_status' => $pStatus,
                    'booking_status' => $status,
                    'broker_approval' => $status === 'confirmed' ? 'approved' : 'pending',
                    'is_active' => true,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }

        // 5. Ensure notifications
        if (Notification::count() < 5) {
            $adminUser = User::where('email', 'admin@staynest.com')->first();
            if ($adminUser) {
                Notification::create([
                    'id' => (string) Str::uuid(),
                    'user_id' => $adminUser->id,
                    'user_type' => 'admin',
                    'title' => 'New Broker Registration',
                    'message' => 'Broker Neha Patel submitted KYC documents for verification.',
                    'type' => 'broker_kyc',
                    'is_read' => 0,
                    'action_url' => '/admin/brokers',
                    'created_at' => now()->subMinutes(15),
                ]);

                Notification::create([
                    'id' => (string) Str::uuid(),
                    'user_id' => $adminUser->id,
                    'user_type' => 'admin',
                    'title' => 'New Property Listed for Approval',
                    'message' => 'Sunrise Premium PG has been submitted by Vikram Singh.',
                    'type' => 'property_submission',
                    'is_read' => 0,
                    'action_url' => '/admin/pgs',
                    'created_at' => now()->subHours(2),
                ]);
            }
        }
    }
}
