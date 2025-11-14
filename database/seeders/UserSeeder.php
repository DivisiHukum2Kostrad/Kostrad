<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin Default
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@siperkara.mil.id',
            'password' => Hash::make('password'), // Ganti dengan password yang aman!
            'nrp' => '1234567890',
            'pangkat' => 'Mayor',
            'jabatan' => 'Kepala Seksi Hukum',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Operator Default
        User::create([
            'name' => 'Operator Perkara',
            'email' => 'operator@siperkara.mil.id',
            'password' => Hash::make('password'), // Ganti dengan password yang aman!
            'nrp' => '0987654321',
            'pangkat' => 'Kapten',
            'jabatan' => 'Staff Seksi Hukum',
            'role' => 'operator',
            'email_verified_at' => now(),
        ]);
    }
}
