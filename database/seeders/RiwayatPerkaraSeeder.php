<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiwayatPerkara;
use App\Models\Perkara;
use App\Models\User;
use Carbon\Carbon;

class RiwayatPerkaraSeeder extends Seeder
{
    public function run(): void
    {
        $perkaras = Perkara::all();
        $users = User::all();

        if ($perkaras->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Please run PerkaraSeeder and UserSeeder first!');
            return;
        }

        $statuses = [
            'Diterima' => 'Perkara telah diterima dan didaftarkan',
            'Dalam Pemeriksaan' => 'Sedang dilakukan pemeriksaan saksi dan bukti',
            'Penyusunan Dakwaan' => 'Tim sedang menyusun surat dakwaan',
            'Sidang' => 'Perkara dalam tahap persidangan',
            'Putusan' => 'Putusan telah dijatuhkan',
            'Selesai' => 'Perkara telah selesai diproses',
        ];

        foreach ($perkaras->take(8) as $perkara) {
            $tanggalMulai = $perkara->tanggal_masuk;
            $statusKeys = array_keys($statuses);

            // Create 3-5 history entries per case
            $historyCount = $perkara->status === 'Selesai' ? rand(4, 6) : rand(2, 4);

            for ($i = 0; $i < $historyCount; $i++) {
                $statusIndex = min($i, count($statusKeys) - 1);
                $status = $statusKeys[$statusIndex];

                RiwayatPerkara::create([
                    'perkara_id' => $perkara->id,
                    'user_id' => $users->random()->id,
                    'aksi' => $status,
                    'deskripsi' => $statuses[$status] . ' - ' . $perkara->nomor_perkara,
                    'tanggal_aksi' => Carbon::parse($tanggalMulai)->addDays($i * rand(3, 7)),
                ]);
            }
        }

        $this->command->info('✅ Riwayat Perkara seeder executed successfully!');
    }
}
