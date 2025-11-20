<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsersController;
use App\Http\Controllers\JadwalPetugasController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\RatingPesananController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JenisServiceController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GeocodeController;
use App\Http\Controllers\UserAddressController;
use App\Http\Controllers\PricingController;

// Public read-only endpoints
Route::apiResource('users', UsersController::class)->only(['index', 'show']);
Route::apiResource('jadwal-petugas', JadwalPetugasController::class)->only(['index', 'show']);
Route::apiResource('pemesanan', PemesananController::class)->only(['index', 'show']);
Route::apiResource('rating-pesanan', RatingPesananController::class)->only(['index', 'show']);
Route::apiResource('jenis-service', JenisServiceController::class)->only(['index', 'show']);
Route::apiResource('promos', PromoController::class)->only(['index', 'show']);

// Public geocode proxy (bypass CORS to Nominatim)
Route::get('geocode/reverse', [GeocodeController::class, 'reverse']);
Route::get('geocode/search', [GeocodeController::class, 'search']);

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
    Route::apiResource('promos', PromoController::class)->only(['store', 'update', 'destroy']);

    // Authenticated user info
    Route::get('me', [UsersController::class, 'me']);
    Route::get('me/stats', [UsersController::class, 'stats']);

    // Dashboard summary
    Route::get('dashboard/summary', [DashboardController::class, 'summary']);

    // Petugas Profile
    Route::get('check-petugas-profile', [UsersController::class, 'checkPetugasProfile']);
    Route::post('form-profile-petugas', [UsersController::class, 'storePetugasProfile']);

    // Admin: Petugas Profile moderation
    Route::prefix('admin')->group(function () {
        Route::get('petugas-profiles', [UsersController::class, 'listPetugasProfiles']);
        Route::post('petugas-profiles/{id}/approve', [UsersController::class, 'approvePetugasProfile']);
        Route::post('petugas-profiles/{id}/reject', [UsersController::class, 'rejectPetugasProfile']);
    });
    
    // Booking Routes
    Route::prefix('bookings')->group(function () {
        Route::get('/', [PemesananController::class, 'index']);
        Route::post('/', [PemesananController::class, 'store']);
        Route::get('/{id}', [PemesananController::class, 'show']);
        Route::post('/{id}/cancel', [PemesananController::class, 'cancel']);
    });

    // Staff: available bookings matching staff profile (gender any or match)
    Route::get('petugas/available-bookings', [PemesananController::class, 'availableForStaff']);
    Route::post('petugas/bookings/{id}/accept', [PemesananController::class, 'acceptByStaff']);
    Route::post('petugas/bookings/{id}/reject', [PemesananController::class, 'rejectByStaff']);
    Route::post('petugas/bookings/{id}/start', [PemesananController::class, 'startByStaff']);
    Route::post('petugas/bookings/{id}/complete', [PemesananController::class, 'completeByStaff']);
    Route::get('petugas/my-bookings', [PemesananController::class, 'myBookings']);

    // Ratings (customer)
    Route::post('ratings', [RatingPesananController::class, 'store']);
    Route::put('ratings/{id}', [RatingPesananController::class, 'update']);
    Route::get('ratings/status/{orderId}', [RatingPesananController::class, 'ratingStatus']);

    // Favorite Addresses (current user)
    Route::prefix('me')->group(function () {
        Route::get('addresses', [UserAddressController::class, 'index']);
        Route::post('addresses', [UserAddressController::class, 'store']);
        Route::put('addresses/{id}', [UserAddressController::class, 'update']);
        Route::delete('addresses/{id}', [UserAddressController::class, 'destroy']);
    });

    // Pricing & Promo
    Route::post('estimate-price', [PricingController::class, 'estimate']);
    Route::post('promos/validate', [PromoController::class, 'validateCode']);
});


