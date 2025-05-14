<?php

namespace Database\Seeders;

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
        $jadwals = [
            ['id_kelas' => 1, 'id_matkul' => 1, 'id_dosen' => 2 , 'id_waktu' => 1,'id_ruangan' => 1,],
            ['id_kelas' => 1, 'id_matkul' => 2, 'id_dosen' => 2 , 'id_waktu' => 2,'id_ruangan' => 1,],
            ['id_kelas' => 1, 'id_matkul' => 3, 'id_dosen' => 2 , 'id_waktu' => 3,'id_ruangan' => 1,],
            ['id_kelas' => 1, 'id_matkul' => 4, 'id_dosen' => 2 , 'id_waktu' => 4,'id_ruangan' => 1,],
            ['id_kelas' => 1, 'id_matkul' => 5, 'id_dosen' => 2 , 'id_waktu' => 5,'id_ruangan' => 1,],
            ['id_kelas' => 1, 'id_matkul' => 6, 'id_dosen' => 2 , 'id_waktu' => 6,'id_ruangan' => 1,],
            ['id_kelas' => 1, 'id_matkul' => 7, 'id_dosen' => 2 , 'id_waktu' => 7,'id_ruangan' => 1,],
            ['id_kelas' => 1, 'id_matkul' => 8, 'id_dosen' => 2 , 'id_waktu' => 8,'id_ruangan' => 1,],
            ['id_kelas' => 1, 'id_matkul' => 9, 'id_dosen' => 2 , 'id_waktu' => 9,'id_ruangan' => 1,],
            ['id_kelas' => 1, 'id_matkul' => 10, 'id_dosen' => 2 , 'id_waktu' => 10,'id_ruangan' => 1,],
        ];

        foreach ($jadwals as $jadwal) {
            Jadwal::create($jadwal);
        }
        // $kelasIds = Kelas::pluck('id_kelas')->all();
        // $matkulIds = Matkul::pluck('id_matkul')->all();
        // $dosenIds = Dosen::pluck('id_dosen')->all();
        // $waktuIds = Waktu::pluck('id_waktu')->all();
        // $ruanganIds = Ruangan::pluck('id_ruangan')->all();

        // // Untuk mencatat kombinasi waktu + ruangan yang sudah dipakai
        // $usedSlots = [];

        // // Tentukan berapa banyak jadwal yang ingin kamu buat
        // $jumlahJadwal = 20;

        // for ($i = 0; $i < $jumlahJadwal; $i++) {
        //     $attempt = 0;
        //     do {
        //         $waktuId = fake()->randomElement($waktuIds);
        //         $ruanganId = fake()->randomElement($ruanganIds);
        //         $key = $waktuId . '-' . $ruanganId;
        //         $attempt++;
        //     } while (in_array($key, $usedSlots) && $attempt < 20);

        //     if ($attempt >= 20) {
        //         // Gagal menemukan kombinasi unik, skip iterasi
        //         continue;
        //     }

        //     // Tandai kombinasi waktu-ruangan ini sebagai sudah digunakan
        //     $usedSlots[] = $key;

        //     Jadwal::create([
        //         'id_kelas' => fake()->randomElement($kelasIds),
        //         'id_matkul' => fake()->randomElement($matkulIds),
        //         'id_dosen' => fake()->randomElement($dosenIds),
        //         'id_waktu' => $waktuId,
        //         'id_ruangan' => $ruanganId,
        //     ]);
        // }
    }
}
