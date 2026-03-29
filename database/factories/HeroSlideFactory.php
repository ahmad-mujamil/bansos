<?php

namespace Database\Factories;

use App\Models\HeroSlide;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HeroSlide>
 */
class HeroSlideFactory extends Factory
{
    protected $model = HeroSlide::class;

    public function definition(): array
    {
        return [
            'kategori' => 'Program Pemerintah Resmi',
            'judul' => 'Gotong Royong Membangun',
            'judul_sorot' => 'Kesejahteraan.',
            'subtitle' => 'Akses layanan bantuan sosial terpadu untuk masyarakat Indonesia. Transparan, akuntabel, dan tepat sasaran bagi yang membutuhkan.',
            'urutan' => 0,
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
