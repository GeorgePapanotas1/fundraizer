<?php

namespace Fundraiser\Campaign\Adapters\Observers;

use Fundraiser\Campaign\Adapters\Models\Campaign;
use Fundraiser\Campaign\Core\Constants\Enums\CampaignStatus;
use Illuminate\Support\Facades\Cache;

class CampaignObserver
{
    /**
     * Increment the active campaigns cache version when status transitions
     * involve the 'active' state, or when an active campaign is deleted/restored.
     */
    public function updated(Campaign $campaign): void
    {
        if ($campaign->wasChanged('status')) {
            $original = $campaign->getOriginal('status');
            $current = $campaign->status;

            if ($original === CampaignStatus::Active->value || $current === CampaignStatus::Active->value) {
                $this->bumpActiveCacheVersion();
            }
        }
    }

    public function deleted(Campaign $campaign): void
    {
        if ($campaign->status === CampaignStatus::Active->value) {
            $this->bumpActiveCacheVersion();
        }
    }

    public function restored(Campaign $campaign): void
    {
        if ($campaign->status === CampaignStatus::Active->value) {
            $this->bumpActiveCacheVersion();
        }
    }

    private function bumpActiveCacheVersion(): void
    {
        // Ensure a baselixne version exists (1) and then bump
        /** @var int $current */
        $current = Cache::get('campaigns:active:version', 1);
        Cache::forever('campaigns:active:version', $current + 1);
    }
}
