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
            $table->string('thumbnail_path')->nullable()->after('file_path');
            $table->string('qr_code_path')->nullable()->after('thumbnail_path');
            $table->text('digital_signature')->nullable()->after('qr_code_path');
            $table->string('signature_name')->nullable()->after('digital_signature');
            $table->timestamp('signed_at')->nullable()->after('signature_name');
            $table->foreignId('signed_by')->nullable()->constrained('users')->after('signed_at');
            $table->json('metadata')->nullable()->after('signed_by');
            $table->boolean('has_thumbnail')->default(false)->after('metadata');
            $table->boolean('is_signed')->default(false)->after('has_thumbnail');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumen_perkaras', function (Blueprint $table) {
            $table->dropForeign(['signed_by']);
            $table->dropColumn([
                'thumbnail_path',
                'qr_code_path',
                'digital_signature',
                'signature_name',
                'signed_at',
                'signed_by',
                'metadata',
                'has_thumbnail',
                'is_signed',
            ]);
        });
    }
};
