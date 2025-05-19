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
            ],
            [
                'nama_dosen' => 'Siti Nurhaliza',
                'nip' => '196511221990021002',
                'alamat' => 'Jl. Kenari No. 5, Malang',
                'no_hp' => '082198765432',
            ],
            [
                'nama_dosen' => 'Budi Santoso',
                'nip' => '198004152005011003',
                'alamat' => 'Jl. Anggrek No. 15, Jakarta',
                'no_hp' => '083112345678',
            ],
            [
                'nama_dosen' => 'Fadilah Fahrul Hardiansyah',
                'nip' => '198004152005011004',
                'alamat' => 'Surabaya',
                'no_hp' => '083112345679'
            ],
            [
                'nama_dosen' => 'Desy Intan Permatasari',
                'nip' => '198004152005011005',
                'alamat' => 'Surabaya',
                'no_hp' => '083112345680',
            ],
            [
                'nama_dosen' => 'Prasetyo Wibowo',
                'nip' => '198104152005011005',
                'alamat' => 'Surabaya',
                'no_hp' => '083112345681',
            ],
            [
                'nama_dosen' => 'Yanuar Risah Prayogi', //7
                'nip' => '198004152005011006',
                'alamat' => 'Surabaya',
                'no_hp' => '083112345682',
            ],
            [
                'nama_dosen' => 'Idris Winarno', //8
                'nip' => '198004152005011007',
                'alamat' => 'Surabaya',
                'no_hp' => '083112345683',
            ],
            [
                'nama_dosen' => 'Nur Rosyid Mubtadai', //9
                'nip' => '198004152005011008',
                'alamat' => 'Surabaya',
                'no_hp' => '083112345684',
            ],
            [
                'nama_dosen' => 'Renovita Edelani',  //10
                'nip' => '198004152005011009',
                'alamat' => 'Surabaya',
                'no_hp' => '083112345685',
            ],
            [
                'nama_dosen' => 'Yesta Medya Mahardhika', //11
                'nip' => '198004152005011010',
                'alamat' => 'Surabaya',
                'no_hp' => '083112345686',
            ],
            [
                'nama_dosen' => 'Aliridho Barakbah', //12
                'nip' => '198004152005011011',
                'alamat' => 'Surabaya',
                'no_hp' => '083112345687',
            ],
            [
                'nama_dosen' => 'Weny Mistarika Rahmawati', //13
                'nip' => '198004152005011012',
                'alamat' => 'Surabaya',
                'no_hp' => '083112345688',
            ],
            [
                'nama_dosen' => 'Ferry Astika Saputra',
                'nip' => '198004152005011013',
                'alamat' => 'Surabaya',
                'no_hp' => '083112345689',
            ],
            [
                'nama_dosen' => 'Adam Shidqul Aziz',
                'nip' => '198004152005011014',
                'alamat' => 'Surabaya',
                'no_hp' => '083112345690'
            ],
        ];

        foreach ($dosens as $dosen) {
            Dosen::create($dosen);
        }
    }
}
