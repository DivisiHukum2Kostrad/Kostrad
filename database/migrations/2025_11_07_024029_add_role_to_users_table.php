<?php

// database/migrations/2024_01_01_000000_add_role_to_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nrp')->nullable()->after('email');
            $table->string('pangkat')->nullable()->after('nrp');
            $table->string('jabatan')->nullable()->after('pangkat');
            $table->enum('role', ['admin', 'operator'])->default('operator')->after('jabatan');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nrp', 'pangkat', 'jabatan', 'role']);
        });
    }
};
