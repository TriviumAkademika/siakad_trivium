<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Berita>
 */
class BeritaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'gambar' => $this->faker->imageUrl(),
            'isi_berita' => $this->faker->paragraph(),
            'judul' => $this->faker->sentence(),
            'tanggal' => $this->faker->date(),
            'penulis' => $this->faker->name(),
        ];
    }
}
