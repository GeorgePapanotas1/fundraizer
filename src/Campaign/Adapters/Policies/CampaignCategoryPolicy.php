<?php

namespace Fundraiser\Campaign\Adapters\Policies;

use Fundraiser\Campaign\Adapters\Models\CampaignCategory;
use Fundraiser\Identity\Adapters\Models\User;

/**
 * Policy for CampaignCategory using campaign-* permissions as the source of truth.
 */
class CampaignCategoryPolicy
{
    /**
     * View list/search of categories (reuses campaign.view_any permission)
     */
    public function viewAny(User $user): bool
    {
        return $user->can('campaign.view_any');
    }

    /**
     * View a single category (reuses campaign.view permission)
     */
    public function view(User $user, CampaignCategory $category): bool
    {
        return $user->can('campaign.view');
    }

    /**
     * Create a category requires campaign.moderate.
     */
    public function create(User $user): bool
    {
        return $user->can('campaign.moderate');
    }

    /**
     * Update a category requires campaign.moderate.
     */
    public function update(User $user, CampaignCategory $category): bool
    {
        return $user->can('campaign.moderate');
    }

    /**
     * Delete a category requires campaign.moderate.
     */
    public function delete(User $user, CampaignCategory $category): bool
    {
        return $user->can('campaign.moderate');
    }
}
