<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roommate_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('roommate_post_id')->index();
            $table->string('sender_id', 36)->index();
            $table->string('receiver_id', 36)->index();
            $table->string('sender_name', 150);
            $table->string('sender_email', 150)->nullable();
            $table->string('sender_phone', 30)->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false)->index();
            $table->enum('moderation_status', ['passed', 'flagged', 'blocked'])->default('passed');
            $table->timestamps();

            $table->foreign('roommate_post_id')->references('id')->on('roommate_posts')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roommate_messages');
    }
};
