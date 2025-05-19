<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetailFrs;

class DetailFrsSeeder extends Seeder
{
    public function run()
    {
        // Contoh data dummy, sesuaikan dengan id jadwal dan id frs yang valid di database kamu
        $data = [
            [
                'id_jadwal' => 1,
                'id_frs' => 1,
                'status' => true,
            ],
            [
                'id_jadwal' => 2,
                'id_frs' => 1,
                'status' => false,
            ],
            [
                'id_jadwal' => 3,
                'id_frs' => 2,
                'status' => true,
            ],
            // tambahkan data lainnya sesuai kebutuhan
        ];

        foreach ($data as $item) {
            DetailFrs::create($item);
        }
    }
}
