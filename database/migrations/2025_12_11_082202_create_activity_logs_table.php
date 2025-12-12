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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_type'); // created, updated, deleted, status_changed, etc.
            $table->string('loggable_type'); // Model name (Perkara, Personel, etc.)
            $table->unsignedBigInteger('loggable_id'); // Model ID
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('description'); // Short description of activity
            $table->json('old_values')->nullable(); // Old data (for updates)
            $table->json('new_values')->nullable(); // New data
            $table->text('metadata')->nullable(); // Additional context
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index(['loggable_type', 'loggable_id']);
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
