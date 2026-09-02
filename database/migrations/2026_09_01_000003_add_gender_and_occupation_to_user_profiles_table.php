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
        Schema::table('user_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('user_profiles', 'gender')) {
                $table->string('gender', 30)->nullable()->after('avatar_url');
            }
            if (!Schema::hasColumn('user_profiles', 'occupation')) {
                $table->string('occupation', 150)->nullable()->after('date_of_birth');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('user_profiles', 'gender')) {
                $table->dropColumn('gender');
            }
            if (Schema::hasColumn('user_profiles', 'occupation')) {
                $table->dropColumn('occupation');
            }
        });
    }
};
