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
            $user = User::create([
                'email' => 'selviea@student.trivium.ac.id',
                'password' => Hash::make('selviea123'),
                'role' => 'mahasiswa',
                'id_mahasiswa' => $mhs1->id_mahasiswa,
            ]);
            $user->assignRole('mahasiswa');
        }

        $mhs2 = Mahasiswa::find(23);
        if ($mhs2) {
            $user = User::create([
                'email' => 'boluea@student.trivium.ac.id',
                'password' => Hash::make('rikuchan'),
                'role' => 'mahasiswa',
                'id_mahasiswa' => $mhs2->id_mahasiswa,
            ]);
            $user->assignRole('mahasiswa');
        }

        // ==== DOSEN ====

        $dsn1 = Dosen::find(1);
        if ($dsn1) {
            $user = User::create([
                'email' => 'budi@lecture.trivium.ac.id',
                'password' => Hash::make('budi123'),
                'role' => 'dosen',
                'id_dosen' => $dsn1->id_dosen,
            ]);
            $user->assignRole('dosen');
        }

        $dsn2 = Dosen::find(2);
        if ($dsn2) {
            $user = User::create([
                'email' => 'siti@lecture.trivium.ac.id',
                'password' => Hash::make('siti123'),
                'role' => 'dosen',
                'id_dosen' => $dsn2->id_dosen,
            ]);
            $user->assignRole('dosen');
        }

        // ==== ADMIN ====

        $admin1 = User::create([
            'email' => 'adminnisa@trivium.ac.id',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'name' => 'Admin Nisa',
        ]);
        $admin1->assignRole('admin');

        $admin2 = User::create([
            'email' => 'adminrahma@trivium.ac.id',
            'password' => Hash::make('admin123'),
            'role' => 'mahasiswa',
            'name' => 'Admin Rahma',
        ]);
        $admin2->assignRole('admin');

        $admin3 = User::create([
            'email' => 'akaashi@trivium.ac.id',
            'password' => Hash::make('akaashi'),
            'role' => 'admin',
            'name' => 'Admin Akaashi',
        ]);
        $admin3->assignRole('admin');
    }
}
