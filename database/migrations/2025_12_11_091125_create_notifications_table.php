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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('type'); // case_assigned, status_changed, document_uploaded, deadline_reminder
            $table->string('subject');
            $table->text('message');
            $table->json('data')->nullable(); // Additional data (perkara_id, document_id, etc.)
            $table->boolean('is_read')->default(false);
            $table->boolean('is_emailed')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamp('emailed_at')->nullable();
            $table->timestamps();
        });

        // Notification preferences table
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->boolean('email_case_assigned')->default(true);
            $table->boolean('email_status_changed')->default(true);
            $table->boolean('email_document_uploaded')->default(true);
            $table->boolean('email_deadline_reminder')->default(true);
            $table->boolean('email_daily_summary')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
        Schema::dropIfExists('notifications');
    }
};
