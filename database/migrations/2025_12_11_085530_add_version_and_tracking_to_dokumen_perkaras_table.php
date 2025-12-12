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
        Schema::table('dokumen_perkaras', function (Blueprint $table) {
            $table->integer('version')->default(1)->after('mime_type');
            $table->foreignId('parent_id')->nullable()->after('version')->constrained('dokumen_perkaras')->onDelete('cascade');
            $table->integer('download_count')->default(0)->after('parent_id');
            $table->timestamp('last_downloaded_at')->nullable()->after('download_count');
            $table->foreignId('uploaded_by')->nullable()->after('last_downloaded_at')->constrained('users')->onDelete('set null');
            $table->text('description')->nullable()->after('uploaded_by');
            $table->string('category')->nullable()->after('jenis_dokumen'); // Evidence, Legal, Administrative, etc
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumen_perkaras', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropForeign(['uploaded_by']);
            $table->dropColumn(['version', 'parent_id', 'download_count', 'last_downloaded_at', 'uploaded_by', 'description', 'category']);
        });
    }
};
