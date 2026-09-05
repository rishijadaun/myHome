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
        Schema::create('platform_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->string('group', 50)->default('general')->index();
            $table->string('type', 20)->default('string');
            $table->timestamps();
        });

        // Insert initial default platform settings
        $defaults = [
            // General Settings
            ['key' => 'platform_name', 'value' => 'SpaceSeeks', 'group' => 'general', 'type' => 'string'],
            ['key' => 'support_email', 'value' => 'support@spaceseeks.com', 'group' => 'general', 'type' => 'string'],
            ['key' => 'support_phone', 'value' => '+91 98765 43210', 'group' => 'general', 'type' => 'string'],
            ['key' => 'platform_tagline', 'value' => 'Premium Verified Co-Living & PGs across India', 'group' => 'general', 'type' => 'string'],
            ['key' => 'platform_description', 'value' => 'Making PG and co-living simple, safe, and comfortable with zero brokerage and verified amenities across India.', 'group' => 'general', 'type' => 'string'],
            ['key' => 'primary_city', 'value' => 'Delhi NCR', 'group' => 'general', 'type' => 'string'],
            ['key' => 'currency_symbol', 'value' => '₹', 'group' => 'general', 'type' => 'string'],

            // Booking & Policies
            ['key' => 'notice_period_days', 'value' => '30', 'group' => 'booking', 'type' => 'number'],
            ['key' => 'broker_commission_percentage', 'value' => '10', 'group' => 'booking', 'type' => 'number'],
            ['key' => 'auto_approve_bookings', 'value' => '1', 'group' => 'booking', 'type' => 'boolean'],
            ['key' => 'mandatory_broker_kyc', 'value' => '1', 'group' => 'booking', 'type' => 'boolean'],
            ['key' => 'auto_sms_whatsapp_alerts', 'value' => '1', 'group' => 'booking', 'type' => 'boolean'],
            ['key' => 'enable_guest_inquiry', 'value' => '1', 'group' => 'booking', 'type' => 'boolean'],

            // Razorpay Payment Gateway
            ['key' => 'razorpay_key_id', 'value' => 'rzp_live_9381kdf89241', 'group' => 'payment', 'type' => 'string'],
            ['key' => 'razorpay_key_secret', 'value' => 'sec_live_k89214710928341', 'group' => 'payment', 'type' => 'string'],
            ['key' => 'payment_mode', 'value' => 'test', 'group' => 'payment', 'type' => 'string'],

            // Security & Maintenance
            ['key' => 'maintenance_mode', 'value' => '0', 'group' => 'security', 'type' => 'boolean'],
            ['key' => 'two_factor_auth_required', 'value' => '0', 'group' => 'security', 'type' => 'boolean'],
        ];

        foreach ($defaults as $item) {
            DB::table('platform_settings')->insert([
                'id' => (string) Str::uuid(),
                'key' => $item['key'],
                'value' => $item['value'],
                'group' => $item['group'],
                'type' => $item['type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_settings');
    }
};
