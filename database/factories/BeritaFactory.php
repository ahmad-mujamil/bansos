<?php

namespace Database\Factories;

use App\Models\Berita;
use App\Models\KategoriBerita;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Berita>
 */
class BeritaFactory extends Factory
{
    protected $model = Berita::class;

    public function definition(): array
    {
        $judul = fake()->sentence(6);

        return [
            'judul' => $judul,
            'slug' => Berita::generateUniqueSlug($judul),
            'kategori_berita_id' => KategoriBerita::factory(),
            'ringkasan' => fake()->text(160),
            'konten' => '<p>'.fake()->paragraphs(3, true).'</p>',
            'published_at' => now()->subDay(),
            'user_id' => null,
        ];
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'published_at' => null,
        ]);
    }

    public function forUser(?User $user = null): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user?->id,
        ]);
    }
}
