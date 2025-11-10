<?php

use Illuminate\Support\Facades\Route;

// Temporary route for debugging - remove after use
require __DIR__.'/temp.php';
use App\Http\Controllers\UsersController;
use App\Http\Controllers\JadwalPetugasController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\RatingPesananController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JenisServiceController;

Route::apiResource('users', UsersController::class);
Route::apiResource('jadwal-petugas', JadwalPetugasController::class);
Route::apiResource('pemesanan', PemesananController::class);
Route::apiResource('rating-pesanan', RatingPesananController::class);
Route::apiResource('jenis-service', JenisServiceController::class);

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/check-email', [AuthController::class, 'checkEmail']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    // Petugas Profile
    Route::post('form-profile-petugas', [UsersController::class, 'storePetugasProfile']);
    
    // Booking Routes
    Route::prefix('bookings')->group(function () {
        Route::get('/', [PemesananController::class, 'index']);
        Route::post('/', [PemesananController::class, 'store']);
        Route::get('/{id}', [PemesananController::class, 'show']);
    });
});
