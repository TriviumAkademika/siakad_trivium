<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['id_dosen' => 1, 'tahun_masuk' => 2022, 'prodi' => 'Informatika', 'paralel' => 'A'],
            ['id_dosen' => 2, 'tahun_masuk' => 2022, 'prodi' => 'Sistem Informasi', 'paralel' => 'B'],
            ['id_dosen' => 3, 'tahun_masuk' => 2023, 'prodi' => 'Teknik Komputer', 'paralel' => 'A'],
            ['id_dosen' => 1, 'tahun_masuk' => 2023, 'prodi' => 'Informatika', 'paralel' => 'C'],
        ];

        foreach ($data as $kelas) {
            Kelas::create($kelas);
        }
    }
}
