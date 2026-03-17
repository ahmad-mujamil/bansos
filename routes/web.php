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

    // Kelompok baru dari user-detail (is_active = false), lengkapi anggota & dokumen
    Route::post('/user-detail/kelompok', [App\Http\Controllers\UserDetailController::class, 'storeKelompok'])->name('user-detail.kelompok.store');
    Route::get('/user-detail/kelompok/{organisasi}/lengkapi', [App\Http\Controllers\UserDetailKelompokController::class, 'lengkapi'])->name('user-detail.kelompok.lengkapi');
    Route::post('/user-detail/kelompok/{organisasi}/anggota', [App\Http\Controllers\UserDetailKelompokController::class, 'storeAnggota'])->name('user-detail.kelompok.anggota.store');
    Route::match(['DELETE'], '/user-detail/kelompok/{organisasi}/anggota/{anggota}', [App\Http\Controllers\UserDetailKelompokController::class, 'destroyAnggota'])->name('user-detail.kelompok.anggota.destroy');
    Route::post('/user-detail/kelompok/{organisasi}/dokumen', [App\Http\Controllers\UserDetailKelompokController::class, 'storeDokumen'])->name('user-detail.kelompok.dokumen.store');
    Route::match(['DELETE'], '/user-detail/kelompok/{organisasi}/dokumen/{dokumen}', [App\Http\Controllers\UserDetailKelompokController::class, 'destroyDokumen'])->name('user-detail.kelompok.dokumen.destroy');

    // Pengajuan (user)
    Route::get('/pengajuan', [App\Http\Controllers\PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/create', [App\Http\Controllers\PengajuanController::class, 'create'])->name('pengajuan.create');
    Route::post('/pengajuan', [App\Http\Controllers\PengajuanController::class, 'store'])->name('pengajuan.store');
    Route::get('/pengajuan/{pengajuan}', [App\Http\Controllers\PengajuanController::class, 'show'])->name('pengajuan.show');
    Route::get('/pengajuan/{pengajuan}/edit', [App\Http\Controllers\PengajuanController::class, 'edit'])->name('pengajuan.edit');
    Route::put('/pengajuan/{pengajuan}', [App\Http\Controllers\PengajuanController::class, 'update'])->name('pengajuan.update');
    Route::post('/pengajuan/{pengajuan}/submit', [App\Http\Controllers\PengajuanController::class, 'submit'])->name('pengajuan.submit');
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
