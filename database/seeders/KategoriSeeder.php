<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kategoris')->insert([
            [
                'nama' => 'Disiplin',
                'warna' => 'purple',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Administrasi',
                'warna' => 'blue',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Pidana',
                'warna' => 'red',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
