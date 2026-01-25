<?php

namespace App\Providers;

use App\Domains\Country\Contracts\CountryRepositoryContract;
use App\Domains\Country\Repositories\CountryRepository;
use App\Domains\EmailVerification\Contracts\EmailVerificationRepositoryContract;
use App\Domains\EmailVerification\Repositories\EmailVerificationRepository;
use App\Domains\User\Contracts\UserRepositoryContract;
use App\Domains\User\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryContract::class, UserRepository::class);
        $this->app->bind(EmailVerificationRepositoryContract::class, EmailVerificationRepository::class);
        $this->app->bind(CountryRepositoryContract::class, CountryRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
