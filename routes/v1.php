<?php

use App\Http\Controllers\Api\EoiController;
use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\CountryController;
use App\Http\Controllers\V1\DocumentController;
use App\Http\Controllers\V1\EoiHistoryController;
use App\Http\Controllers\V1\EoiSubmissionController;
use App\Http\Controllers\V1\InvitationRoundController;
use App\Http\Controllers\V1\NotificationController;
use App\Http\Controllers\V1\OccupationListController;
use App\Http\Controllers\V1\OnboardingController;
use App\Http\Controllers\V1\ReminderController;
use App\Http\Controllers\V1\StatesSkilledOccupationListController;
use App\Http\Controllers\V1\VisaSubclassController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
    Route::post('/verify-email', [AuthController::class, 'verifyEmail'])->middleware('throttle:10,1');
    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->middleware('throttle:3,1');
    Route::post('/send-otp', [AuthController::class, 'sendOtp'])->middleware('throttle:3,1');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::post('/social-login', [AuthController::class, 'socialLogin'])->middleware('throttle:10,1');
});

Route::middleware('auth:api')->group(function () {
    Route::prefix('points-calculator')->group(function () {
        Route::post('/submit', [EoiController::class, 'submitCalculator']);
        Route::get('/points-breakdown', [EoiController::class, 'getPointsBreakdown']);
        Route::get('/suggestions', [EoiController::class, 'getSuggestions']);
    });

    Route::prefix('eois')->group(function () {
        Route::get('/', [EoiSubmissionController::class, 'index']);
        Route::post('/', [EoiSubmissionController::class, 'store']);
        Route::get('/{id}', [EoiSubmissionController::class, 'show']);
        Route::put('/{id}', [EoiSubmissionController::class, 'update']);
        Route::delete('/{id}', [EoiSubmissionController::class, 'destroy']);
        Route::get('/{id}/history', [EoiHistoryController::class, 'index']);
    });

    Route::prefix('documents')->group(function () {
        Route::get('/', [DocumentController::class, 'index']);
        Route::post('/', [DocumentController::class, 'store']);
        Route::get('/expiring', [DocumentController::class, 'expiring']);
        Route::put('/{id}', [DocumentController::class, 'update']);
        Route::delete('/{id}', [DocumentController::class, 'destroy']);
    });

    Route::prefix('reminders')->group(function () {
        Route::get('/', [ReminderController::class, 'index']);
        Route::patch('/{id}/read', [ReminderController::class, 'markRead']);
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::patch('/{id}/read', [NotificationController::class, 'markRead']);
        Route::patch('/read-all', [NotificationController::class, 'markAllRead']);
    });

    Route::prefix('onboarding')->group(function () {
        Route::post('/q&a', [OnboardingController::class, 'submitAnswers']);
        Route::get('/q&a', [OnboardingController::class, 'getAnswers']);
        Route::put('/q&a', [OnboardingController::class, 'updateAnswers']);
    });
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

Route::prefix('states-skilled-occupation-lists')->group(function () {
    Route::get('/', [StatesSkilledOccupationListController::class, 'index']);
    Route::get('/by-state/{state}', [StatesSkilledOccupationListController::class, 'byState']);
    Route::get('/{id}', [StatesSkilledOccupationListController::class, 'show']);
});

Route::prefix('invitation-rounds')->group(function () {
    Route::get('/', [InvitationRoundController::class, 'index']);
    Route::get('/{id}', [InvitationRoundController::class, 'show']);
});

Route::prefix('points-calculator')->group(function () {
    Route::get('/questions', [EoiController::class, 'getQuestions']);
});

Route::prefix('onboarding')->group(function () {
    Route::get('/questions', [OnboardingController::class, 'questions']);
});
