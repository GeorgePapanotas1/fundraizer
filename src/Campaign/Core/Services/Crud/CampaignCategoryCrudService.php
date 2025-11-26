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
        return CampaignCategory::create($payload->toArray());
    }

    /**
     * @param  CampaignCategory  $category  Model instance
     */
    public function update(CampaignCategory $category, UpdateCampaignCategoryForm $payload): CampaignCategory
    {
        $category->fill($payload->toArray());
        $category->save();

        return $category->refresh();
    }
}
