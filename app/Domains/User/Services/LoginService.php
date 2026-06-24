<?php

namespace App\Domains\User\Services;

use App\Domains\User\Contracts\UserRepositoryContract;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginService
{
    public function __construct(
        private UserRepositoryContract $userRepository
    ) {}

    public function login(array $credentials): array
    {
        $email = $credentials['email'];
        $password = $credentials['password'];

        $user = $this->userRepository->findByEmail($email);

        if (! $user) {
            $this->logFailedLogin($email, 'User not found');
            throw new \Exception('Invalid credentials provided.');
        }

        // if (! $user->email_verified_at) {
        //     $this->logFailedLogin($email, 'Email not verified');
        //     throw new \Exception('Please verify your email address before logging in.');
        // }

        if (! Hash::check($password, $user->password)) {
            $this->logFailedLogin($email, 'Invalid password');
            throw new \Exception('Invalid credentials provided.');
        }

        $token = $user->createToken('GoAustralia Access Token');

        $this->logSuccessfulLogin($user->id, $email);

        return [
            'message' => 'Login successful.',
            'user' => $user->load('country'),
            'access_token' => $token->plainTextToken,
        ];
    }

    private function logFailedLogin(string $email, string $reason): void
    {
        Log::warning('Failed login attempt', [
            'email' => $email,
            'reason' => $reason,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now(),
        ]);
    }

    private function logSuccessfulLogin(int $userId, string $email): void
    {
        Log::info('Successful login', [
            'user_id' => $userId,
            'email' => $email,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now(),
        ]);
    }
}
