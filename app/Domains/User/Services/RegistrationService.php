<?php

namespace App\Domains\User\Services;

use App\Domains\EmailVerification\Contracts\EmailVerificationRepositoryContract;
use App\Domains\User\Contracts\UserRepositoryContract;
use App\Enums\EmailVerificationEnum;
use App\Mail\OtpVerificationMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegistrationService
{
    public function __construct(
        private UserRepositoryContract $userRepository,
        private EmailVerificationRepositoryContract $emailVerificationRepository
    ) {}

    public function register(array $data): array
    {
        $existingUser = $this->userRepository->findByEmail($data['email']);

        if ($existingUser && $existingUser->email_verified_at) {
            throw new \Exception('Email already registered and verified.');
        }

        if ($existingUser && ! $existingUser->email_verified_at) {
            $this->emailVerificationRepository->deleteByEmail($data['email'], EmailVerificationEnum::REGISTRATION);
            $user = $existingUser;
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

        Mail::to($data['email'])->queue(new OtpVerificationMail($verificationCode, $data['email']));

        $accessToken = $user->createToken('GoAustralia Access Token');

        return [
            'message' => 'Registration successful. Please check your email for verification code.',
            'user' => $user,
            'email' => $data['email'],
            'access_token' => $accessToken->accessToken,
            'refresh_token' => $accessToken->token->refresh_token,
            'expires_at' => $accessToken->token->expires_at,
        ];
    }

    private function generateOtp(): string
    {
        return str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
    }
}
