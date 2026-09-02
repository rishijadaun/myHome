<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\RoommatePost;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RoommateSeeder extends Seeder
{
    public function run(): void
    {
        $tenantRole = Role::firstOrCreate(
            ['slug' => 'tenant'],
            ['id' => (string) Str::uuid(), 'name' => 'Tenant', 'level' => 10, 'is_system' => true, 'is_active' => true]
        );

        $flatmates = [
            [
                'email'         => 'aarav.mehta@example.com',
                'phone'         => '+919876511001',
                'first_name'    => 'Aarav',
                'last_name'     => 'Mehta',
                'gender'        => 'Male',
                'dob'           => '1999-06-15',
                'occupation'    => 'Software Engineer @ Microsoft',
                'avatar_url'    => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=300&auto=format&fit=crop&q=80',
                'city'          => 'Noida',
                'locality'      => 'Sector 62, Near Metro Station',
                'bhk_type'      => '2bhk',
                'furnishing'    => 'furnished',
                'budget_max'    => 13500,
                'gender_pref'   => 'male',
                'stay_duration' => 6,
                'move_in_date'  => Carbon::now()->addDays(5)->format('Y-m-d'),
                'lifestyle'     => [
                    'veg'         => 1,
                    'no_smoking'  => 1,
                    'wfh'         => 1,
                    'gym_person'  => 1,
                    'early_bird'  => 1,
                ],
                'amenities'     => [
                    'wifi'            => 1,
                    'ac'              => 1,
                    'fridge'          => 1,
                    'washing_machine' => 1,
                    'power_backup'    => 1,
                    'cook_maid'       => 1,
                    'ro_water'        => 1,
                    'balcony'         => 1,
                ],
                'description'   => "Private master bedroom with attached washroom available in a spacious 2BHK flat in Sector 62, Noida. Flat is fully loaded with high-speed 300 Mbps Wi-Fi, air conditioning, automatic washing machine, refrigerator, and dedicated cook & maid service. Looking for a clean, non-smoking male working professional or student. Just 5 mins walk to Sector 62 metro station.",
            ],
            [
                'email'         => 'sneha.mukherjee@example.com',
                'phone'         => '+919876511002',
                'first_name'    => 'Sneha',
                'last_name'     => 'Mukherjee',
                'gender'        => 'Female',
                'dob'           => '2000-03-22',
                'occupation'    => 'Senior UI/UX Designer',
                'avatar_url'    => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=300&auto=format&fit=crop&q=80',
                'city'          => 'Bangalore',
                'locality'      => 'HSR Layout, Sector 2',
                'bhk_type'      => '3bhk',
                'furnishing'    => 'furnished',
                'budget_max'    => 16000,
                'gender_pref'   => 'female',
                'stay_duration' => 12,
                'move_in_date'  => Carbon::now()->addDays(3)->format('Y-m-d'),
                'lifestyle'     => [
                    'non_veg'        => 1,
                    'no_smoking'     => 1,
                    'pets_ok'        => 1,
                    'wfh'            => 1,
                    'party_friendly' => 1,
                ],
                'amenities'     => [
                    'wifi'            => 1,
                    'ac'              => 1,
                    'kitchen'         => 1,
                    'fridge'          => 1,
                    'washing_machine' => 1,
                    'geyser'          => 1,
                    'power_backup'    => 1,
                    'balcony'         => 1,
                ],
                'description'   => "Sunlit private room with private balcony in a chic 3BHK flat at HSR Layout Sector 2. Fully furnished with study desk, ergonomic chair, wardrobe, and queen bed. All modern appliances available. Looking for a friendly female flatmate who values hygiene and quiet work hours during weekdays. Pet friendly flat!",
            ],
            [
                'email'         => 'rohan.verma@example.com',
                'phone'         => '+919876511003',
                'first_name'    => 'Rohan',
                'last_name'     => 'Verma',
                'gender'        => 'Male',
                'dob'           => '1997-11-10',
                'occupation'    => 'Product Manager @ Fintech',
                'avatar_url'    => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&auto=format&fit=crop&q=80',
                'city'          => 'Gurgaon',
                'locality'      => 'DLF Phase 3 / Cyber City',
                'bhk_type'      => '2bhk',
                'furnishing'    => 'furnished',
                'budget_max'    => 18500,
                'gender_pref'   => 'any',
                'stay_duration' => 6,
                'move_in_date'  => Carbon::now()->addDays(7)->format('Y-m-d'),
                'lifestyle'     => [
                    'non_veg'    => 1,
                    'no_smoking' => 1,
                    'gym_person' => 1,
                    'early_bird' => 1,
                    'wfh'        => 1,
                ],
                'amenities'     => [
                    'wifi'         => 1,
                    'ac'           => 1,
                    'tv'           => 1,
                    'parking'      => 1,
                    'cook_maid'    => 1,
                    'power_backup' => 1,
                    'ro_water'     => 1,
                    'geyser'       => 1,
                ],
                'description'   => "1 room available in a premium gated society in DLF Phase 3, right next to Cyber City & Rapid Metro. Gated community with 24/7 security, clubhouse, gym, and covered car parking. Daily maid for cleaning and cooking. Ideal for IT/Fintech professionals looking for zero commute.",
            ],
            [
                'email'         => 'ananya.iyer@example.com',
                'phone'         => '+919876511004',
                'first_name'    => 'Ananya',
                'last_name'     => 'Iyer',
                'gender'        => 'Female',
                'dob'           => '2001-08-19',
                'occupation'    => 'Data Analyst @ Consulting',
                'avatar_url'    => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=300&auto=format&fit=crop&q=80',
                'city'          => 'Mumbai',
                'locality'      => 'Hiranandani, Powai',
                'bhk_type'      => '2bhk',
                'furnishing'    => 'semi_furnished',
                'budget_max'    => 15000,
                'gender_pref'   => 'female',
                'stay_duration' => 12,
                'move_in_date'  => Carbon::now()->addDays(10)->format('Y-m-d'),
                'lifestyle'     => [
                    'veg'        => 1,
                    'no_smoking' => 1,
                    'no_pets'    => 1,
                    'early_bird' => 1,
                ],
                'amenities'     => [
                    'wifi'            => 1,
                    'kitchen'         => 1,
                    'fridge'          => 1,
                    'washing_machine' => 1,
                    'ro_water'        => 1,
                    'geyser'          => 1,
                ],
                'description'   => "Cozy single bedroom available in Hiranandani Powai. Close to lake and cafes. Vegetarian household with fully functional modular kitchen, water purifier, high-speed internet, and regular cleaning. Looking for a neat and respectful female flatmate.",
            ],
            [
                'email'         => 'kavya.nair@example.com',
                'phone'         => '+919876511005',
                'first_name'    => 'Kavya',
                'last_name'     => 'Nair',
                'gender'        => 'Female',
                'dob'           => '1998-12-05',
                'occupation'    => 'Digital Marketing Lead',
                'avatar_url'    => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=300&auto=format&fit=crop&q=80',
                'city'          => 'Pune',
                'locality'      => 'Baner, Near Highway',
                'bhk_type'      => '3bhk',
                'furnishing'    => 'semi_furnished',
                'budget_max'    => 11500,
                'gender_pref'   => 'female',
                'stay_duration' => 6,
                'move_in_date'  => Carbon::now()->addDays(4)->format('Y-m-d'),
                'lifestyle'     => [
                    'non_veg'    => 1,
                    'no_smoking' => 1,
                    'wfh'        => 1,
                    'pets_ok'    => 1,
                ],
                'amenities'     => [
                    'wifi'         => 1,
                    'parking'      => 1,
                    'balcony'      => 1,
                    'kitchen'      => 1,
                    'power_backup' => 1,
                    'ro_water'     => 1,
                ],
                'description'   => "Private room in a 3BHK flat at Baner, Pune. Society has a garden, security, and dedicated bike parking. Very close to Balewadi High Street and Hinjewadi IT park road. Female flatmate required.",
            ],
            [
                'email'         => 'vikram.roy@example.com',
                'phone'         => '+919876511006',
                'first_name'    => 'Vikramaditya',
                'last_name'     => 'Roy',
                'gender'        => 'Male',
                'dob'           => '1996-04-12',
                'occupation'    => 'Cloud Architect @ Oracle',
                'avatar_url'    => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=300&auto=format&fit=crop&q=80',
                'city'          => 'Hyderabad',
                'locality'      => 'Gachibowli, Near Financial District',
                'bhk_type'      => '3bhk',
                'furnishing'    => 'furnished',
                'budget_max'    => 12500,
                'gender_pref'   => 'any',
                'stay_duration' => 12,
                'move_in_date'  => Carbon::now()->addDays(2)->format('Y-m-d'),
                'lifestyle'     => [
                    'non_veg'        => 1,
                    'smoking_ok'     => 1,
                    'gym_person'     => 1,
                    'night_owl'      => 1,
                    'party_friendly' => 1,
                ],
                'amenities'     => [
                    'wifi'            => 1,
                    'ac'              => 1,
                    'tv'              => 1,
                    'fridge'          => 1,
                    'washing_machine' => 1,
                    'parking'         => 1,
                    'power_backup'    => 1,
                ],
                'description'   => "1 room with attached washroom in a high-rise gated society in Gachibowli. 5 minutes from Financial District. Fully furnished flat with Smart TV, split AC, 500 Mbps broadband, and 100% power backup. Chill environment, looking for like-minded flatmate.",
            ],
        ];

        foreach ($flatmates as $data) {
            $user = User::where('email', $data['email'])->first();
            if (!$user) {
                $user = User::create([
                    'id'            => (string) Str::uuid(),
                    'email'         => $data['email'],
                    'phone'         => $data['phone'],
                    'password_hash' => Hash::make('password123'),
                    'status'        => 'active',
                    'is_active'     => true,
                    'email_verified_at' => now(),
                    'phone_verified_at' => now(),
                ]);

                UserRole::create([
                    'id'         => (string) Str::uuid(),
                    'user_id'    => $user->id,
                    'role_id'    => $tenantRole->id,
                    'is_primary' => true,
                    'is_active'  => true,
                ]);
            }

            $profile = UserProfile::firstOrNew(['user_id' => $user->id]);
            $profile->first_name    = $data['first_name'];
            $profile->last_name     = $data['last_name'];
            $profile->gender        = $data['gender'];
            $profile->date_of_birth = $data['dob'];
            $profile->occupation    = $data['occupation'];
            $profile->avatar_url    = $data['avatar_url'];
            $profile->is_active     = true;
            $profile->save();

            $age = $profile->age;
            $bhkLabels = RoommatePost::bhkOptions();
            $bhkLabel = $bhkLabels[$data['bhk_type']] ?? 'Room';
            $title = "{$bhkLabel} in {$data['locality']}, {$data['city']}";

            RoommatePost::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'title'                     => $title,
                    'slug'                      => RoommatePost::generateSlug($title, $data['city']),
                    'post_type'                 => 'have_room',
                    'city'                      => $data['city'],
                    'locality'                  => $data['locality'],
                    'full_address'              => $data['locality'] . ', ' . $data['city'],
                    'poster_name'               => $data['first_name'] . ' ' . $data['last_name'],
                    'poster_age'                => $age,
                    'poster_gender'             => strtolower($data['gender']),
                    'profession'                => $data['occupation'],
                    'occupation_type'           => 'working_professional',
                    'gender_preference'         => $data['gender_pref'],
                    'bhk_type'                  => $data['bhk_type'],
                    'furnishing'                => $data['furnishing'],
                    'budget_min'                => null,
                    'budget_max'                => $data['budget_max'],
                    'move_in_date'              => $data['move_in_date'],
                    'preferred_duration_months' => $data['stay_duration'],
                    'lifestyle'                 => $data['lifestyle'],
                    'amenities'                 => $data['amenities'],
                    'description'               => $data['description'],
                    'contact_phone'             => $data['phone'],
                    'contact_whatsapp'          => $data['phone'],
                    'contact_visible_to_all'    => false,
                    'poster_avatar_url'         => $data['avatar_url'],
                    'status'                    => 'active',
                    'is_active'                 => true,
                    'expires_at'                => now()->addDays(30),
                ]
            );
        }
    }
}
