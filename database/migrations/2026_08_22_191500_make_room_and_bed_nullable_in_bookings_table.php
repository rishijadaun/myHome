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
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->char('room_id', 36)->nullable()->change();
                $table->char('bed_id', 36)->nullable()->change();
                $table->string('room_type_name', 100)->nullable()->after('bed_id');
                $table->string('tenant_name', 150)->nullable()->after('user_id');
                $table->string('tenant_phone', 30)->nullable()->after('tenant_name');
                $table->string('tenant_email', 150)->nullable()->after('tenant_phone');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('bookings')) {
            Schema::table('bookings', function (Blueprint $table) {
                if (Schema::hasColumn('bookings', 'room_type_name')) {
                    $table->dropColumn(['room_type_name', 'tenant_name', 'tenant_phone', 'tenant_email']);
                }
            });
        }
    }
};
