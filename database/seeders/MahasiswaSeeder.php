<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['3123500031', 'Bagus Alfian', 'L'],
            ['3123500032', 'Muhammad Renata Mashanda Putra', 'L'],
            ['3123500033', 'Muhammad Ghazali Nur Rahman', 'L'],
            ['3123500034', 'Riki Aditya Ramadhan', 'L'],
            ['3123500035', 'Neofano Dewanto Nugroho', 'L'],
            ['3123500036', 'Muhammad Fikri Abiyyu', 'L'],
            ['3123500037', 'Aldo Marsendo', 'L'],
            ['3123500038', 'Adrian Sondang Irawan', 'L'],
            ['3123500039', 'Rasydatur Rahma', 'P'],
            ['3123500040', 'Yildisray Rafi Putra', 'L'],
            ['3123500041', 'Muhammad Bagas Prayogi', 'L'],
            ['3123500042', 'Rasyid Ridho', 'L'],
            ['3123500043', 'Mohammad Hilmi Afifi', 'L'],
            ['3123500044', 'Reno Naufal Hanggara', 'L'],
            ['3123500045', 'Redian Rasigio Indra Purnomo', 'L'],
            ['3123500046', 'Zubairi Rahman', 'L'],
            ['3123500047', 'Zhulva Priya Abhipraya', 'L'],
            ['3123500048', 'Aura Sasi Kirana Dharma Acintya', 'P'],
            ['3123500049', 'Gumiwang Gde Derazatna', 'L'],
            ['3123500052', 'Pradita Arif Setiawan', 'L'],
            ['3123500053', 'Tiara Vani Putri Herlambang', 'P'],
            ['3123500054', 'Selvi Riska Nisa', 'P'],
            ['3123500055', 'Salsabila Nurhalimah', 'P'],
            ['3123500056', 'Muhammad Raihan', 'L'],
            ['3123500057', 'Dominikus Kawengdem', 'L'],
            ['3123500058', 'Jauharotun Nafisah', 'P'],
            ['3123500059', 'Muhammad Taufiqurridwan', 'L'],
            ['3123500060', 'Rizal Risqi Ahmad Dinata', 'L'],
        ];

        foreach ($data as [$nrp, $nama, $gender]) {
            Mahasiswa::create([
                'id_kelas' => 1,
                'nama' => $nama,
                'nrp' => $nrp,
                'semester' => 4,
                'gender' => $gender,
                'alamat' => fake()->address(),
                'no_hp' => $this->generateIndoPhone(),
            ]);
        }
    }
    private function generateIndoPhone(): string
    {
        $prefixes = ['08', '+628'];
        $prefix = fake()->randomElement($prefixes);
        $number = '';

        // generate 9 to 10 digits after prefix
        for ($i = 0; $i < rand(9, 10); $i++) {
            $number .= rand(0, 9);
        }

        return $prefix . $number;
    }
}
