<?php

use Illuminate\Support\Facades\Route;

route::redirect('/', '/login');
Auth::routes([
    "register" => false,
    "confirm" => false,
    "reset" => false
]);

Route::group(['middleware' => ['auth:web']], function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
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

});
