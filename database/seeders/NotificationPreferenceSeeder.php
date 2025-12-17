<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationPreference;
use App\Models\User;

class NotificationPreferenceSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('Please run UserSeeder first!');
            return;
        }

        foreach ($users as $user) {
            NotificationPreference::create([
                'user_id' => $user->id,
                'email_case_assigned' => true,
                'email_status_changed' => true,
                'email_deadline_reminder' => rand(0, 1) == 1,
                'email_document_uploaded' => rand(0, 1) == 1,
                'email_daily_summary' => rand(0, 1) == 1,
            ]);
        }

        $this->command->info('✅ Notification Preference seeder executed successfully!');
    }
}
