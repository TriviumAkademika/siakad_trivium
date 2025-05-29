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
            ['nama_matkul' => 'Workshop Desain Pengalaman Pengguna','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30],
            ['nama_matkul' => 'Workshop Pemrograman Perangkat Bergerak','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30],
            ['nama_matkul' => 'Workshop Pemrograman Framework','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30],
            ['nama_matkul' => 'Workshop Administrasi Jaringan','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30],
            ['nama_matkul' => 'Workshop Pengembangan Perangkat Lunak berbasis Agile','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30],
            ['nama_matkul' => 'Praktek Kecerdasan Buatan','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30],
            ['nama_matkul' => 'Workshop Aplikasi dan Komputasi Awan','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30],
            ['nama_matkul' => 'Kecerdasan Buatan','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30],
            ['nama_matkul' => 'Workshop Administrasi Basis Data','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30],
            ['nama_matkul' => 'Bahasa Indonesia','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30],

            // Tambahan 30 matkul
            ['nama_matkul' => 'Manajemen Proyek TI','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30],
            ['nama_matkul' => 'Interaksi Manusia dan Komputer','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30],
            ['nama_matkul' => 'Pengolahan Citra Digital','jenis' => 'Pilihan','sks' => 2,'kapasitas_kelas' => 25],
            ['nama_matkul' => 'Sistem Informasi Geografis','jenis' => 'Pilihan','sks' => 2,'kapasitas_kelas' => 25],
            ['nama_matkul' => 'Teknologi Game','jenis' => 'Pilihan','sks' => 2,'kapasitas_kelas' => 25],
            ['nama_matkul' => 'Etika Profesi TI','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30],
            ['nama_matkul' => 'Pemrograman Web Lanjut','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30],
            ['nama_matkul' => 'Keamanan Jaringan','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30],
            ['nama_matkul' => 'Big Data dan Analitik','jenis' => 'Pilihan','sks' => 2,'kapasitas_kelas' => 25],
            ['nama_matkul' => 'Basis Data Terdistribusi','jenis' => 'Pilihan','sks' => 2,'kapasitas_kelas' => 25],
            ['nama_matkul' => 'Bahasa Inggris untuk TI','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30],
            ['nama_matkul' => 'Rekayasa Perangkat Lunak','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30],
            ['nama_matkul' => 'Analisis dan Perancangan Sistem','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30],
            ['nama_matkul' => 'Pemrograman Berbasis Objek','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30],
            ['nama_matkul' => 'Pemrograman Dasar','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30],
            ['nama_matkul' => 'Pemrograman Python','jenis' => 'Pilihan','sks' => 2,'kapasitas_kelas' => 25],
            ['nama_matkul' => 'Deep Learning','jenis' => 'Pilihan','sks' => 2,'kapasitas_kelas' => 25],
            ['nama_matkul' => 'Cloud Computing','jenis' => 'Pilihan','sks' => 2,'kapasitas_kelas' => 25],
            ['nama_matkul' => 'Manajemen Data Center','jenis' => 'Pilihan','sks' => 2,'kapasitas_kelas' => 25],
            ['nama_matkul' => 'DevOps Engineering','jenis' => 'Pilihan','sks' => 2,'kapasitas_kelas' => 25],
            ['nama_matkul' => 'Internet of Things (IoT)','jenis' => 'Pilihan','sks' => 2,'kapasitas_kelas' => 25],
            ['nama_matkul' => 'Agile Software Development','jenis' => 'Pilihan','sks' => 2,'kapasitas_kelas' => 25],
            ['nama_matkul' => 'Sistem Operasi','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30],
            ['nama_matkul' => 'Arsitektur Komputer','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30],
            ['nama_matkul' => 'Kalkulus','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30],
            ['nama_matkul' => 'Statistika','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30],
            ['nama_matkul' => 'Logika Matematika','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30],
            ['nama_matkul' => 'Sistem Digital','jenis' => 'Wajib','sks' => 2,'kapasitas_kelas' => 30],
            ['nama_matkul' => 'Teknologi Blockchain','jenis' => 'Pilihan','sks' => 2,'kapasitas_kelas' => 25],
        ];

        foreach ($matkuls as $matkul) {
            Matkul::create($matkul);
        }
    }
}
