<?php

namespace App\Providers;

use Fundraiser\Campaign\Adapters\Models\Campaign;
use Fundraiser\Campaign\Adapters\Observers\CampaignObserver;
use Fundraiser\Donations\Adapters\Payments\PaymentGatewayFactory;
use Fundraiser\Donations\Core\Payments\PaymentGatewayInterface;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        /** @var string $activeGateway */
        $activeGateway = config('payments.active_gateway');
        $this->app->bind(PaymentGatewayInterface::class, function () use ($activeGateway) {
            return PaymentGatewayFactory::make($activeGateway);
        });
    }

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
