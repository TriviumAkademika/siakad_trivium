<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Waktu;

class WaktuSeeder extends Seeder
{
    public function run()
    {
        $waktus = [
            ['hari' => 'Senin', 'jam_mulai' => '09:40', 'jam_selesai' => '12:10'],
            ['hari' => 'Senin', 'jam_mulai' => '13:00', 'jam_selesai' => '15:30'],
            ['hari' => 'Selasa', 'jam_mulai' => '10:30', 'jam_selesai' => '13:50'],
            ['hari' => 'Selasa', 'jam_mulai' => '13:50', 'jam_selesai' => '16:20'],
            ['hari' => 'Rabu', 'jam_mulai' => '08:50', 'jam_selesai' => '11:20'],
            ['hari' => 'Rabu', 'jam_mulai' => '11:20', 'jam_selesai' => '13:50'],
            ['hari' => 'Rabu', 'jam_mulai' => '13:50', 'jam_selesai' => '16:20'],
            ['hari' => 'Kamis', 'jam_mulai' => '08:00', 'jam_selesai' => '09:40'],
            ['hari' => 'Kamis', 'jam_mulai' => '13:00', 'jam_selesai' => '15:30'],
            ['hari' => 'Jumat', 'jam_mulai' => '08:00', 'jam_selesai' => '09:40'],
        ];

        foreach ($waktus as $waktu) {
            Waktu::create($waktu);
        }
    }
}
