<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DestinationController;
use App\Http\Controllers\Api\Admin\VerificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// == RUTE OTENTIKASI ==
Route::post('/register', [AuthController::class, 'register']);
Route::post('/register/pengelola', [AuthController::class, 'registerPengelola']);
Route::post('/login', [AuthController::class, 'login']);

// == RUTE PUBLIK (TIDAK PERLU LOGIN) ==
Route::get('/destinations', [DestinationController::class, 'index']);
Route::get('/destinations/{destination}', [DestinationController::class, 'show']);

// == RUTE YANG MEMERLUKAN LOGIN (TERPROTEKSI) ==
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rute Destinasi yang butuh login (membuat, update, hapus)
    Route::post('/destinations', [DestinationController::class, 'store']);
    Route::put('/destinations/{destination}', [DestinationController::class, 'update']);
    Route::delete('/destinations/{destination}', [DestinationController::class, 'destroy']);

    // Grup Rute Khusus Admin
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/verifications', [VerificationController::class, 'index']);
        Route::post('/verifications/{id}/approve', [VerificationController::class, 'approve']);
        Route::post('/verifications/{id}/reject', [VerificationController::class, 'reject']);
    });
});
