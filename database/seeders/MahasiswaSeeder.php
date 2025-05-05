<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        Mahasiswa::create([
            'id_kelas' => 1,
            'nama' => 'Andi Wijaya',
            'nrp' => '05111740000001',
            'semester' => '6',
            'gender' => 'L',
            'alamat' => 'Jl. Merpati No. 123, Surabaya',
            'no_hp' => '081234567890',
        ]);
    }
}
