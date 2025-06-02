<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\nilai as NilaiModel; // Sesuai dengan model Anda
use App\Models\Mahasiswa;
use App\Models\Matkul;

class NilaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar ID Mahasiswa yang relevan dari UserSeeder Anda
        // UserSeeder mencari Mahasiswa dengan ID: 22 (selviea), 23 (boluea), 1 (alfian), 2 (renata), 3 (ghazali)
        $mahasiswa_ids_from_user_seeder = [22, 23, 1, 2, 3];

        // Daftar ID Matakuliah yang mungkin (berdasarkan urutan pembuatan di MatkulSeeder)
        // Kita asumsikan ID matakuliah dimulai dari 1 dan berurutan.
        // Contoh:
        // ID 1: 'Workshop Desain Pengalaman Pengguna'
        // ID 2: 'Workshop Pemrograman Perangkat Bergerak'
        // ID 3: 'Workshop Pemrograman Framework'
        // ID 8: 'Kecerdasan Buatan'
        // ID 10: 'Bahasa Indonesia'
        // ID 11: 'Manajemen Proyek TI'
        // ID 17: 'Pemrograman Web Lanjut'
        $target_matakuliah_ids = [1, 2, 3, 8, 10, 11, 17];

        $possible_grades = ['A', 'B', 'C', 'D', 'E']; // Sesuai validasi di controller Anda

        foreach ($mahasiswa_ids_from_user_seeder as $mhs_id) {
            $mahasiswa = Mahasiswa::find($mhs_id);

            // Lewati jika mahasiswa tidak ditemukan
            if (!$mahasiswa) {
                $this->command->warn("NilaiSeeder: Mahasiswa dengan ID {$mhs_id} tidak ditemukan. Data nilai untuk mahasiswa ini dilewati.");
                continue;
            }

            // Setiap mahasiswa akan mendapatkan nilai untuk beberapa matakuliah (misalnya 2 atau 3)
            // Ambil beberapa matakuliah secara acak dari target_matakuliah_ids
            // Pastikan jumlah yang diambil tidak melebihi jumlah matakuliah yang ada
            $num_matkuls_for_student = min(rand(2, 3), count($target_matakuliah_ids));
            $selected_matkul_keys = array_rand($target_matakuliah_ids, $num_matkuls_for_student);
            
            // Jika array_rand hanya mengembalikan satu kunci (integer)
            if (!is_array($selected_matkul_keys)) {
                $selected_matkul_keys = [$selected_matkul_keys];
            }


            foreach ($selected_matkul_keys as $key) {
                $mk_id = $target_matakuliah_ids[$key];
                $matkul = Matkul::find($mk_id);

                // Lewati jika matakuliah tidak ditemukan
                if (!$matkul) {
                    $this->command->warn("NilaiSeeder: Matakuliah dengan ID {$mk_id} tidak ditemukan. Data nilai untuk matakuliah ini dilewati.");
                    continue;
                }

                // Buat nilai UTS
                NilaiModel::updateOrCreate(
                    [
                        'mahasiswa_id'  => $mahasiswa->id_mahasiswa,
                        'matakuliah_id' => $matkul->id_matkul,
                        'jenis_nilai'   => 'UTS',
                    ],
                    [
                        'nilai'         => $possible_grades[array_rand($possible_grades)],
                    ]
                );

                // Buat nilai UAS (dengan kemungkinan 70% mahasiswa sudah memiliki nilai UAS)
                if (rand(1, 10) <= 7) {
                    NilaiModel::updateOrCreate(
                        [
                            'mahasiswa_id'  => $mahasiswa->id_mahasiswa,
                            'matakuliah_id' => $matkul->id_matkul,
                            'jenis_nilai'   => 'UAS',
                        ],
                        [
                            'nilai'         => $possible_grades[array_rand($possible_grades)],
                        ]
                    );
                }
            }
        }

        // Anda juga bisa menambahkan data nilai yang sangat spesifik di sini jika diperlukan
        // Contoh: Memastikan mahasiswa 'selviea' (ID 22) mendapat nilai 'A' di 'Workshop Desain Pengalaman Pengguna' (ID 1)
        $selviea = Mahasiswa::find(22);
        $wdpp = Matkul::find(1);
        if ($selviea && $wdpp) {
            NilaiModel::updateOrCreate(
                ['mahasiswa_id' => 22, 'matakuliah_id' => 1, 'jenis_nilai' => 'UTS'],
                ['nilai' => 'A']
            );
            NilaiModel::updateOrCreate(
                ['mahasiswa_id' => 22, 'matakuliah_id' => 1, 'jenis_nilai' => 'UAS'],
                ['nilai' => 'A']
            );
        }
        
        $alfian = Mahasiswa::find(1);
        $kb = Matkul::find(8); // Kecerdasan Buatan
        if ($alfian && $kb) {
            NilaiModel::updateOrCreate(
                ['mahasiswa_id' => 1, 'matakuliah_id' => 8, 'jenis_nilai' => 'UTS'],
                ['nilai' => 'B']
            );
             // Biarkan UAS Alfian untuk KB tidak ada, sebagai variasi
        }


        $this->command->info('NilaiSeeder berhasil dijalankan dan data nilai dummy telah dibuat.');
    }
}
