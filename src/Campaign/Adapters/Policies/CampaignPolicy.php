<?php

namespace Fundraiser\Campaign\Adapters\Policies;

use Fundraiser\Campaign\Adapters\Models\Campaign;
use Fundraiser\Identity\Adapters\Models\User;

/**
 * Campaign policy maps Spatie permissions to Laravel policy abilities.
 * Example usage:
 *  - $user->hasRole('csr_admin');
 *  - $user->can('campaign.update_own');
 */
class CampaignPolicy
{
    /** View list/search of campaigns */
    public function viewAny(User $user): bool
    {
        return $user->can('campaign.view_any');
    }

    /** View a specific campaign */
    public function view(User $user, Campaign $campaign): bool
    {
        return $user->can('campaign.view');
    }

    /** Create a campaign */
    public function create(User $user): bool
    {
        return $user->can('campaign.create');
    }

    /** Update a campaign */
    public function update(User $user, Campaign $campaign): bool
    {
        return $user->can('campaign.update_any') ||
            (
                $user->can('campaign.update_own') &&
                $campaign->created_by_user_id === $user->id
            );
    }

    /** Delete a campaign */
    public function delete(User $user, Campaign $campaign): bool
    {
        return $user->can('campaign.delete_any') ||
            (
                $user->can('campaign.delete_own') &&
                $campaign->created_by_user_id === $user->id
            );
    }

    /** Moderate a campaign (approve/close, etc.) */
    public function moderate(User $user, Campaign $campaign): bool
    {
        return $user->can('campaign.moderate');
    }

    /** Mark a campaign as featured */
    public function feature(User $user, Campaign $campaign): bool
    {
        return $user->can('campaign.feature');
    }
}
