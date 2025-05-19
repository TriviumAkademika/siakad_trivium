<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ruangan;

class RuanganSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nama_ruangan' => 'Lab Komputer 1', 'nama_gedung' => 'Gedung D4', 'kode_ruangan' => 'C106'],
            ['nama_ruangan' => 'Lab Komputer 2', 'nama_gedung' => 'Gedung D4', 'kode_ruangan' => 'C206'],
            ['nama_ruangan' => 'Lab Komputer 3', 'nama_gedung' => 'Gedung D4', 'kode_ruangan' => 'C303'],
            ['nama_ruangan' => 'Lab Jaringan', 'nama_gedung' => 'Gedung D4', 'kode_ruangan' => 'C307'],        
            ['nama_ruangan' => 'Lab Komputer 4', 'nama_gedung' => 'Gedung D4', 'kode_ruangan' => 'C203'],          
            ['nama_ruangan' => 'Auditorium', 'nama_gedung' => 'Gedung D3', 'kode_ruangan' => 'HH101'],          
            ['nama_ruangan' => 'Ruang Kuliah', 'nama_gedung' => 'Gedung D4', 'kode_ruangan' => 'B302'],
        ];

        foreach ($data as $ruangan) {
            Ruangan::create($ruangan);
        }
    }
}
