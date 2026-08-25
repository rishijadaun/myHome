<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            if (!Schema::hasColumn('properties', 'ad_type')) {
                $table->string('ad_type', 20)->default('rent')->after('property_type_id')->index();
            }
            if (!Schema::hasColumn('properties', 'property_category')) {
                $table->string('property_category', 50)->default('residential')->after('ad_type')->index();
            }
            if (!Schema::hasColumn('properties', 'expected_price')) {
                $table->decimal('expected_price', 15, 2)->nullable()->after('monthly_rent')->index();
            }
            if (!Schema::hasColumn('properties', 'booking_token_amount')) {
                $table->decimal('booking_token_amount', 15, 2)->nullable()->after('expected_price');
            }
            if (!Schema::hasColumn('properties', 'price_negotiable')) {
                $table->boolean('price_negotiable')->default(false)->after('booking_token_amount');
            }
            if (!Schema::hasColumn('properties', 'ownership_type')) {
                $table->string('ownership_type', 50)->nullable()->after('price_negotiable');
            }
            if (!Schema::hasColumn('properties', 'possession_status')) {
                $table->string('possession_status', 50)->nullable()->after('ownership_type');
            }
            if (!Schema::hasColumn('properties', 'carpet_area_sqft')) {
                $table->integer('carpet_area_sqft')->nullable()->after('possession_status');
            }
            if (!Schema::hasColumn('properties', 'bhk_type')) {
                $table->string('bhk_type', 50)->nullable()->after('carpet_area_sqft');
            }
            if (!Schema::hasColumn('properties', 'furnishing_status')) {
                $table->string('furnishing_status', 50)->nullable()->after('bhk_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $columns = [
                'ad_type',
                'property_category',
                'expected_price',
                'booking_token_amount',
                'price_negotiable',
                'ownership_type',
                'possession_status',
                'carpet_area_sqft',
                'bhk_type',
                'furnishing_status',
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('properties', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
