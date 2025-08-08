<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\VerificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// rute untuk registrasi wisatawan
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// rute untuk registrasi pengelola
Route::post('/register/pengelola', [AuthController::class, 'registrasiPengelola']);

// rute verifikasi admin
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/verifications', [VerificationController::class, 'index']);
    Route::post('/verifications/{id}/approve', [VerificationController::class, 'approve']);
    Route::post('/verifications/{id}/reject', [VerificationController::class, 'reject']);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
