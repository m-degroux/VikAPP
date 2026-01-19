<?php

namespace App\Providers;

use App\Contracts\AuthServiceInterface;
use App\Contracts\ClubRepositoryInterface;
use App\Contracts\GeocodingServiceInterface;
use App\Contracts\RaceRepositoryInterface;
use App\Contracts\RaidRepositoryInterface;
use App\Contracts\TeamRepositoryInterface;
use App\Repositories\ClubRepository;
use App\Repositories\RaceRepository;
use App\Repositories\RaidRepository;
use App\Repositories\TeamRepository;
use App\Services\AuthService;
use App\Services\GeocodingService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(RaceRepositoryInterface::class, RaceRepository::class);
        $this->app->bind(RaidRepositoryInterface::class, RaidRepository::class);
        $this->app->bind(ClubRepositoryInterface::class, ClubRepository::class);
        $this->app->bind(TeamRepositoryInterface::class, TeamRepository::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(GeocodingServiceInterface::class, GeocodingService::class);
    }

    public function boot(): void
    {
        //
    }
}
