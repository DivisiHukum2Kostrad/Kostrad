<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🌱 Starting complete database seeding...');

        $this->call([
            // Base Data
            KategoriSeeder::class,
            UserSeeder::class,
            PersonelSeeder::class,

            // Main Data
            CompletePerkaraSeeder::class,

            // Related Data
            DokumenPerkaraSeeder::class,
            RiwayatPerkaraSeeder::class,

            // System Data
            NotificationPreferenceSeeder::class,
            NotificationSeeder::class,
            ActivityLogSeeder::class,
        ]);

        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->info('');
        $this->command->info('📊 Summary:');
        $this->command->info('   - Kategoris: 3 items');
        $this->command->info('   - Users: 2 items (admin & operator)');
        $this->command->info('   - Personels: 15 items');
        $this->command->info('   - Perkaras: 10 items');
        $this->command->info('   - Dokumen Perkaras: ~25 items');
        $this->command->info('   - Riwayat Perkaras: ~35 items');
        $this->command->info('   - Notifications: ~15 items per user');
        $this->command->info('   - Activity Logs: ~100+ items');
        $this->command->info('');
        $this->command->info('🔐 Default Login:');
        $this->command->info('   Admin    - admin@siperkara.mil.id / password');
        $this->command->info('   Operator - operator@siperkara.mil.id / password');
    }
}

