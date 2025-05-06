<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Matkul;

class MatkulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $matkuls = [
            [
                'nama_matkul' => 'Pemrograman Web',
                'jenis' => 'Teori',
                'sks' => 3,
                'kapasitas_kelas' => 40,
            ],
            [
                'nama_matkul' => 'Struktur Data',
                'jenis' => 'Teori',
                'sks' => 3,
                'kapasitas_kelas' => 35,
            ],
            [
                'nama_matkul' => 'Praktikum Basis Data',
                'jenis' => 'Praktikum',
                'sks' => 1,
                'kapasitas_kelas' => 25,
            ],
            [
                'nama_matkul' => 'Kalkulus Dasar',
                'jenis' => 'Teori',
                'sks' => 4,
                'kapasitas_kelas' => 50,
            ],
        ];

        foreach ($matkuls as $matkul) {
            Matkul::create($matkul);
        }
    }
}
