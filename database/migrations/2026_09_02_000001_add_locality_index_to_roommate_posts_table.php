<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roommate_posts', function (Blueprint $table) {
            $table->index('locality');
            $table->index(['locality', 'city', 'status', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::table('roommate_posts', function (Blueprint $table) {
            $table->dropIndex(['locality']);
            $table->dropIndex(['locality', 'city', 'status', 'is_active']);
        });
    }
};
