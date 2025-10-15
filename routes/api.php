<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\JadwalPetugasController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\RatingPesananController;

Route::apiResource('users', UsersController::class);
Route::apiResource('jadwal-petugas', JadwalPetugasController::class);
Route::apiResource('pemesanan', PemesananController::class);
Route::apiResource('rating-pesanan', RatingPesananController::class);
