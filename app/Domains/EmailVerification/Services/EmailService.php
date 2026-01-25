<?php

namespace App\Domains\EmailVerification\Services;

use Illuminate\Support\Facades\Log;

class EmailService
{
    public function sendVerificationEmail(string $email, string $verificationCode): void
    {
        try {
            Log::info('Sending verification email', [
                'email' => $email,
                'code' => $verificationCode,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send verification email', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
