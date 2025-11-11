<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsersController;
use App\Http\Controllers\JadwalPetugasController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\RatingPesananController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JenisServiceController;

// Public read-only endpoints
Route::apiResource('users', UsersController::class)->only(['index', 'show']);
Route::apiResource('jadwal-petugas', JadwalPetugasController::class)->only(['index', 'show']);
Route::apiResource('pemesanan', PemesananController::class)->only(['index', 'show']);
Route::apiResource('rating-pesanan', RatingPesananController::class)->only(['index', 'show']);
Route::apiResource('jenis-service', JenisServiceController::class)->only(['index', 'show']);

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/check-email', [AuthController::class, 'checkEmail']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    // Logout
    Route::post('auth/logout', [AuthController::class, 'logout']);

    // Protected write operations for resources
    Route::apiResource('users', UsersController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('jadwal-petugas', JadwalPetugasController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('pemesanan', PemesananController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('rating-pesanan', RatingPesananController::class)->only(['store', 'update', 'destroy']);
    Route::apiResource('jenis-service', JenisServiceController::class)->only(['store', 'update', 'destroy']);

    // Petugas Profile
    Route::get('check-petugas-profile', [UsersController::class, 'checkPetugasProfile']);
    Route::post('form-profile-petugas', [UsersController::class, 'storePetugasProfile']);
    
    // Booking Routes
    Route::prefix('bookings')->group(function () {
        Route::get('/', [PemesananController::class, 'index']);
        Route::post('/', [PemesananController::class, 'store']);
        Route::get('/{id}', [PemesananController::class, 'show']);
        Route::post('/{id}/cancel', [PemesananController::class, 'cancel']);
    });
});

