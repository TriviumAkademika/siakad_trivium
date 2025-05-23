<?php

namespace Database\Seeders;

use App\Models\DetailFrs;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            DosenSeeder::class,
            MatkulSeeder::class,
            WaktuSeeder::class,
            RuanganSeeder::class,
            KelasSeeder::class,
            MahasiswaSeeder::class,
            JadwalSeeder::class,
            FrsSeeder::class,
            DetailFrsSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,

        ]);
    }
}
