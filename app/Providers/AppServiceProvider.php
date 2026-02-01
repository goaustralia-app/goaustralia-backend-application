<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configurePassport();
    }

    /**
     * Configure Laravel Passport.
     */
    private function configurePassport(): void
    {
        // Configure token lifetimes
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

        // For production (Vapor), handle base64 encoded keys
        if (app()->environment('production')) {
            $this->configureVaporKeys();
        }
        // For local development, Passport uses default configuration from config/passport.php
    }

    /**
     * Configure Passport keys for Vapor deployment.
     */
    private function configureVaporKeys(): void
    {
        $privateKey = env('PASSPORT_PRIVATE_KEY');
        $publicKey = env('PASSPORT_PUBLIC_KEY');

        if ($privateKey && $publicKey && str_starts_with($privateKey, 'LS0tLS1CRUdJTi')) {
            // Decode base64 keys and write to temp files
            file_put_contents('/tmp/oauth-private.key', base64_decode($privateKey));
            file_put_contents('/tmp/oauth-public.key', base64_decode($publicKey));

            Passport::loadKeysFrom('/tmp/');
        }
    }
}
