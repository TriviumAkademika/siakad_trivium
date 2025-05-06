<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Waktu;

class WaktuSeeder extends Seeder
{
    public function run()
    {
        $waktus = [
            ['hari' => 'Senin', 'jam_mulai' => '08:00', 'jam_selesai' => '10:00'],
            ['hari' => 'Senin', 'jam_mulai' => '10:15', 'jam_selesai' => '12:15'],
            ['hari' => 'Selasa', 'jam_mulai' => '13:00', 'jam_selesai' => '15:00'],
            ['hari' => 'Rabu',  'jam_mulai' => '14:40', 'jam_selesai' => '16:40'],
            ['hari' => 'Kamis', 'jam_mulai' => '07:30', 'jam_selesai' => '09:30'],
            ['hari' => 'Jumat', 'jam_mulai' => '09:00', 'jam_selesai' => '11:00'],
        ];

        foreach ($waktus as $waktu) {
            Waktu::create($waktu);
        }
    }
}
