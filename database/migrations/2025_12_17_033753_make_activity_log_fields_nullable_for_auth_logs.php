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
        Schema::table('activity_logs', function (Blueprint $table) {
            // Make fields nullable for authentication logs (login/logout)
            // These logs don't need polymorphic relationships
            $table->string('log_type')->nullable()->change();
            $table->string('loggable_type')->nullable()->change();
            $table->unsignedBigInteger('loggable_id')->nullable()->change();

            // Add action field for simple auth logs
            $table->string('action')->nullable()->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('log_type')->nullable(false)->change();
            $table->string('loggable_type')->nullable(false)->change();
            $table->unsignedBigInteger('loggable_id')->nullable(false)->change();
            $table->dropColumn('action');
        });
    }
};
