<?php

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\CountryController;
use App\Http\Controllers\V1\OccupationListController;
use App\Http\Controllers\V1\VisaSubclassController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
    Route::post('/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/social-login', [AuthController::class, 'socialLogin']);
});

Route::prefix('countries')->group(function () {
    Route::get('/', [CountryController::class, 'index']);
    Route::get('/{id}', [CountryController::class, 'show']);
});

Route::prefix('occupation-lists')->group(function () {
    Route::get('/', [OccupationListController::class, 'index']);
    Route::get('/{id}', [OccupationListController::class, 'show']);
});

Route::prefix('visa-subclasses')->group(function () {
    Route::get('/', [VisaSubclassController::class, 'index']);
    Route::get('/{id}', [VisaSubclassController::class, 'show']);
});
