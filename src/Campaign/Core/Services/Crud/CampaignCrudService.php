<?php

namespace Fundraiser\Campaign\Core\Services\Crud;

use Fundraiser\Campaign\Adapters\Models\Campaign;
use Fundraiser\Campaign\Core\Dto\Forms\CreateCampaignForm;
use Fundraiser\Campaign\Core\Dto\Forms\UpdateCampaignForm;

/**
 * Write-layer for Campaign. Contains ONLY create and update.
 * All other read operations should live in the simple/domain service.
 */
class CampaignCrudService
{
    /**
     * Create a new Campaign.
     */
    public function create(CreateCampaignForm $payload): Campaign
    {
        return Campaign::create($payload->toArray());
    }

    /**
     * Update an existing Campaign.
     *
     * @param  Campaign  $campaign  Campaign model
     */
    public function update(Campaign $campaign, UpdateCampaignForm $payload): Campaign
    {
        $campaign->fill($payload->toArray());
        $campaign->save();

        return $campaign->refresh();
    }
}
