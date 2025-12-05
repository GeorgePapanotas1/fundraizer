<?php

use Fundraiser\Campaign\Adapters\Http\Controllers\CampaignCategoryController;
use Fundraiser\Campaign\Adapters\Http\Controllers\CampaignController;
use Fundraiser\Donations\Adapters\Http\Controllers\DonationsController;
use Fundraiser\Identity\Adapters\Http\Controllers\MeController;
use Fundraiser\Identity\Adapters\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

// routes in this file are already prefixed with 'api' by Laravel
// we add versioning prefix 'v1' to achieve final path '/api/v1/...'
Route::prefix('v1')
    ->as('api.v1.')
    ->middleware('auth:api')
    ->group(function () {
        // Current authenticated user (profile + roles + permissions)
        Route::get('me', [MeController::class, 'show'])->name('me.show');

        // Campaigns
        // Campaign status dropdowns (place before {campaign} bindings to avoid collisions)
        Route::get('campaigns/statuses', [CampaignController::class, 'statuses'])->name('campaigns.statuses.index');

        // Active campaigns (cached) for employees
        Route::get('campaigns/active', [CampaignController::class, 'active'])->name('campaigns.active.index');

        Route::get('campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
        Route::get('campaigns/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');
        Route::post('campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
        Route::match(['put', 'patch'], 'campaigns/{campaign}', [CampaignController::class, 'update'])->name('campaigns.update');

        Route::get('campaigns/{campaign}/statuses', [CampaignController::class, 'statusesForCampaign'])->name('campaigns.statuses.show');

        // Moderation actions
        Route::post('campaigns/{campaign}/approve', [CampaignController::class, 'approve'])->name('campaigns.approve');
        Route::post('campaigns/{campaign}/reject', [CampaignController::class, 'reject'])->name('campaigns.reject');

        // Donations
        Route::post('campaigns/{campaign}/donations', [DonationsController::class, 'store'])->name('campaigns.donations.store');

        // Campaign Categories
        Route::get('campaign-categories', [CampaignCategoryController::class, 'index'])->name('campaign-categories.index');
        Route::get('campaign-categories/{campaignCategory}', [CampaignCategoryController::class, 'show'])->name('campaign-categories.show');
        Route::post('campaign-categories', [CampaignCategoryController::class, 'store'])->name('campaign-categories.store');
        Route::match(['put', 'patch'], 'campaign-categories/{campaignCategory}', [CampaignCategoryController::class, 'update'])->name('campaign-categories.update');

        // Identity - Users (admin-only; read-only listing for admin UI)
        Route::get('users', [UsersController::class, 'index'])->name('users.index');
    });
