<?php

namespace Database\Factories;

use App\Models\KategoriBerita;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KategoriBerita>
 */
class KategoriBeritaFactory extends Factory
{
    protected $model = KategoriBerita::class;

    public function definition(): array
    {
        return [
            'nama' => fake()->unique()->words(2, true),
        ];
    }
}
