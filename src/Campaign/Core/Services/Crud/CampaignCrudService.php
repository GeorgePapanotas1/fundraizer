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
        /** @var array<string, mixed> $campaign */
        $campaign = $payload->toArray();

        return Campaign::create($campaign);
    }

    /**
     * Update an existing Campaign.
     *
     * @param  Campaign  $campaign  Campaign model
     */
    public function update(Campaign $campaign, UpdateCampaignForm $payload): Campaign
    {
        // Partial updates: only apply fields that are present (non-null)
        /** @var array<string, mixed> $data */
        $data = array_filter($payload->toArray(), fn ($v) => ! is_null($v));
        $campaign->fill($data);
        $campaign->save();

        return $campaign->refresh();
    }

    /**
     * Delete a Campaign (soft delete via model trait).
     */
    public function delete(Campaign $campaign): void
    {
        $campaign->delete();
    }
}
