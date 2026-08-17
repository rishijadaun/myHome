<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class IndianCitiesSeeder extends Seeder
{
    public function run(): void
    {
        $country = DB::table('countries')->where('code', 'IND')->orWhere('name', 'India')->first();
        $countryId = $country ? $country->id : 'c9ac8db1-8dab-11f1-a4cf-1062e5a5cd6c';

        // States & Cities Mapping
        $statesAndCities = [
            'Delhi' => [
                'code' => 'DL',
                'cities' => [
                    ['name' => 'New Delhi', 'is_metro' => 1, 'is_tier1' => 1],
                    ['name' => 'Delhi', 'is_metro' => 1, 'is_tier1' => 1],
                ]
            ],
            'Uttar Pradesh' => [
                'code' => 'UP',
                'cities' => [
                    ['name' => 'Noida', 'is_metro' => 1, 'is_tier1' => 1],
                    ['name' => 'Greater Noida', 'is_metro' => 1, 'is_tier1' => 0],
                    ['name' => 'Ghaziabad', 'is_metro' => 1, 'is_tier1' => 0],
                    ['name' => 'Lucknow', 'is_metro' => 0, 'is_tier1' => 1],
                    ['name' => 'Kanpur', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Varanasi', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Agra', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Prayagraj', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Meerut', 'is_metro' => 0, 'is_tier1' => 0],
                ]
            ],
            'Haryana' => [
                'code' => 'HR',
                'cities' => [
                    ['name' => 'Gurugram (Gurgaon)', 'is_metro' => 1, 'is_tier1' => 1],
                    ['name' => 'Faridabad', 'is_metro' => 1, 'is_tier1' => 0],
                    ['name' => 'Panchkula', 'is_metro' => 0, 'is_tier1' => 0],
                ]
            ],
            'Karnataka' => [
                'code' => 'KA',
                'cities' => [
                    ['name' => 'Bangalore (Bengaluru)', 'is_metro' => 1, 'is_tier1' => 1],
                    ['name' => 'Mysore (Mysuru)', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Mangalore (Mangaluru)', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Hubli-Dharwad', 'is_metro' => 0, 'is_tier1' => 0],
                ]
            ],
            'Maharashtra' => [
                'code' => 'MH',
                'cities' => [
                    ['name' => 'Mumbai', 'is_metro' => 1, 'is_tier1' => 1],
                    ['name' => 'Pune', 'is_metro' => 1, 'is_tier1' => 1],
                    ['name' => 'Navi Mumbai', 'is_metro' => 1, 'is_tier1' => 1],
                    ['name' => 'Thane', 'is_metro' => 1, 'is_tier1' => 1],
                    ['name' => 'Nagpur', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Nashik', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Aurangabad (Chhatrapati Sambhajinagar)', 'is_metro' => 0, 'is_tier1' => 0],
                ]
            ],
            'Telangana' => [
                'code' => 'TS',
                'cities' => [
                    ['name' => 'Hyderabad', 'is_metro' => 1, 'is_tier1' => 1],
                    ['name' => 'Secunderabad', 'is_metro' => 1, 'is_tier1' => 1],
                    ['name' => 'Warangal', 'is_metro' => 0, 'is_tier1' => 0],
                ]
            ],
            'Tamil Nadu' => [
                'code' => 'TN',
                'cities' => [
                    ['name' => 'Chennai', 'is_metro' => 1, 'is_tier1' => 1],
                    ['name' => 'Coimbatore', 'is_metro' => 0, 'is_tier1' => 1],
                    ['name' => 'Madurai', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Tiruchirappalli', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Salem', 'is_metro' => 0, 'is_tier1' => 0],
                ]
            ],
            'West Bengal' => [
                'code' => 'WB',
                'cities' => [
                    ['name' => 'Kolkata', 'is_metro' => 1, 'is_tier1' => 1],
                    ['name' => 'Howrah', 'is_metro' => 1, 'is_tier1' => 0],
                    ['name' => 'Durgapur', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Siliguri', 'is_metro' => 0, 'is_tier1' => 0],
                ]
            ],
            'Gujarat' => [
                'code' => 'GJ',
                'cities' => [
                    ['name' => 'Ahmedabad', 'is_metro' => 1, 'is_tier1' => 1],
                    ['name' => 'Surat', 'is_metro' => 0, 'is_tier1' => 1],
                    ['name' => 'Vadodara', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Rajkot', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Gandhinagar', 'is_metro' => 0, 'is_tier1' => 0],
                ]
            ],
            'Rajasthan' => [
                'code' => 'RJ',
                'cities' => [
                    ['name' => 'Jaipur', 'is_metro' => 0, 'is_tier1' => 1],
                    ['name' => 'Kota', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Jodhpur', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Udaipur', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Ajmer', 'is_metro' => 0, 'is_tier1' => 0],
                ]
            ],
            'Madhya Pradesh' => [
                'code' => 'MP',
                'cities' => [
                    ['name' => 'Indore', 'is_metro' => 0, 'is_tier1' => 1],
                    ['name' => 'Bhopal', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Gwalior', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Jabalpur', 'is_metro' => 0, 'is_tier1' => 0],
                ]
            ],
            'Punjab' => [
                'code' => 'PB',
                'cities' => [
                    ['name' => 'Chandigarh', 'is_metro' => 0, 'is_tier1' => 1],
                    ['name' => 'Mohali', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Ludhiana', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Amritsar', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Jalandhar', 'is_metro' => 0, 'is_tier1' => 0],
                ]
            ],
            'Kerala' => [
                'code' => 'KL',
                'cities' => [
                    ['name' => 'Kochi (Cochin)', 'is_metro' => 0, 'is_tier1' => 1],
                    ['name' => 'Thiruvananthapuram', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Kozhikode (Calicut)', 'is_metro' => 0, 'is_tier1' => 0],
                ]
            ],
            'Andhra Pradesh' => [
                'code' => 'AP',
                'cities' => [
                    ['name' => 'Visakhapatnam', 'is_metro' => 0, 'is_tier1' => 1],
                    ['name' => 'Vijayawada', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Guntur', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Tirupati', 'is_metro' => 0, 'is_tier1' => 0],
                ]
            ],
            'Odisha' => [
                'code' => 'OD',
                'cities' => [
                    ['name' => 'Bhubaneswar', 'is_metro' => 0, 'is_tier1' => 1],
                    ['name' => 'Cuttack', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Rourkela', 'is_metro' => 0, 'is_tier1' => 0],
                ]
            ],
            'Bihar' => [
                'code' => 'BR',
                'cities' => [
                    ['name' => 'Patna', 'is_metro' => 0, 'is_tier1' => 1],
                    ['name' => 'Gaya', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Muzaffarpur', 'is_metro' => 0, 'is_tier1' => 0],
                ]
            ],
            'Uttarakhand' => [
                'code' => 'UK',
                'cities' => [
                    ['name' => 'Dehradun', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Haridwar', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Rishikesh', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Roorkee', 'is_metro' => 0, 'is_tier1' => 0],
                ]
            ],
            'Assam' => [
                'code' => 'AS',
                'cities' => [
                    ['name' => 'Guwahati', 'is_metro' => 0, 'is_tier1' => 0],
                ]
            ],
            'Goa' => [
                'code' => 'GA',
                'cities' => [
                    ['name' => 'Panaji (North Goa)', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Margao (South Goa)', 'is_metro' => 0, 'is_tier1' => 0],
                ]
            ],
            'Jharkhand' => [
                'code' => 'JH',
                'cities' => [
                    ['name' => 'Ranchi', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Jamshedpur', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Dhanbad', 'is_metro' => 0, 'is_tier1' => 0],
                ]
            ],
            'Chhattisgarh' => [
                'code' => 'CG',
                'cities' => [
                    ['name' => 'Raipur', 'is_metro' => 0, 'is_tier1' => 0],
                    ['name' => 'Bhilai', 'is_metro' => 0, 'is_tier1' => 0],
                ]
            ],
        ];

        foreach ($statesAndCities as $stateName => $stateData) {
            // Find or create state
            $state = DB::table('states')->where('name', $stateName)->orWhere('code', $stateData['code'])->first();
            if (!$state) {
                $stateId = (string) Str::uuid();
                DB::table('states')->insert([
                    'id' => $stateId,
                    'country_id' => $countryId,
                    'code' => $stateData['code'],
                    'name' => $stateName,
                    'is_active' => 1,
                    'version' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $stateId = $state->id;
            }

            foreach ($stateData['cities'] as $cityInfo) {
                $existing = DB::table('cities')->where('name', $cityInfo['name'])->first();
                if (!$existing) {
                    $slug = Str::slug($cityInfo['name']);
                    DB::table('cities')->insert([
                        'id' => (string) Str::uuid(),
                        'state_id' => $stateId,
                        'name' => $cityInfo['name'],
                        'slug' => $slug,
                        'is_metro' => $cityInfo['is_metro'],
                        'is_tier1' => $cityInfo['is_tier1'],
                        'is_active' => 1,
                        'version' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
