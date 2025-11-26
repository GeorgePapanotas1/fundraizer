<?php

namespace App\Providers;

use Fundraiser\Campaign\Adapters\Models\Campaign;
use Fundraiser\Campaign\Adapters\Observers\CampaignObserver;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::enablePasswordGrant();
        // Register model observers
        Campaign::observe(CampaignObserver::class);
    }
}
