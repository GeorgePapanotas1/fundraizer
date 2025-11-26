<?php

namespace Fundraiser\Campaign\Core\Services;

use Fundraiser\Campaign\Adapters\Models\Campaign;
use Fundraiser\Campaign\Core\Constants\Enums\CampaignStatus;
use Fundraiser\Campaign\Core\Dto\Forms\CreateCampaignForm;
use Fundraiser\Campaign\Core\Dto\Forms\UpdateCampaignForm;
use Fundraiser\Campaign\Core\Dto\Queries\CampaignQuery;
use Fundraiser\Campaign\Core\Services\Crud\CampaignCrudService;
use Fundraiser\Identity\Adapters\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;

/**
 * Read + orchestration service for Campaigns.
 * - Reads are performed directly via the Eloquent model (read-only in Core).
 * - Writes are delegated to CampaignCrudService (create/update only).
 */
readonly class CampaignService
{
    public function __construct(
        private CampaignCrudService $campaignCrudService,
    ) {}

    /**
     * @return Builder<Campaign>
     */
    protected function baseQuery(CampaignQuery $filters): Builder
    {
        return Campaign::query()
            ->when($filters->status, fn (Builder $q, string $v) => $q->where('status', $v))
            ->when($filters->campaign_category_id, fn (Builder $q, string $v) => $q->where('campaign_category_id', $v))
            ->when($filters->created_by_user_id, fn (Builder $q, string $v) => $q->where('created_by_user_id', $v))
            ->when($filters->search, function (Builder $q, string $search) {
                $q->where(function (Builder $nested) use ($search) {
                    $nested->where('title', 'like', "%{$search}%")
                        ->orWhere('short_description', 'like', "%{$search}%");
                });
            });
    }

    public function findById(string $id): ?Campaign
    {
        $model = Campaign::query()->find($id);

        // Authorize viewing only when a model exists
        if ($model) {
            Gate::authorize('view', $model);
        }

        return $model;
    }

    /**
     * @return Collection<int, Campaign>
     */
    public function list(CampaignQuery $filters): Collection
    {
        Gate::authorize('viewAny', Campaign::class);

        return $this->baseQuery($filters)->get();
    }

    /**
     * @return LengthAwarePaginator<int, Campaign>
     */
    public function paginate(int $perPage, int $page, CampaignQuery $filters): LengthAwarePaginator
    {
        Gate::authorize('viewAny', Campaign::class);

        return $this->baseQuery($filters)->paginate(perPage: $perPage, page: $page);
    }

    public function create(CreateCampaignForm $payload): Campaign
    {
        Gate::authorize('create', Campaign::class);

        return $this->campaignCrudService->create($payload);
    }

    public function update(Campaign $campaign, UpdateCampaignForm $payload): Campaign
    {
        Gate::authorize('update', $campaign);

        return $this->campaignCrudService->update($campaign, $payload);
    }

    public function delete(Campaign $campaign): void
    {
        Gate::authorize('delete', $campaign);

        $this->campaignCrudService->delete($campaign);
    }

    /**
     * Move a campaign into moderation queue. Owner (update_own) or admins (update_any) can do this.
     */
    public function submitForApproval(Campaign $campaign): Campaign
    {
        Gate::authorize('update', $campaign);

        return $this->campaignCrudService->update(
            $campaign,
            new UpdateCampaignForm(status: CampaignStatus::PendingApproval->value)
        );
    }

    /**
     * Approve a campaign. Requires 'campaign.moderate'. Sets status to active and records approver.
     */
    public function approve(Campaign $campaign, User $approver): Campaign
    {
        Gate::authorize('moderate', $campaign);

        return $this->campaignCrudService->update(
            $campaign,
            new UpdateCampaignForm(
                status: CampaignStatus::Active->value,
                approved_by_user_id: (string) $approver->id
            )
        );
    }
}
