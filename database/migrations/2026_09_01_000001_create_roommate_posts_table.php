<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roommate_posts', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Owner / poster
            $table->string('user_id', 36)->index(); // FK to users (UUID)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Core post metadata
            $table->string('title', 200);
            $table->string('slug', 220)->unique()->index();
            $table->enum('post_type', ['looking_for_room', 'have_room'])->default('looking_for_room')
                  ->comment('looking_for_room = I need a flat/roommate | have_room = I have a room/flat to share');

            // Location
            $table->string('city', 100)->index();
            $table->string('locality', 150)->nullable();
            $table->string('full_address', 400)->nullable()->comment('Shown only to logged-in users');

            // About the poster
            $table->string('poster_name', 120);
            $table->unsignedTinyInteger('poster_age')->nullable();
            $table->enum('poster_gender', ['male', 'female', 'other'])->nullable();
            $table->string('profession', 120)->nullable()->comment('e.g. Software Engineer, MBA Student');
            $table->enum('occupation_type', ['student', 'working_professional', 'any'])->default('any');

            // Room preferences
            $table->enum('gender_preference', ['male', 'female', 'any'])->default('any')->index();
            $table->enum('bhk_type', ['single_room', '1bhk', '2bhk', '3bhk', 'studio', 'any'])->default('any');
            $table->enum('furnishing', ['furnished', 'semi_furnished', 'unfurnished', 'any'])->default('any');
            $table->unsignedInteger('budget_min')->nullable()->comment('Monthly rent in INR');
            $table->unsignedInteger('budget_max')->nullable()->comment('Monthly rent in INR');
            $table->date('move_in_date')->nullable();
            $table->unsignedTinyInteger('preferred_duration_months')->nullable()->comment('Min expected stay duration');

            // Lifestyle & habits (JSON flags)
            $table->json('lifestyle')->nullable()
                  ->comment('Keys: veg, smoker, pets_allowed, early_bird, party_friendly, night_owl, gym, couple_friendly');

            // Description & contact
            $table->text('description')->nullable();
            $table->string('contact_phone', 20)->nullable()->comment('Shown only to logged-in users');
            $table->string('contact_whatsapp', 20)->nullable();
            $table->boolean('contact_visible_to_all')->default(false)
                  ->comment('If false, contact only shown to logged-in users');

            // Avatar / photo of poster (optional)
            $table->string('poster_avatar_url', 600)->nullable();

            // Status & moderation
            $table->enum('status', ['active', 'filled', 'expired', 'pending_review', 'rejected'])
                  ->default('active')->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamp('expires_at')->nullable()
                  ->comment('Auto-expire posts after 30 days if not renewed');
            $table->unsignedInteger('view_count')->default(0);

            // Audit
            $table->unsignedInteger('version')->default(1);
            $table->timestamps();
            $table->softDeletes();

            // Composite indexes for listing queries
            $table->index(['city', 'status', 'is_active', 'created_at']);
            $table->index(['post_type', 'gender_preference', 'city', 'is_active']);
            $table->index(['status', 'is_active', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roommate_posts');
    }
};
