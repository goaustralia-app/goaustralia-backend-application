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
        $this->configurePassportKeys();
    }

    /**
     * Configure Passport encryption keys.
     */
    private function configurePassportKeys(): void
    {
        $privateKeyPath = env('PASSPORT_PRIVATE_KEY');
        $publicKeyPath = env('PASSPORT_PUBLIC_KEY');

        if ($privateKeyPath && $publicKeyPath) {
            // Check if it's a base64 encoded key (for Vapor) or file path
            if (str_contains($privateKeyPath, 'storage/') || str_contains($privateKeyPath, '/')) {
                // File path - use default behavior
                Passport::loadKeysFrom(storage_path());
            } else {
                // Base64 encoded keys (for Vapor)
                Passport::keyPath($privateKeyPath);
            }
        }
    }
}
