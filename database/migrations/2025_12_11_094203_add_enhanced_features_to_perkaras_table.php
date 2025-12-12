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
        Schema::table('perkaras', function (Blueprint $table) {
            // Priority: Low, Medium, High, Urgent
            $table->enum('priority', ['Low', 'Medium', 'High', 'Urgent'])
                ->default('Medium')
                ->after('status');
            
            // Deadline for case completion
            $table->date('deadline')->nullable()->after('priority');
            
            // Additional case details
            $table->string('nama', 255)->nullable()->after('jenis_perkara');
            $table->text('deskripsi')->nullable()->after('nama');
            
            // Case tracking
            $table->string('assigned_to')->nullable()->after('deskripsi'); // User name or personel
            $table->date('tanggal_perkara')->nullable()->after('tanggal_masuk'); // Date of incident
            
            // Progress tracking
            $table->integer('progress')->default(0)->after('deadline'); // 0-100
            $table->text('internal_notes')->nullable()->after('keterangan'); // Private notes
            
            // Tags for categorization
            $table->json('tags')->nullable()->after('internal_notes');
            
            // Estimated completion
            $table->integer('estimated_days')->nullable()->after('progress');
            
            // Add indexes for better query performance
            $table->index('priority');
            $table->index('deadline');
            $table->index('assigned_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perkaras', function (Blueprint $table) {
            $table->dropIndex(['priority']);
            $table->dropIndex(['deadline']);
            $table->dropIndex(['assigned_to']);
            
            $table->dropColumn([
                'priority',
                'deadline',
                'nama',
                'deskripsi',
                'assigned_to',
                'tanggal_perkara',
                'progress',
                'internal_notes',
                'tags',
                'estimated_days'
            ]);
        });
    }
};
