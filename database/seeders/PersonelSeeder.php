<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Personel;

class PersonelSeeder extends Seeder
{
    public function run(): void
    {
        $personels = [
            [
                'nrp' => '11010001',
                'nama' => 'Andi Wijaya',
                'pangkat' => 'Kolonel',
                'jabatan' => 'Komandan Batalyon',
                'kesatuan' => 'Yonif 1 Kostrad',
            ],
            [
                'nrp' => '11010002',
                'nama' => 'Budi Santoso',
                'pangkat' => 'Letkol',
                'jabatan' => 'Wakil Komandan',
                'kesatuan' => 'Yonif 1 Kostrad',
            ],
            [
                'nrp' => '11010003',
                'nama' => 'Chandra Kusuma',
                'pangkat' => 'Mayor',
                'jabatan' => 'Pasi Ops',
                'kesatuan' => 'Yonif 2 Kostrad',
            ],
            [
                'nrp' => '11010004',
                'nama' => 'Dedi Prasetyo',
                'pangkat' => 'Kapten',
                'jabatan' => 'Danki A',
                'kesatuan' => 'Yonif 2 Kostrad',
            ],
            [
                'nrp' => '11010005',
                'nama' => 'Eko Wahyudi',
                'pangkat' => 'Lettu',
                'jabatan' => 'Danton 1',
                'kesatuan' => 'Yonif 3 Kostrad',
            ],
            [
                'nrp' => '11010006',
                'nama' => 'Fajar Nugroho',
                'pangkat' => 'Letda',
                'jabatan' => 'Danton 2',
                'kesatuan' => 'Yonif 3 Kostrad',
            ],
            [
                'nrp' => '11010007',
                'nama' => 'Gunawan Hidayat',
                'pangkat' => 'Serka',
                'jabatan' => 'Danru',
                'kesatuan' => 'Yonif 4 Kostrad',
            ],
            [
                'nrp' => '11010008',
                'nama' => 'Hadi Suryanto',
                'pangkat' => 'Sertu',
                'jabatan' => 'Babinsa',
                'kesatuan' => 'Yonif 4 Kostrad',
            ],
            [
                'nrp' => '11010009',
                'nama' => 'Indra Mahendra',
                'pangkat' => 'Serda',
                'jabatan' => 'Bintara Ops',
                'kesatuan' => 'Yonif 5 Kostrad',
            ],
            [
                'nrp' => '11010010',
                'nama' => 'Joko Widodo',
                'pangkat' => 'Kopka',
                'jabatan' => 'Bintara Intel',
                'kesatuan' => 'Yonif 5 Kostrad',
            ],
            [
                'nrp' => '11010011',
                'nama' => 'Kurniawan Setiawan',
                'pangkat' => 'Koptu',
                'jabatan' => 'Bintara Logistik',
                'kesatuan' => 'Yonbekang Kostrad',
            ],
            [
                'nrp' => '11010012',
                'nama' => 'Lukman Hakim',
                'pangkat' => 'Kopda',
                'jabatan' => 'Bintara Persenjataan',
                'kesatuan' => 'Yonbekang Kostrad',
            ],
            [
                'nrp' => '11010013',
                'nama' => 'Muhammad Rizki',
                'pangkat' => 'Prada',
                'jabatan' => 'Tamtama Infanteri',
                'kesatuan' => 'Yonif 1 Kostrad',
            ],
            [
                'nrp' => '11010014',
                'nama' => 'Nur Hidayat',
                'pangkat' => 'Pratu',
                'jabatan' => 'Tamtama Kavaleri',
                'kesatuan' => 'Yonkav Kostrad',
            ],
            [
                'nrp' => '11010015',
                'nama' => 'Oki Permana',
                'pangkat' => 'Praka',
                'jabatan' => 'Tamtama Artileri',
                'kesatuan' => 'Yonarmed Kostrad',
            ],
        ];

        foreach ($personels as $personel) {
            Personel::create($personel);
        }
    }
}
