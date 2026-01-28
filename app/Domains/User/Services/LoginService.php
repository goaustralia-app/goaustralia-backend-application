<?php

namespace App\Domains\User\Services;

use App\Domains\User\Contracts\UserRepositoryContract;
use Illuminate\Support\Facades\Hash;

class LoginService
{
    public function __construct(
        private UserRepositoryContract $userRepository
    ) {}

    public function login(array $credentials): array
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

        if (! $user) {
            throw new \Exception('Invalid credentials provided.');
        }

        if (! $user->email_verified_at) {
            throw new \Exception('Please verify your email address before logging in.');
        }

        if (! Hash::check($credentials['password'], $user->password)) {
            throw new \Exception('Invalid credentials provided.');
        }

        $tokenName = 'GoAustralia Access Token';
        $accessToken = $user->createToken($tokenName);

        return [
            'message' => 'Login successful.',
            'user' => $user->load('country'),
            'access_token' => $accessToken->accessToken,
            'refresh_token' => $accessToken->token->refresh_token,
            'expires_at' => $accessToken->token->expires_at,
        ];
    }
}
