<?php

use App\Enums\RoleUser;
use App\Models\HeroSlide;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('shows active hero slide content on the landing page', function () {
    $slide = HeroSlide::factory()->create(['judul' => 'Judul Slider Unik XYZ']);
    $slide->addMedia(UploadedFile::fake()->image('hero.jpg', 1200, 600))->toMediaCollection('hero');

    $this->get(route('landing'))
        ->assertSuccessful()
        ->assertSee('Judul Slider Unik XYZ', escape: false);
});

it('does not show inactive slides on the landing page', function () {
    HeroSlide::factory()->inactive()->create(['judul' => 'Slide Nonaktif ABC']);

    $this->get(route('landing'))
        ->assertSuccessful()
        ->assertDontSee('Slide Nonaktif ABC');
});

it('allows super admin to open kelola slider index', function () {
    $user = User::query()->create([
        'nama' => 'Super',
        'email' => 'super-slider@test.com',
        'username' => 'superslider',
        'password' => Hash::make('password'),
        'role' => RoleUser::SUPER,
        'is_active' => true,
    ]);

    $this->actingAs($user)->get(route('kelola.slider.index'))
        ->assertSuccessful();
});

it('allows super admin to store a hero slide with image', function () {
    $this->withoutMiddleware(ValidateCsrfToken::class);

    $user = User::query()->create([
        'nama' => 'Super',
        'email' => 'super-slider2@test.com',
        'username' => 'superslider2',
        'password' => Hash::make('password'),
        'role' => RoleUser::SUPER,
        'is_active' => true,
    ]);

    $file = UploadedFile::fake()->image('slide.jpg', 800, 400);

    $response = $this->actingAs($user)->post(route('kelola.slider.store'), [
        'kategori' => 'Program Resmi',
        'judul' => 'Judul dari form',
        'judul_sorot' => 'Aksen.',
        'subtitle' => 'Subtitle panjang untuk pengujian slider hero di beranda portal.',
        'urutan' => '2',
        'is_active' => '1',
        'gambar' => $file,
    ]);

    $response->assertRedirect(route('kelola.slider.index'));

    $slide = HeroSlide::query()->where('judul', 'Judul dari form')->first();
    expect($slide)->not->toBeNull();
    expect($slide->getFirstMedia('hero'))->not->toBeNull();
});

it('forbids masyarakat from accessing kelola slider', function () {
    $user = User::query()->create([
        'nama' => 'User',
        'email' => 'user-slider@test.com',
        'username' => 'userslider',
        'password' => Hash::make('password'),
        'role' => RoleUser::USER,
        'is_active' => true,
    ]);

    $this->actingAs($user)->get(route('kelola.slider.index'))
        ->assertUnauthorized();
});
