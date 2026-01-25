<?php

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\CountryController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
    Route::post('/send-otp', [AuthController::class, 'sendOtp']);
});

Route::prefix('countries')->group(function () {
    Route::get('/', [CountryController::class, 'index']);
    Route::get('/{id}', [CountryController::class, 'show']);
});
