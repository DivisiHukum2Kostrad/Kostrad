<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DokumenPerkara;
use App\Models\Perkara;

class DokumenPerkaraSeeder extends Seeder
{
    public function run(): void
    {
        $perkaras = Perkara::all();

        if ($perkaras->isEmpty()) {
            $this->command->warn('Please run PerkaraSeeder first!');
            return;
        }

        $dokumenTypes = [
            'Surat Dakwaan',
            'BAP (Berita Acara Pemeriksaan)',
            'Surat Pelimpahan',
            'Bukti Elektronik',
            'Foto Kejadian',
            'SKEP (Surat Keputusan)',
            'Laporan Saksi',
            'Dokumen Pendukung',
        ];

        foreach ($perkaras->take(7) as $perkara) {
            // Create 2-4 documents per case
            $documentCount = rand(2, 4);

            for ($i = 0; $i < $documentCount; $i++) {
                $isSigned = rand(0, 1) == 1;

                DokumenPerkara::create([
                    'perkara_id' => $perkara->id,
                    'nama_dokumen' => $dokumenTypes[array_rand($dokumenTypes)] . ' - ' . $perkara->nomor_perkara,
                    'jenis_dokumen' => $dokumenTypes[array_rand($dokumenTypes)],
                    'category' => collect(['Evidence', 'Legal', 'Administrative', 'Report'])->random(),
                    'file_path' => 'dokumen/' . $perkara->id . '/dokumen_' . ($i + 1) . '.pdf',
                    'file_size' => rand(100000, 5000000), // bytes
                    'mime_type' => 'application/pdf',
                    'uploaded_by' => $perkara->assigned_to,
                    'description' => 'Dokumen ' . ($i + 1) . ' untuk perkara ' . $perkara->nomor_perkara,
                    'version' => 1,
                    'download_count' => rand(0, 20),
                    'last_downloaded_at' => rand(0, 1) == 1 ? now()->subDays(rand(1, 10)) : null,
                    'is_signed' => $isSigned,
                    'signed_by' => $isSigned ? $perkara->assigned_to : null,
                    'signed_at' => $isSigned ? now()->subDays(rand(1, 5)) : null,
                    'signature_name' => $isSigned ? 'Digital Signature ' . uniqid() : null,
                    'has_thumbnail' => rand(0, 1) == 1,
                    'is_public' => rand(0, 1) == 1,
                ]);
            }
        }

        $this->command->info('✅ Dokumen Perkara seeder executed successfully!');
    }
}
