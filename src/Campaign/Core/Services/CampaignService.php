<?php

namespace Fundraiser\Campaign\Core\Services;

use Fundraiser\Campaign\Adapters\Models\Campaign;
use Fundraiser\Campaign\Core\Dto\Forms\CreateCampaignForm;
use Fundraiser\Campaign\Core\Dto\Forms\UpdateCampaignForm;
use Fundraiser\Campaign\Core\Dto\Queries\CampaignQuery;
use Fundraiser\Campaign\Core\Services\Crud\CampaignCrudService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Read + orchestration service for Campaigns.
 * - Reads are performed directly via the Eloquent model (read-only in Core).
 * - Writes are delegated to CampaignCrudService (create/update only).
 */
readonly class CampaignService
{
    public function __construct(private CampaignCrudService $campaignCrudService) {}

    /**
     * Get a query builder with optional filters applied.
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
        return Campaign::query()->find($id);
    }

    public function list(array|CampaignQuery $filters = []): Collection
    {
        $filtersDto = $filters instanceof CampaignQuery ? $filters : CampaignQuery::from($filters);

        return $this->baseQuery($filtersDto)->get();
    }

    public function paginate(int $perPage = 15, int $page = 1, array|CampaignQuery $filters = []): LengthAwarePaginator
    {
        $filtersDto = $filters instanceof CampaignQuery ? $filters : CampaignQuery::from($filters);

        return $this->baseQuery($filtersDto)->paginate(perPage: $perPage, page: $page);
    }

    public function create(CreateCampaignForm $payload): Campaign
    {
        return $this->campaignCrudService->create($payload);
    }

    public function update(Campaign $campaign, UpdateCampaignForm $payload): Campaign
    {
        return $this->campaignCrudService->update($campaign, $payload);
    }
}
