<?php

namespace App\Domains\User\Services;

use App\Domains\User\Contracts\UserRepositoryContract;
use Illuminate\Support\Facades\Http;

class SocialLoginService
{
    public function __construct(
        private UserRepositoryContract $userRepository
    ) {}

    public function login(array $data): array
    {
        $socialUserData = $this->validateSocialToken($data['provider'], $data['access_token']);

        if (! $socialUserData) {
            throw new \Exception('Invalid social login token.');
        }

        $user = $this->userRepository->findByEmail($socialUserData['email']);

        if (! $user) {
            $userData = [
                'name' => $socialUserData['name'],
                'email' => $socialUserData['email'],
                'email_verified_at' => now(),
                'password' => null,
                'country_id' => $data['country_id'] ?? null,
            ];

            $user = $this->userRepository->create($userData);
        } else {
            $user->email_verified_at = $user->email_verified_at ?? now();
            $user->save();
        }

        $tokenName = 'GoAustralia Social Access Token';
        $accessToken = $user->createToken($tokenName);

        return [
            'message' => 'Social login successful.',
            'user' => $user->load('country'),
            'access_token' => $accessToken->accessToken,
            'refresh_token' => $accessToken->token->refresh_token,
            'expires_at' => $accessToken->token->expires_at,
        ];
    }

    private function validateSocialToken(string $provider, string $accessToken): ?array
    {
        return match ($provider) {
            'google' => $this->validateGoogleToken($accessToken),
            'facebook' => $this->validateFacebookToken($accessToken),
            'apple' => $this->validateAppleToken($accessToken),
            default => null,
        };
    }

    private function validateGoogleToken(string $accessToken): ?array
    {
        try {
            $response = Http::get('https://www.googleapis.com/oauth2/v2/userinfo', [
                'access_token' => $accessToken,
            ]);

            if ($response->successful()) {
                $userData = $response->json();

                return [
                    'name' => $userData['name'] ?? $userData['given_name'] ?? 'User',
                    'email' => $userData['email'],
                ];
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }

    private function validateFacebookToken(string $accessToken): ?array
    {
        try {
            $response = Http::get('https://graph.facebook.com/me', [
                'access_token' => $accessToken,
                'fields' => 'id,name,email',
            ]);

            if ($response->successful()) {
                $userData = $response->json();

                return [
                    'name' => $userData['name'] ?? 'User',
                    'email' => $userData['email'],
                ];
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }

    private function validateAppleToken(string $accessToken): ?array
    {
        return null;
    }
}
