<?php

use Database\Factories\Fundraiser\Identity\Adapters\Models\UserFactory;
use Database\Seeders\IdentityCampaignPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

/*
|--------------------------------------------------------------------------
| Pest configuration
|--------------------------------------------------------------------------
| Apply base TestCase and RefreshDatabase to all Feature tests.
*/

uses(Tests\TestCase::class, RefreshDatabase::class)->beforeEach(function () {
    // Seed roles & permissions for Campaign context
    $this->seed(IdentityCampaignPermissionSeeder::class);

    // Authenticate a superuser to pass policies by default in Feature tests
    $user = UserFactory::new()->create();
    $user->assignRole('system_admin');
    $this->be($user, 'web');
})->in('Feature');
