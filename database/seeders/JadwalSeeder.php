<?php

namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Matkul;
use App\Models\Dosen;
use App\Models\Waktu;
use App\Models\Ruangan;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        $kelasIds = Kelas::pluck('id_kelas')->all();
        $matkulIds = Matkul::pluck('id_matkul')->all();
        $dosenIds = Dosen::pluck('id_dosen')->all();
        $waktuIds = Waktu::pluck('id_waktu')->all();
        $ruanganIds = Ruangan::pluck('id_ruangan')->all();

        foreach (range(1, 5) as $i) {
            Jadwal::create([
                'id_kelas' => fake()->randomElement($kelasIds),
                'id_matkul' => fake()->randomElement($matkulIds),
                'id_dosen' => fake()->randomElement($dosenIds),
                'id_waktu' => fake()->randomElement($waktuIds),
                'id_ruangan' => fake()->randomElement($ruanganIds),
            ]);
        }
    }
}
