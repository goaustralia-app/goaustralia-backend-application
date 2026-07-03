<?php

namespace App\Providers;

use App\Domains\Country\Contracts\CountryRepositoryContract;
use App\Domains\Country\Repositories\CountryRepository;
use App\Domains\EmailVerification\Contracts\EmailVerificationRepositoryContract;
use App\Domains\EmailVerification\Repositories\EmailVerificationRepository;
use App\Domains\InvitationRound\Repositories\InvitationRoundRepository;
use App\Domains\InvitationRound\Repositories\InvitationRoundRepositoryInterface;
use App\Domains\OccupationList\Repositories\OccupationListRepository;
use App\Domains\OccupationList\Repositories\OccupationListRepositoryInterface;
use App\Domains\User\Contracts\UserRepositoryContract;
use App\Domains\User\Repositories\UserRepository;
use App\Domains\VisaSubclass\Repositories\VisaSubclassRepository;
use App\Domains\VisaSubclass\Repositories\VisaSubclassRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryContract::class, UserRepository::class);
        $this->app->bind(EmailVerificationRepositoryContract::class, EmailVerificationRepository::class);
        $this->app->bind(CountryRepositoryContract::class, CountryRepository::class);
        $this->app->bind(OccupationListRepositoryInterface::class, OccupationListRepository::class);
        $this->app->bind(VisaSubclassRepositoryInterface::class, VisaSubclassRepository::class);
        $this->app->bind(InvitationRoundRepositoryInterface::class, InvitationRoundRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
