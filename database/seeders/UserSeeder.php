<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $mhs = Mahasiswa::create([
            'id_kelas' => 1,
            'nama' => 'Karina Mhs',
            'nrp' => '20230001',
            'semester' => '6',
            'gender' => 'P',
            'alamat' => 'Bandung',
            'no_hp' => '08123456789',
        ]);

        User::create([
            'email' => 'mhs@example.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'id_mahasiswa' => $mhs->id_mahasiswa,
        ]);

        $dsn = Dosen::create([
            'nama_dosen' => 'Pak Dosen',
            'nip' => '197012312021',
            'alamat' => 'Jakarta',
            'no_hp' => '08987654321',
        ]);

        User::create([
            'email' => 'dosen@example.com',
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'id_dosen' => $dsn->id_dosen,
        ]);
    }
}
