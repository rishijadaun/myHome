<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roommate_posts', function (Blueprint $table) {
            if (!Schema::hasColumn('roommate_posts', 'amenities')) {
                $table->json('amenities')->nullable()->after('lifestyle');
            }
        });
    }

    public function down(): void
    {
        Schema::table('roommate_posts', function (Blueprint $table) {
            if (Schema::hasColumn('roommate_posts', 'amenities')) {
                $table->dropColumn('amenities');
            }
        });
    }
};
