<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Frs;

class FrsSeeder extends Seeder
{
    public function run()
    {
        Frs::create([
            'id_mahasiswa' => 1,
            'tahun_ajaran' => '2024/2025',
            'semester' => 'Genap',
            'total_sks' => 20,
            'ips' => 3.5,
            'ipk' => 3.7,
            'tgl_pengisian' => now(),
            'tgl_perubahan' => now(),
            'tgl_drop' => null
        ]);
    }
}
