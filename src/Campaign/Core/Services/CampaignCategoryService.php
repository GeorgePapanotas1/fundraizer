<?php

namespace Fundraiser\Campaign\Core\Services;

use Fundraiser\Campaign\Adapters\Models\CampaignCategory;
use Fundraiser\Campaign\Core\Dto\Forms\CreateCampaignCategoryForm;
use Fundraiser\Campaign\Core\Dto\Forms\UpdateCampaignCategoryForm;
use Fundraiser\Campaign\Core\Dto\Queries\CampaignCategoryQuery;
use Fundraiser\Campaign\Core\Services\Crud\CampaignCategoryCrudService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;

/**
 * Read + orchestration service for Campaign Categories.
 * - Reads are performed directly via the Eloquent model (read-only in Core).
 * - Writes are delegated to CampaignCategoryCrudService (create/update only).
 */
readonly class CampaignCategoryService
{
    public function __construct(private CampaignCategoryCrudService $categoryCrudService) {}

    /**
     * @return Builder<CampaignCategory>
     */
    protected function baseQuery(CampaignCategoryQuery $filters): Builder
    {
        return CampaignCategory::query()
            ->when($filters->search, function (Builder $q, string $search) {
                $q->where(function (Builder $nested) use ($search) {
                    $nested->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            });
    }

    public function findById(string $id): ?CampaignCategory
    {
        $model = CampaignCategory::query()->find($id);

        if ($model) {
            Gate::authorize('view', $model);
        }

        return $model;
    }

    /**
     * @return Collection<int, CampaignCategory>
     */
    public function list(CampaignCategoryQuery $filters): Collection
    {
        Gate::authorize('viewAny', CampaignCategory::class);

        return $this->baseQuery($filters)->get();
    }

    /**
     * @return LengthAwarePaginator<int, CampaignCategory>
     */
    public function paginate(
        int $perPage,
        int $page,
        CampaignCategoryQuery $filters
    ): LengthAwarePaginator {
        Gate::authorize('viewAny', CampaignCategory::class);

        return $this->baseQuery($filters)->paginate(perPage: $perPage, page: $page);
    }

    public function create(CreateCampaignCategoryForm $payload): CampaignCategory
    {
        Gate::authorize('create', CampaignCategory::class);

        return $this->categoryCrudService->create($payload);
    }

    public function update(CampaignCategory $category, UpdateCampaignCategoryForm $payload): CampaignCategory
    {
        Gate::authorize('update', $category);

        return $this->categoryCrudService->update($category, $payload);
    }

    /**
     * Delete a campaign category. Guarded by policy.
     */
    public function delete(CampaignCategory $category): void
    {
        Gate::authorize('delete', $category);

        $this->categoryCrudService->delete($category);
    }
}
