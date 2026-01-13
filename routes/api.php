<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::prefix('v1')->group(function () {
    Route::get('/classroom', [ApiController::class, 'getClassroom'])->name('api.classroom.index');
    Route::get('/classroom/{classroomId}/pasien', [ApiController::class, 'getPasienByClassroom'])->name('api.classroom.pasien');
    Route::post('/pasien/kirim', [ApiController::class, 'kirimPasien'])->name('api.pasien.kirim');
//    Route::get('/pasien/{id}', [ApiController::class, 'getPasien'])->name('api.pasien.show');
    Route::post('/analyze-pose', [ApiController::class, 'checkPose'])->name('api.check.pose');
});

