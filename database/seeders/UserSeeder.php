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
        // ==== MAHASISWA ====

        $mhs1 = Mahasiswa::find(22); // Ganti ID sesuai dengan data Anda
        if ($mhs1) {
            User::create([
                'email' => 'selviea@student.trivium.ac.id',
                'password' => Hash::make('selviea123'),
                'role' => 'mahasiswa',
                'id_mahasiswa' => $mhs1->id_mahasiswa,
            ]);
        }

        $mhs2 = Mahasiswa::find(23);
        if ($mhs2) {
            User::create([
                'email' => 'boluea@student.trivium.ac.id',
                'password' => Hash::make('budi456'),
                'role' => 'mahasiswa',
                'id_mahasiswa' => $mhs2->id_mahasiswa,
            ]);
        }

        // ==== DOSEN ====

        $dsn1 = Dosen::find(1);
        if ($dsn1) {
            User::create([
                'email' => 'budi@lecture.trivium.ac.id',
                'password' => Hash::make('budi123'),
                'role' => 'dosen',
                'id_dosen' => $dsn1->id_dosen,
            ]);
        }

        $dsn2 = Dosen::find(2);
        if ($dsn2) {
            User::create([
                'email' => 'siti@lecture.trivium.ac.id',
                'password' => Hash::make('siti123'),
                'role' => 'dosen',
                'id_dosen' => $dsn2->id_dosen,
            ]);
        }

        // ==== ADMIN ====

        User::create([
            'email' => 'adminnisa@trivium.ac.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'nama_user' => 'Admin Nisa',
        ]);

        User::create([
            'email' => 'adminrahma@trivium.ac.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'nama_user' => 'Admin Rahma',
        ]);

        User::create([
            'email' => 'akaashi@trivium.ac.id',
            'password' => Hash::make('akaashi'),
            'role' => 'admin',
            'nama_user' => 'Admin Akaashi',
        ]);
    }
}
