<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['id_dosen' => 15, 'tahun_masuk' => 2023, 'prodi' => 'D3 TI', 'paralel' => 'B', 'status' => 'AKTIF'],
            ['id_dosen' => 12, 'tahun_masuk' => 2023, 'prodi' => 'D3 TI', 'paralel' => 'A', 'status' => 'AKTIF'],
            ['id_dosen' => 1, 'tahun_masuk' => 2023, 'prodi' => 'D4 TI', 'paralel' => 'A', 'status' => 'AKTIF'],
            ['id_dosen' => 2, 'tahun_masuk' => 2023, 'prodi' => 'D4 TI', 'paralel' => 'B', 'status' => 'AKTIF'],
            ['id_dosen' => 3, 'tahun_masuk' => 2023, 'prodi' => 'D4 SDT', 'paralel' => 'A', 'status' => 'AKTIF'],
            ['id_dosen' => 4, 'tahun_masuk' => 2023, 'prodi' => 'D4 SDT', 'paralel' => 'B', 'status' => 'AKTIF'],
            ['id_dosen' => 5, 'tahun_masuk' => 2024, 'prodi' => 'D4 SI', 'paralel' => 'A', 'status' => 'AKTIF'],
            ['id_dosen' => 6, 'tahun_masuk' => 2024, 'prodi' => 'D4 SI', 'paralel' => 'B', 'status' => 'AKTIF'],
        ];

        foreach ($data as $kelas) {
            Kelas::create($kelas);
        }
    }
}