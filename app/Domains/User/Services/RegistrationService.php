<?php

namespace App\Domains\User\Services;

use App\Domains\EmailVerification\Contracts\EmailVerificationRepositoryContract;
use App\Domains\User\Contracts\UserRepositoryContract;
use App\Enums\EmailVerificationEnum;
use App\Mail\WelcomeMail;
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

        Mail::to($data['email'])->queue(new WelcomeMail($user));

        $token = $user->createToken('GoAustralia Access Token');

        return [
            'message' => 'Registration successful.',
            'user' => $user,
            'email' => $user->email,
            'access_token' => $token->plainTextToken,
        ];
    }
}
