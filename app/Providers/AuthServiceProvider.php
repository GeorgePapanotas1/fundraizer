<?php

namespace App\Providers;

use Fundraiser\Campaign\Adapters\Models\Campaign;
use Fundraiser\Campaign\Adapters\Models\CampaignCategory;
use Fundraiser\Campaign\Adapters\Policies\CampaignCategoryPolicy;
use Fundraiser\Campaign\Adapters\Policies\CampaignPolicy;
use Fundraiser\Identity\Adapters\Models\User as IdentityUser;
use Fundraiser\Identity\Adapters\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Campaign::class => CampaignPolicy::class,
        CampaignCategory::class => CampaignCategoryPolicy::class,
        IdentityUser::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
