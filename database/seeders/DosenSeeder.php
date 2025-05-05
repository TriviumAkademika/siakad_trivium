<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dosen;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus semua data dosen sebelum melakukan seeding
        Dosen::truncate(); // Akan menghapus semua data di tabel dosen

        $dosens = [
            [
                'nama_dosen' => 'Dr. Andi Setiawan',
                'nip' => '197812312019031001',
                'alamat' => 'Jl. Merpati No. 10, Surabaya',
                'no_hp' => '081234567890',
            ],
            [
                'nama_dosen' => 'Prof. Siti Nurhaliza',
                'nip' => '196511221990021002',
                'alamat' => 'Jl. Kenari No. 5, Malang',
                'no_hp' => '082198765432',
            ],
            [
                'nama_dosen' => 'Dr. Budi Santoso',
                'nip' => '198004152005011003',
                'alamat' => 'Jl. Anggrek No. 15, Jakarta',
                'no_hp' => '083112345678',
            ],
        ];

        foreach ($dosens as $dosen) {
            Dosen::create($dosen);
        }
    }
}
