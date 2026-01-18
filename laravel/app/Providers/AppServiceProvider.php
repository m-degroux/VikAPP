<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\ManageRaid;
use App\Models\Member;
use App\Models\Race;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        Gate::define('admin', function (Member $member) {
            return Admin::where('user_id', $member->user_id)->exists();
        });

        Gate::define('raid', function (Member $member, $raidId = null) {
            $isAdmin = Admin::where('user_id', $member->user_id)->exists();
            if ($isAdmin) return true;
            if ($raidId) {
                return ManageRaid::where('raid_id', $raidId)
                    ->where('user_id', $member->user_id)
                    ->exists();
            }
            return false;
        });

        Gate::define('race', function (Member $member, Race $race) {
            if (Admin::where('user_id', $member->user_id)->exists()) {
                return true;
            }

            if (ManageRaid::where('raid_id', $race->raid_id)
                ->where('user_id', $member->user_id)
                ->exists()) {
                return true;
            }

            return RaceManager::where('race_id', $race->race_id)
                ->where('user_id', $member->user_id)
                ->exists();
        });
    }
}
