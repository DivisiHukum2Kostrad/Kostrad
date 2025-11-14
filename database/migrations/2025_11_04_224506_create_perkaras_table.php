<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perkaras', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_perkara')->unique(); // PERK/DIV2/2024/001
            $table->string('jenis_perkara');
            $table->foreignId('kategori_id')->constrained('kategoris')->onDelete('cascade');
            $table->date('tanggal_masuk');
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', ['Proses', 'Selesai'])->default('Proses');
            $table->text('keterangan')->nullable();
            $table->string('file_dokumentasi')->nullable(); // path ke PDF
            $table->boolean('is_public')->default(false); // apakah bisa diakses publik
            $table->timestamps();
            $table->softDeletes(); // untuk soft delete
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perkaras');
    }
};
