<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\JadwalPetugasController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\RatingPesananController;
use App\Http\Controllers\AuthController;

Route::apiResource('users', UsersController::class);
Route::apiResource('jadwal-petugas', JadwalPetugasController::class);
Route::apiResource('pemesanan', PemesananController::class);
Route::apiResource('rating-pesanan', RatingPesananController::class);

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);
