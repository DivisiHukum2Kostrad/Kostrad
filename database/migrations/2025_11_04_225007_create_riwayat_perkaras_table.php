<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_perkaras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perkara_id')->constrained('perkaras')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('aksi'); // Dibuat, Diupdate, Diproses, Diselesaikan
            $table->text('deskripsi')->nullable();
            $table->timestamp('tanggal_aksi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_perkaras');
    }
};
