<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to add high-performance database indexes.
     */
    public function up(): void
    {
        // 1. Properties Composite Indexes
        if (Schema::hasTable('properties')) {
            Schema::table('properties', function (Blueprint $table) {
                $this->addIndexSafely('properties', ['status', 'verification_status', 'is_active', 'created_at'], 'idx_prop_search_perf', $table);
                $this->addIndexSafely('properties', ['city_id', 'status', 'verification_status'], 'idx_prop_city_status_ver', $table);
                $this->addIndexSafely('properties', ['monthly_rent', 'gender_preference'], 'idx_prop_rent_gender', $table);
                $this->addIndexSafely('properties', ['tag'], 'idx_prop_tag', $table);
            });
        }

        // 2. Bookings Indexes
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                $this->addIndexSafely('bookings', ['broker_id', 'booking_status', 'created_at'], 'idx_bk_broker_status_date', $table);
                $this->addIndexSafely('bookings', ['user_id', 'booking_status'], 'idx_bk_user_status_opt', $table);
            });
        }

        // 3. User Profiles Index
        if (Schema::hasTable('user_profiles')) {
            Schema::table('user_profiles', function (Blueprint $table) {
                $this->addIndexSafely('user_profiles', ['first_name', 'last_name'], 'idx_up_names', $table);
                $this->addIndexSafely('user_profiles', ['full_name'], 'idx_up_full_name', $table);
            });
        }

        // 4. Reviews Index
        if (Schema::hasTable('reviews')) {
            Schema::table('reviews', function (Blueprint $table) {
                $this->addIndexSafely('reviews', ['property_id', 'status', 'is_active'], 'idx_rev_prop_status_active', $table);
            });
        }
    }

    /**
     * Helper to safely add an index if it does not already exist.
     */
    private function addIndexSafely(string $tableName, array $columns, string $indexName, Blueprint $blueprint): void
    {
        try {
            $indexes = DB::select("SHOW INDEX FROM `{$tableName}` WHERE Key_name = ?", [$indexName]);
            if (empty($indexes)) {
                $blueprint->index($columns, $indexName);
            }
        } catch (\Throwable $e) {
            // Silently continue if database driver does not support raw SHOW INDEX
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('properties')) {
            Schema::table('properties', function (Blueprint $table) {
                $table->dropIndex('idx_prop_search_perf');
                $table->dropIndex('idx_prop_city_status_ver');
                $table->dropIndex('idx_prop_rent_gender');
                $table->dropIndex('idx_prop_tag');
            });
        }

        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropIndex('idx_bk_broker_status_date');
                $table->dropIndex('idx_bk_user_status_opt');
            });
        }

        if (Schema::hasTable('user_profiles')) {
            Schema::table('user_profiles', function (Blueprint $table) {
                $table->dropIndex('idx_up_names');
                $table->dropIndex('idx_up_full_name');
            });
        }

        if (Schema::hasTable('reviews')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->dropIndex('idx_rev_prop_status_active');
            });
        }
    }
};
