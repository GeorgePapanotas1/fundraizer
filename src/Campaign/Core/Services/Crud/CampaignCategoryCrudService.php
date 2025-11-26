<?php

namespace Fundraiser\Campaign\Core\Services\Crud;

use Fundraiser\Campaign\Adapters\Models\CampaignCategory;
use Fundraiser\Campaign\Core\Dto\Forms\CreateCampaignCategoryForm;
use Fundraiser\Campaign\Core\Dto\Forms\UpdateCampaignCategoryForm;

/**
 * Write-layer for CampaignCategory. Contains ONLY create and update.
 */
class CampaignCategoryCrudService
{
    public function create(CreateCampaignCategoryForm $payload): CampaignCategory
    {
        /** @var array<string, mixed> $category */
        $category = $payload->toArray();

        return CampaignCategory::create($category);
    }

    /**
     * @param  CampaignCategory  $category  Model instance
     */
    public function update(CampaignCategory $category, UpdateCampaignCategoryForm $payload): CampaignCategory
    {
        // Partial updates: only apply fields that are present (non-null)
        /** @var array<string, mixed> $data */
        $data = array_filter($payload->toArray(), fn ($v) => ! is_null($v));

        $category->fill($data);
        $category->save();

        return $category->refresh();
    }

    /**
     * Delete a CampaignCategory.
     */
    public function delete(CampaignCategory $category): void
    {
        $category->delete();
    }
}
