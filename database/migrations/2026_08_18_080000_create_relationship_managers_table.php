<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('relationship_managers')) {
            Schema::create('relationship_managers', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name', 120);
                $table->string('email', 150)->unique();
                $table->string('phone', 30);
                $table->string('whatsapp_number', 30)->nullable();
                $table->string('designation', 120)->default('Partner Relationship Manager');
                $table->string('zone', 100)->default('North Zone (Delhi NCR)');
                $table->string('city_coverage', 255)->nullable();
                $table->string('working_hours', 100)->default('Mon - Sat: 9:00 AM - 7:00 PM');
                $table->string('avatar_url', 500)->nullable();
                $table->boolean('is_active')->default(true)->index();
                $table->boolean('is_default')->default(false);
                $table->unsignedInteger('version')->default(1);
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Add relationship_manager_id to users table if not present
        if (!Schema::hasColumn('users', 'relationship_manager_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->uuid('relationship_manager_id')->nullable()->after('status')->index();
            });
        }

        // Seed initial Regional Relationship Managers
        $initialRMs = [
            [
                'id' => (string) Str::uuid(),
                'name' => 'Ananya Sengupta',
                'email' => 'ananya.sengupta@staynest.com',
                'phone' => '+91 98765 43210',
                'whatsapp_number' => '919876543210',
                'designation' => 'Senior Key Account Lead',
                'zone' => 'North Zone (Noida & Delhi NCR)',
                'city_coverage' => 'Noida, Greater Noida, Delhi, Ghaziabad, Gurgaon',
                'working_hours' => 'Mon - Sat: 9:00 AM - 7:30 PM',
                'avatar_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=300&q=80',
                'is_active' => true,
                'is_default' => true,
                'version' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Rohan Mehta',
                'email' => 'rohan.mehta@staynest.com',
                'phone' => '+91 98765 43212',
                'whatsapp_number' => '919876543212',
                'designation' => 'Regional Growth Specialist',
                'zone' => 'South Zone (Bangalore & Hyderabad)',
                'city_coverage' => 'Bangalore, Hyderabad, Chennai, Kochi',
                'working_hours' => 'Mon - Sat: 9:30 AM - 7:00 PM',
                'avatar_url' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=300&q=80',
                'is_active' => true,
                'is_default' => false,
                'version' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Pooja Sharma',
                'email' => 'pooja.sharma@staynest.com',
                'phone' => '+91 98765 43213',
                'whatsapp_number' => '919876543213',
                'designation' => 'Partner Success Manager',
                'zone' => 'West Zone (Mumbai & Pune)',
                'city_coverage' => 'Mumbai, Pune, Ahmedabad, Surat',
                'working_hours' => 'Mon - Sat: 9:00 AM - 6:30 PM',
                'avatar_url' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=300&q=80',
                'is_active' => true,
                'is_default' => false,
                'version' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => 'Karan Kapoor',
                'email' => 'karan.kapoor@staynest.com',
                'phone' => '+91 98765 43214',
                'whatsapp_number' => '919876543214',
                'designation' => 'Priority Partner Concierge Lead',
                'zone' => 'VIP Pan-India Co-Living',
                'city_coverage' => 'All Tier-1 & Metro Clusters',
                'working_hours' => 'Mon - Sun: 8:30 AM - 8:30 PM',
                'avatar_url' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=300&q=80',
                'is_active' => true,
                'is_default' => false,
                'version' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($initialRMs as $rm) {
            $exists = DB::table('relationship_managers')->where('email', $rm['email'])->exists();
            if (!$exists) {
                DB::table('relationship_managers')->insert($rm);
            }
        }

        // Assign default RM (Ananya) to existing partner brokers who don't have an RM yet
        $defaultRm = DB::table('relationship_managers')->where('is_default', 1)->first();
        if ($defaultRm) {
            DB::table('users')
                ->whereNull('relationship_manager_id')
                ->whereExists(function ($q) {
                    $q->select(DB::raw(1))
                        ->from('user_roles')
                        ->join('roles', 'user_roles.role_id', '=', 'roles.id')
                        ->whereColumn('user_roles.user_id', 'users.id')
                        ->where('roles.slug', 'broker');
                })
                ->update(['relationship_manager_id' => $defaultRm->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'relationship_manager_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('relationship_manager_id');
            });
        }

        Schema::dropIfExists('relationship_managers');
    }
};
