<?php

namespace App\Domains\User\Services;

use App\Domains\EmailVerification\Contracts\EmailVerificationRepositoryContract;
use App\Domains\EmailVerification\Services\EmailService;
use App\Domains\User\Contracts\UserRepositoryContract;
use App\Enums\EmailVerificationEnum;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class RegistrationService
{
    public function __construct(
        private UserRepositoryContract $userRepository,
        private EmailVerificationRepositoryContract $emailVerificationRepository,
        private EmailService $emailService
    ) {}

    public function register(array $data): array
    {
        $existingUser = $this->userRepository->findByEmail($data['email']);

        if ($existingUser && $existingUser->email_verified_at) {
            throw new \Exception('Email already registered and verified.');
        }

        if ($existingUser && ! $existingUser->email_verified_at) {
            $this->emailVerificationRepository->deleteByEmail($data['email'], EmailVerificationEnum::REGISTRATION);
        } else {
            $data['password'] = Hash::make($data['password']);
            $user = $this->userRepository->create($data);
        }

        $verificationCode = $this->generateOtp();

        $this->emailVerificationRepository->create([
            'email' => $data['email'],
            'verification_code' => $verificationCode,
            'type' => EmailVerificationEnum::REGISTRATION,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        $this->emailService->sendVerificationEmail($data['email'], $verificationCode);

        return [
            'message' => 'Registration successful. Please check your email for verification code.',
            'email' => $data['email'],
        ];
    }

    private function generateOtp(): string
    {
        return str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    }
}
