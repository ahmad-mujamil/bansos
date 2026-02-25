<?php

use Illuminate\Support\Facades\Route;

route::redirect('/', '/login');
Auth::routes([
    "register" => false,
    "confirm" => false,
    "reset" => false
]);

// Registration Routes
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

Route::group(['middleware' => ['auth:web', 'check.perorangan.detail', 'ensure.user.active']], function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // User detail (lengkapi data diri untuk user yang belum verifikasi)
    Route::get('/user-detail', [App\Http\Controllers\UserDetailController::class, 'create'])->name('user-detail.create');
    Route::post('/user-detail', [App\Http\Controllers\UserDetailController::class, 'store'])->name('user-detail.store');
    Route::put('/user-detail', [App\Http\Controllers\UserDetailController::class, 'update'])->name('user-detail.update');
    //PROFILE
    Route::get('/my-profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/my-profile', [App\Http\Controllers\ProfileController::class, 'updateProfile'])->name('profile.update');
    //SECURITY
    Route::get('/my-profile/security', [App\Http\Controllers\ProfileController::class, 'security'])->name('security.index');
    Route::put('/my-profile/security/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('password.update');


    //USERS
    Route::middleware(['role:super'])->group(function () {
        Route::resource('pengguna', App\Http\Controllers\PenggunaController::class);
    });

    // Verifikasi Pengguna (user belum aktif)
    Route::middleware(['role:super,admin'])->group(function () {
        Route::get('verifikasi-pengguna', [App\Http\Controllers\VerifikasiPenggunaController::class, 'index'])->name('verifikasi-pengguna.index');
        Route::get('verifikasi-pengguna/{user}/aktifkan', [App\Http\Controllers\VerifikasiPenggunaController::class, 'aktifkan'])->name('verifikasi-pengguna.aktifkan');
        Route::get('verifikasi-pengguna/{user}', [App\Http\Controllers\VerifikasiPenggunaController::class, 'show'])->name('verifikasi-pengguna.show');
    });

    //MASTER DATA
    Route::middleware(['role:super,admin'])->group(function () {
        Route::resource('kecamatan', App\Http\Controllers\KecamatanController::class);
        Route::resource('desa', App\Http\Controllers\DesaController::class);
        Route::resource('penduduk', App\Http\Controllers\PendudukController::class);
        Route::resource('opd', App\Http\Controllers\OpdController::class);
        Route::resource('jenis-bantuan', App\Http\Controllers\JenisBantuanController::class);
        Route::resource('kelompok-masyarakat', App\Http\Controllers\KelompokMasyarakatController::class);
        Route::resource('kelompok-masyarakat.anggota', App\Http\Controllers\KelompokMasyarakatAnggotaController::class)->except(['show']);
        Route::resource('kelompok-masyarakat.dokumen', App\Http\Controllers\KelompokMasyarakatDokumenController::class)->except(['show']);
    });


});
