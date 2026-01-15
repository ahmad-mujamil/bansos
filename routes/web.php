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

Route::group(['middleware' => ['auth:web', 'check.perorangan.detail']], function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    //PROFILE
    Route::get('/my-profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/my-profile', [App\Http\Controllers\ProfileController::class, 'updateProfile'])->name('profile.update');
    //SECURITY
    Route::get('/my-profile/security', [App\Http\Controllers\ProfileController::class, 'security'])->name('security.index');
    Route::put('/my-profile/security/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('password.update');

    //PERORANGAN DETAIL
    Route::get('/perorangan-detail/create', [App\Http\Controllers\PeroranganDetailController::class, 'create'])->name('perorangan-detail.create');
    Route::post('/perorangan-detail', [App\Http\Controllers\PeroranganDetailController::class, 'store'])->name('perorangan-detail.store');
    Route::get('/perorangan-detail/edit', [App\Http\Controllers\PeroranganDetailController::class, 'edit'])->name('perorangan-detail.edit');
    Route::put('/perorangan-detail', [App\Http\Controllers\PeroranganDetailController::class, 'update'])->name('perorangan-detail.update');

    //USERS
    Route::middleware(['role:super'])->group(function () {
        Route::resource('pengguna', App\Http\Controllers\PenggunaController::class);
    });

    //MASTER DATA
    Route::middleware(['role:super'])->group(function () {
        Route::resource('kecamatan', App\Http\Controllers\KecamatanController::class);
        Route::resource('desa', App\Http\Controllers\DesaController::class);
    });

});
