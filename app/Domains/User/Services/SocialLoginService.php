<?php

namespace App\Domains\User\Services;

use App\Domains\User\Contracts\UserRepositoryContract;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Log;

class SocialLoginService
{
    public function __construct(
        private UserRepositoryContract $userRepository
    ) {}

    public function login(array $data): array
    {
        // Validate required fields
        if (empty($data['email']) || empty($data['name'])) {
            throw new \Exception('Email and name are required for social login.');
        }

        $email = $data['email'];
        $providerName = $data['provider_name'];
        $providerId = $data['provider_id'];

        // Check if user already exists by email
        $user = $this->userRepository->findByEmail($email);

        if ($user) {
            // User exists - update provider data and login
            $user = $this->updateExistingUserWithProvider($user, $data);
            $message = 'Social login successful. Welcome back!';
            $isNewUser = false;
        } else {
            // Check if user exists with same provider credentials
            $existingProviderUser = $this->findUserByProvider($providerName, $providerId);

            if ($existingProviderUser) {
                throw new \Exception('This social account is already linked to another user.');
            }

            // User doesn't exist - create new user
            $user = $this->createNewSocialUser($data);
            $message = 'Account created successfully via social login. Welcome!';
            $isNewUser = true;
        }

        // Generate access token
        $accessToken = $this->generateAccessToken($user);

        $this->logSocialLogin($user, $providerName, $isNewUser);

        return [
            'message' => $message,
            'user' => new UserResource($user->load('country')),
            'access_token' => $accessToken->accessToken,
            'refresh_token' => $accessToken->token->refresh_token,
            'expires_at' => $accessToken->token->expires_at,
            'is_new_user' => $isNewUser,
        ];
    }

    /**
     * Find user by provider credentials.
     */
    private function findUserByProvider(string $providerName, string $providerId)
    {
        return $this->userRepository->findByProvider($providerName, $providerId);
    }

    /**
     * Update existing user with provider data.
     */
    private function updateExistingUserWithProvider($user, array $data)
    {
        // Ensure email is verified for social logins
        if (! $user->email_verified_at) {
            $user->email_verified_at = now();
        }

        // Update provider data if not set or if it's from a different provider
        if (! $user->provider_name || $user->provider_name !== $data['provider_name']) {
            $user->provider_name = $data['provider_name'];
            $user->provider_id = $data['provider_id'];
        }

        $user->save();

        return $user;
    }

    /**
     * Create new user from social login data.
     */
    private function createNewSocialUser(array $data)
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'email_verified_at' => now(), // Social logins are pre-verified
            'password' => null, // No password for social users
            'country_id' => $data['country_id'] ?? null,
            'provider_name' => $data['provider_name'],
            'provider_id' => $data['provider_id'],
        ];

        return $this->userRepository->create($userData);
    }

    /**
     * Generate access token for user.
     */
    private function generateAccessToken($user)
    {
        $tokenName = 'GoAustralia Social Access Token';
        $scopes = ['*'];

        return $user->createToken($tokenName, $scopes);
    }

    /**
     * Log social login activity.
     */
    private function logSocialLogin($user, string $providerName, bool $isNewUser): void
    {
        Log::info('Social login', [
            'user_id' => $user->id,
            'email' => $user->email,
            'provider' => $providerName,
            'is_new_user' => $isNewUser,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now(),
        ]);
    }
}
