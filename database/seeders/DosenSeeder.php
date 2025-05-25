<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dosen;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus semua data dosen sebelum melakukan seeding
        // Dosen::truncate(); // Akan menghapus semua data di tabel dosen

        $dosens = [
            [
                'nama_dosen' => 'Andi Setiawan',
                'nip' => '197812312019031001',
                'alamat' => 'Jl. Merpati No. 10, Surabaya',
                'no_hp' => '081234567890',
                'status' => 'AKTIF',
            ],
            [
                'nama_dosen' => 'Siti Nurhaliza',
                'nip' => '196511221990021002',
                'alamat' => 'Jl. Kenari No. 5, Malang',
                'no_hp' => '082198765432',
                'status' => 'AKTIF',
            ],
            [
                'nama_dosen' => 'Budi Santoso',
                'nip' => '198004152005011003',
                'alamat' => 'Jl. Anggrek No. 15, Jakarta',
                'no_hp' => '083112345678',
                'status' => 'CUTI',
            ],
            [
                'nama_dosen' => 'Fadilah Fahrul Hardiansyah',
                'nip' => '198004152005011004',
                'alamat' => 'Surabaya',
                'no_hp' => '083112345679',
                'status' => 'AKTIF',
            ],
            [
                'nama_dosen' => 'Desy Intan Permatasari',
                'nip' => '198004152005011005',
                'alamat' => 'Surabaya',
                'no_hp' => '083112345680',
                'status' => 'AKTIF',
            ],
            [
                'nama_dosen' => 'Prasetyo Wibowo',
                'nip' => '198104152005011005',
                'alamat' => 'Surabaya',
                'no_hp' => '083112345681',
                'status' => 'PENSIUN',
            ],
            [
                'nama_dosen' => 'Yanuar Risah Prayogi',
                'nip' => '198004152005011006',
                'alamat' => 'Surabaya',
                'no_hp' => '083112345682',
                'status' => 'AKTIF',
            ],
            [
                'nama_dosen' => 'Idris Winarno',
                'nip' => '198004152005011007',
                'alamat' => 'Surabaya',
                'no_hp' => '083112345683',
                'status' => 'AKTIF',
            ],
            [
                'nama_dosen' => 'Nur Rosyid Mubtadai',
                'nip' => '198004152005011008',
                'alamat' => 'Surabaya',
                'no_hp' => '083112345684',
                'status' => 'CUTI',
            ],
            [
                'nama_dosen' => 'Renovita Edelani',
                'nip' => '198004152005011009',
                'alamat' => 'Surabaya',
                'no_hp' => '083112345685',
                'status' => 'AKTIF',
            ],
            [
                'nama_dosen' => 'Yesta Medya Mahardhika',
                'nip' => '198004152005011010',
                'alamat' => 'Surabaya',
                'no_hp' => '083112345686',
                'status' => 'AKTIF',
            ],
            [
                'nama_dosen' => 'Aliridho Barakbah',
                'nip' => '198004152005011011',
                'alamat' => 'Surabaya',
                'no_hp' => '083112345687',
                'status' => 'TIDAK AKTIF',
            ],
            [
                'nama_dosen' => 'Weny Mistarika Rahmawati',
                'nip' => '198004152005011012',
                'alamat' => 'Surabaya',
                'no_hp' => '083112345688',
                'status' => 'AKTIF',
            ],
            [
                'nama_dosen' => 'Ferry Astika Saputra',
                'nip' => '198004152005011013',
                'alamat' => 'Surabaya',
                'no_hp' => '083112345689',
                'status' => 'AKTIF',
            ],
            [
                'nama_dosen' => 'Adam Shidqul Aziz',
                'nip' => '198004152005011014',
                'alamat' => 'Surabaya',
                'no_hp' => '083112345690',
                'status' => 'AKTIF',
            ],
        ];

        foreach ($dosens as $dosen) {
            Dosen::create($dosen);
        }
    }
}