<?php

use Fundraiser\Campaign\Adapters\Http\Controllers\CampaignCategoryController;
use Fundraiser\Campaign\Adapters\Http\Controllers\CampaignController;
use Illuminate\Support\Facades\Route;

// routes in this file are already prefixed with 'api' by Laravel
// we add versioning prefix 'v1' to achieve final path '/api/v1/...'
Route::prefix('v1')
    ->as('api.v1.')
    ->middleware('auth:api')
    ->group(function () {
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

        // Campaign Categories
        Route::get('campaign-categories', [CampaignCategoryController::class, 'index'])->name('campaign-categories.index');
        Route::get('campaign-categories/{campaignCategory}', [CampaignCategoryController::class, 'show'])->name('campaign-categories.show');
        Route::post('campaign-categories', [CampaignCategoryController::class, 'store'])->name('campaign-categories.store');
        Route::match(['put', 'patch'], 'campaign-categories/{campaignCategory}', [CampaignCategoryController::class, 'update'])->name('campaign-categories.update');
    });
