<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ruangan;

class RuanganSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nama_ruangan' => 'Lab Komputer 1', 'nama_gedung' => 'Gedung A', 'kode_ruangan' => 'A101'],
            ['nama_ruangan' => 'Lab Bahasa', 'nama_gedung' => 'Gedung B', 'kode_ruangan' => 'B202'],
            ['nama_ruangan' => 'Ruang Kuliah Umum', 'nama_gedung' => 'Gedung C', 'kode_ruangan' => 'C303'],
        ];

        foreach ($data as $ruangan) {
            Ruangan::create($ruangan);
        }
    }
}
