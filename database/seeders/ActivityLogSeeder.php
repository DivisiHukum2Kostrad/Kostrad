<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Perkara;
use Carbon\Carbon;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $perkaras = Perkara::all();

        if ($users->isEmpty() || $perkaras->isEmpty()) {
            $this->command->warn('Please run UserSeeder and PerkaraSeeder first!');
            return;
        }

        $logTypes = ['created', 'updated', 'deleted', 'viewed'];
        $descriptions = [
            'created' => 'membuat perkara baru',
            'updated' => 'memperbarui data perkara',
            'deleted' => 'menghapus data perkara',
            'viewed' => 'melihat detail perkara',
        ];

        // Create activity logs for perkaras
        foreach ($perkaras->take(8) as $perkara) {
            $logsPerCase = rand(3, 8);

            for ($i = 0; $i < $logsPerCase; $i++) {
                $logType = $logTypes[array_rand($logTypes)];
                $user = $users->random();

                ActivityLog::create([
                    'log_type' => $logType,
                    'loggable_type' => Perkara::class,
                    'loggable_id' => $perkara->id,
                    'user_id' => $user->id,
                    'action' => null,
                    'description' => $user->name . ' ' . $descriptions[$logType] . ' ' . $perkara->nomor_perkara,
                    'old_values' => $logType === 'updated' ? json_encode([
                        'status' => 'Proses',
                        'progress' => rand(0, 50),
                    ]) : null,
                    'new_values' => $logType === 'updated' ? json_encode([
                        'status' => $perkara->status,
                        'progress' => $perkara->progress,
                    ]) : null,
                    'metadata' => json_encode([
                        'browser' => collect(['Chrome', 'Firefox', 'Edge', 'Safari'])->random(),
                        'os' => collect(['Windows 10', 'Windows 11', 'Linux', 'MacOS'])->random(),
                    ]),
                    'ip_address' => '192.168.1.' . rand(1, 255),
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'created_at' => Carbon::now()->subDays(rand(1, 30))->subHours(rand(0, 23)),
                ]);
            }
        }

        // Create login/logout activity logs
        foreach ($users as $user) {
            $loginCount = rand(5, 15);

            for ($i = 0; $i < $loginCount; $i++) {
                $isLogin = $i % 2 == 0;

                ActivityLog::create([
                    'log_type' => null,
                    'loggable_type' => null,
                    'loggable_id' => null,
                    'user_id' => $user->id,
                    'action' => $isLogin ? 'login' : 'logout',
                    'description' => $user->name . ($isLogin ? ' login ke sistem' : ' logout dari sistem'),
                    'old_values' => null,
                    'new_values' => null,
                    'metadata' => json_encode([
                        'session_id' => uniqid(),
                        'duration' => !$isLogin ? rand(30, 240) . ' minutes' : null,
                    ]),
                    'ip_address' => '192.168.1.' . rand(1, 255),
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    'created_at' => Carbon::now()->subDays(rand(1, 20))->subHours(rand(0, 23)),
                ]);
            }
        }

        $this->command->info('✅ Activity Log seeder executed successfully!');
    }
}
