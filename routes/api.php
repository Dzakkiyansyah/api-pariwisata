<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DestinationController;
use App\Http\Controllers\Api\Admin\VerificationController;
use App\Http\Controllers\Api\Admin\NewsController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\BookmarkController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\DestinationPhotoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// -- RUTE OTENTIKASI --
Route::post('/register', [AuthController::class, 'register']);
Route::post('/register/pengelola', [AuthController::class, 'registerPengelola']);
Route::post('/login', [AuthController::class, 'login']);

// -- RUTE PUBLIK (TIDAK PERLU LOGIN) --
Route::get('/destinations', [DestinationController::class, 'index']);
Route::get('/destinations/{destination}', [DestinationController::class, 'show']);

// -- RUTE YANG MEMERLUKAN LOGIN (TERPROTEKSI) --
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });


    // --- RUTE BARU UNTUK PROFIL PENGGUNA ---
    Route::get('/profile', [ProfileController::class, 'show']); // Melihat profil
    Route::put('/profile', [ProfileController::class, 'update']); // Mengupdate profil
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']); // Mengupdate password
    // ------------------------------------

    // Rute Destinasi (membuat, update, hapus)
    Route::post('/destinations', [DestinationController::class, 'store']);
    Route::put('/destinations/{destination}', [DestinationController::class, 'update']);
    Route::delete('/destinations/{destination}', [DestinationController::class, 'destroy']);

    // Rute Ulasan
    Route::post('/destinations/{destination}/reviews', [ReviewController::class, 'store']);

    // Rute Bookmark
    Route::get('/bookmarks', [BookmarkController::class, 'index']);
    Route::post('/destinations/{destination}/bookmark', [BookmarkController::class, 'store']);
    Route::delete('/destinations/{destination}/bookmark', [BookmarkController::class, 'destroy']);

    // --- RUTE BARU UNTUK GALERI FOTO ---
    Route::post('/destinations/{destination}/photos', [DestinationPhotoController::class, 'store']);
    Route::delete('/destination-photos/{photo}', [DestinationPhotoController::class, 'destroy']);
    // ------------------------------------

    // Grup Rute Khusus Admin
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/verifications', [VerificationController::class, 'index']);
        Route::post('/verifications/{id}/approve', [VerificationController::class, 'approve']);
        Route::post('/verifications/{id}/reject', [VerificationController::class, 'reject']);

        // Rute Berita
        Route::apiResource('news', NewsController::class);

        // Rute kategori
        Route::apiResource('categories', CategoryController::class);

        // Rute baru untuk Manajemen Pengguna
         Route::apiResource('users', UserController::class);
    });
});
