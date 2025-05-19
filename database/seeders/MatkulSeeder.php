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
            ['nama_matkul' => 'Workshop Desain Pengalaman Pengguna','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30,],
            ['nama_matkul' => 'Workshop Pemrograman Perangkat Bergerak ','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30,],
            ['nama_matkul' => 'Workshop Pemrograman Framework','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30,],
            ['nama_matkul' => 'Workshop Administrasi Jaringan','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30,],
            ['nama_matkul' => 'Workshop Pengembangan Perangkat Lunak berbasis Agile','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30,],
            ['nama_matkul' => 'Praktek Kecerdasan Buatan','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30,],
            ['nama_matkul' => 'Workshop Aplikasi dan Komputasi Awan','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30,],
            ['nama_matkul' => 'Kecerdasan Buatan','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30,],
            ['nama_matkul' => 'Workshop Administrasi Basis Data','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30,],
            ['nama_matkul' => 'Bahasa Indonesia','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30,],
        ];

        foreach ($matkuls as $matkul) {
            Matkul::create($matkul);
        }
    }
}
