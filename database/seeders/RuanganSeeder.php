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

            // Tambahan 30 data baru
            ['nama_ruangan' => 'Ruang Seminar 1', 'nama_gedung' => 'Pascasarjana', 'kode_ruangan' => 'P101'],
            ['nama_ruangan' => 'Ruang Seminar 2', 'nama_gedung' => 'Pascasarjana', 'kode_ruangan' => 'P102'],
            ['nama_ruangan' => 'Ruang Dosen 1', 'nama_gedung' => 'Gedung D3', 'kode_ruangan' => 'D301'],
            ['nama_ruangan' => 'Ruang Dosen 2', 'nama_gedung' => 'Gedung D3', 'kode_ruangan' => 'D302'],
            ['nama_ruangan' => 'Lab Multimedia', 'nama_gedung' => 'Gedung D4', 'kode_ruangan' => 'C204'],
            ['nama_ruangan' => 'Ruang Diskusi', 'nama_gedung' => 'SAW', 'kode_ruangan' => 'SA101'],
            ['nama_ruangan' => 'Studio Rekaman', 'nama_gedung' => 'SAW', 'kode_ruangan' => 'SA102'],
            ['nama_ruangan' => 'Ruang Presentasi', 'nama_gedung' => 'Pascasarjana', 'kode_ruangan' => 'P103'],
            ['nama_ruangan' => 'Ruang Bimbingan', 'nama_gedung' => 'Pascasarjana', 'kode_ruangan' => 'P104'],
            ['nama_ruangan' => 'Ruang Kuliah A', 'nama_gedung' => 'Gedung D3', 'kode_ruangan' => 'B101'],
            ['nama_ruangan' => 'Ruang Kuliah B', 'nama_gedung' => 'Gedung D3', 'kode_ruangan' => 'B102'],
            ['nama_ruangan' => 'Lab AI', 'nama_gedung' => 'Gedung D4', 'kode_ruangan' => 'C308'],
            ['nama_ruangan' => 'Lab Mobile', 'nama_gedung' => 'Gedung D4', 'kode_ruangan' => 'C309'],
            ['nama_ruangan' => 'Ruang Server', 'nama_gedung' => 'Gedung D4', 'kode_ruangan' => 'C310'],
            ['nama_ruangan' => 'Ruang Pengujian', 'nama_gedung' => 'Gedung D4', 'kode_ruangan' => 'C311'],
            ['nama_ruangan' => 'Lab Robotik', 'nama_gedung' => 'SAW', 'kode_ruangan' => 'SA103'],
            ['nama_ruangan' => 'Lab IoT', 'nama_gedung' => 'SAW', 'kode_ruangan' => 'SA104'],
            ['nama_ruangan' => 'Ruang Konsultasi', 'nama_gedung' => 'Pascasarjana', 'kode_ruangan' => 'P105'],
            ['nama_ruangan' => 'Lab Data Science', 'nama_gedung' => 'Gedung D4', 'kode_ruangan' => 'C312'],
            ['nama_ruangan' => 'Lab Cloud Computing', 'nama_gedung' => 'Gedung D4', 'kode_ruangan' => 'C313'],
            ['nama_ruangan' => 'Lab Cyber Security', 'nama_gedung' => 'Gedung D4', 'kode_ruangan' => 'C314'],
            ['nama_ruangan' => 'Ruang Rapat 1', 'nama_gedung' => 'Gedung D3', 'kode_ruangan' => 'D303'],
            ['nama_ruangan' => 'Ruang Rapat 2', 'nama_gedung' => 'Gedung D3', 'kode_ruangan' => 'D304'],
            ['nama_ruangan' => 'Ruang Tunggu', 'nama_gedung' => 'Pascasarjana', 'kode_ruangan' => 'P106'],
            ['nama_ruangan' => 'Studio Podcast', 'nama_gedung' => 'SAW', 'kode_ruangan' => 'SA105'],
            ['nama_ruangan' => 'Ruang Editing', 'nama_gedung' => 'SAW', 'kode_ruangan' => 'SA106'],
            ['nama_ruangan' => 'Ruang Audio Visual', 'nama_gedung' => 'SAW', 'kode_ruangan' => 'SA107'],
            ['nama_ruangan' => 'Lab Software Engineering', 'nama_gedung' => 'Gedung D4', 'kode_ruangan' => 'C315'],
            ['nama_ruangan' => 'Ruang Project Mahasiswa', 'nama_gedung' => 'Gedung D4', 'kode_ruangan' => 'C316'],
            ['nama_ruangan' => 'Ruang Simulasi', 'nama_gedung' => 'Gedung D3', 'kode_ruangan' => 'B201'],
            ['nama_ruangan' => 'Ruang Pameran', 'nama_gedung' => 'Pascasarjana', 'kode_ruangan' => 'P107'],
        ];

        foreach ($data as $ruangan) {
            Ruangan::create($ruangan);
        }
    }
}
