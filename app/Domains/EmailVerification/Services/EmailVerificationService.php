<?php

namespace App\Domains\EmailVerification\Services;

use App\Domains\EmailVerification\Contracts\EmailVerificationRepositoryContract;
use App\Domains\User\Contracts\UserRepositoryContract;
use App\Enums\EmailVerificationEnum;
use Carbon\Carbon;

class EmailVerificationService
{
    public function __construct(
        private UserRepositoryContract $userRepository,
        private EmailVerificationRepositoryContract $emailVerificationRepository,
        private EmailService $emailService
    ) {}

    public function verifyEmail(string $email, string $code): array
    {
        $verification = $this->emailVerificationRepository->findValidByEmail($email, EmailVerificationEnum::REGISTRATION);

        if (! $verification) {
            throw new \Exception('Invalid or expired verification code.');
        }

        if ($verification->is_verified) {
            return [
                'message' => 'Email already verified.',
                'email' => $email,
            ];
        }

        if ($verification->verification_code !== $code) {
            throw new \Exception('Invalid verification code.');
        }

        $user = $this->userRepository->findByEmail($email);
        if ($user) {
            $this->userRepository->markEmailAsVerified($user);
        }

        $verification->update(['is_verified' => true]);

        return [
            'message' => 'Email verified successfully.',
            'email' => $email,
        ];
    }

    public function resendOtp(string $email): array
    {
        $existingVerification = $this->emailVerificationRepository->findValidByEmail($email, EmailVerificationEnum::REGISTRATION);

        if ($existingVerification && ! $existingVerification->isExpired()) {
            throw new \Exception('Verification code is still valid. Please wait before requesting a new one.');
        }

        $this->emailVerificationRepository->deleteByEmail($email, EmailVerificationEnum::REGISTRATION);

        $verificationCode = $this->generateOtp();

        $this->emailVerificationRepository->create([
            'email' => $email,
            'verification_code' => $verificationCode,
            'type' => EmailVerificationEnum::REGISTRATION,
            'expires_at' => Carbon::now()->addMinutes(5),
            'is_verified' => false,
        ]);

        $this->emailService->sendVerificationEmail($email, $verificationCode);

        return [
            'message' => 'Verification code resent successfully.',
            'email' => $email,
        ];
    }

    public function sendOtp(string $email): array
    {
        $this->emailVerificationRepository->deleteByEmail($email, EmailVerificationEnum::REGISTRATION);

        $verificationCode = $this->generateOtp();

        $this->emailVerificationRepository->create([
            'email' => $email,
            'verification_code' => $verificationCode,
            'type' => EmailVerificationEnum::REGISTRATION,
            'expires_at' => Carbon::now()->addMinutes(5),
            'is_verified' => false,
        ]);

        $this->emailService->sendVerificationEmail($email, $verificationCode);

        return [
            'message' => 'Verification code sent successfully.',
            'email' => $email,
        ];
    }

    private function generateOtp(): string
    {
        return str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    }
}
