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
        if (!Schema::hasTable('contact_inquiries')) {
            Schema::create('contact_inquiries', function (Blueprint $table) {
                $table->id();
                $table->string('name', 150);
                $table->string('email', 150)->index();
                $table->string('phone', 30)->index();
                $table->string('user_type', 50)->default('tenant')->index();
                $table->string('city', 150)->nullable()->index();
                $table->text('message');
                $table->enum('status', ['new', 'in_progress', 'resolved', 'archived'])->default('new')->index();
                $table->text('admin_notes')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_inquiries');
    }
};
