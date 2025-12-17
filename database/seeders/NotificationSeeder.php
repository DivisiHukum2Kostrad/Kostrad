<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;
use App\Models\Perkara;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $perkaras = Perkara::all();

        if ($users->isEmpty() || $perkaras->isEmpty()) {
            $this->command->warn('Please run UserSeeder and PerkaraSeeder first!');
            return;
        }

        $notificationTypes = [
            'case_assigned' => [
                'title' => 'Perkara Baru Ditugaskan',
                'message' => 'Anda telah ditugaskan untuk menangani perkara baru',
            ],
            'status_changed' => [
                'title' => 'Status Perkara Berubah',
                'message' => 'Status perkara telah diperbarui',
            ],
            'deadline_reminder' => [
                'title' => 'Pengingat Deadline',
                'message' => 'Perkara akan mencapai deadline dalam 3 hari',
            ],
            'document_uploaded' => [
                'title' => 'Dokumen Baru Diunggah',
                'message' => 'Dokumen baru telah ditambahkan ke perkara',
            ],
            'case_completed' => [
                'title' => 'Perkara Selesai',
                'message' => 'Perkara telah diselesaikan',
            ],
        ];

        foreach ($users as $user) {
            // Create 5-10 notifications per user
            $notifCount = rand(5, 10);

            for ($i = 0; $i < $notifCount; $i++) {
                $type = array_rand($notificationTypes);
                $template = $notificationTypes[$type];
                $perkara = $perkaras->random();
                $isRead = rand(0, 100) > 40; // 60% chance of being read
                $isEmailed = rand(0, 100) > 50; // 50% chance of being emailed

                Notification::create([
                    'user_id' => $user->id,
                    'type' => $type,
                    'subject' => $template['title'],
                    'message' => $template['message'] . ' - ' . $perkara->nomor_perkara,
                    'data' => json_encode([
                        'perkara_id' => $perkara->id,
                        'perkara_nomor' => $perkara->nomor_perkara,
                        'action_url' => route('admin.perkaras.show', $perkara->id),
                    ]),
                    'is_read' => $isRead,
                    'read_at' => $isRead ? Carbon::now()->subDays(rand(1, 15)) : null,
                    'is_emailed' => $isEmailed,
                    'emailed_at' => $isEmailed ? Carbon::now()->subDays(rand(1, 18)) : null,
                    'created_at' => Carbon::now()->subDays(rand(1, 20)),
                ]);
            }
        }

        $this->command->info('✅ Notification seeder executed successfully!');
    }
}
