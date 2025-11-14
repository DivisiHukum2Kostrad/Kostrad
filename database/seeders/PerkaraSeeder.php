<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PerkaraSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('perkaras')->insert([
            [
                'nomor_perkara' => 'PERK/DIV2/2024/001',
                'jenis_perkara' => 'Pelanggaran Disiplin Militer',
                'kategori_id' => 1, // Disiplin
                'tanggal_masuk' => '2024-01-10',
                'tanggal_selesai' => '2024-01-15',
                'status' => 'Selesai',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nomor_perkara' => 'PERK/DIV2/2024/002',
                'jenis_perkara' => 'Pelanggaran Administratif',
                'kategori_id' => 2, // Administrasi
                'tanggal_masuk' => '2024-01-18',
                'tanggal_selesai' => '2024-02-22',
                'status' => 'Selesai',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nomor_perkara' => 'PERK/DIV2/2024/003',
                'jenis_perkara' => 'Ketidakhadiran Tanpa Izin',
                'kategori_id' => 1, // Disiplin
                'tanggal_masuk' => '2024-03-05',
                'tanggal_selesai' => '2024-03-10',
                'status' => 'Selesai',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nomor_perkara' => 'PERK/DIV2/2024/004',
                'jenis_perkara' => 'Kehilangan Aset Negara',
                'kategori_id' => 3, // Pidana
                'tanggal_masuk' => '2024-03-12',
                'tanggal_selesai' => null,
                'status' => 'Proses',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nomor_perkara' => 'PERK/DIV2/2024/005',
                'jenis_perkara' => 'Penyalahgunaan Wewenang',
                'kategori_id' => 3, // Pidana
                'tanggal_masuk' => '2024-03-20',
                'tanggal_selesai' => '2024-04-28',
                'status' => 'Selesai',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nomor_perkara' => 'PERK/DIV2/2024/006',
                'jenis_perkara' => 'Pelanggaran Tata Tertib',
                'kategori_id' => 1, // Disiplin
                'tanggal_masuk' => '2024-04-02',
                'tanggal_selesai' => '2024-04-08',
                'status' => 'Selesai',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nomor_perkara' => 'PERK/DIV2/2024/007',
                'jenis_perkara' => 'Kesalahan Prosedur Operasional',
                'kategori_id' => 2, // Administrasi
                'tanggal_masuk' => '2024-04-15',
                'tanggal_selesai' => '2024-05-25',
                'status' => 'Selesai',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nomor_perkara' => 'PERK/DIV2/2024/008',
                'jenis_perkara' => 'Pemalsuan Dokumen',
                'kategori_id' => 3, // Pidana
                'tanggal_masuk' => '2024-05-10',
                'tanggal_selesai' => null,
                'status' => 'Proses',
                'is_public' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
