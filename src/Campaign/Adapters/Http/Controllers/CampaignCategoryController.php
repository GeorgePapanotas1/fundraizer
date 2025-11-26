<?php

namespace Fundraiser\Campaign\Adapters\Http\Controllers;

use Fundraiser\Campaign\Adapters\Models\CampaignCategory;
use Fundraiser\Campaign\Core\Dto\Forms\CreateCampaignCategoryForm;
use Fundraiser\Campaign\Core\Dto\Forms\UpdateCampaignCategoryForm;
use Fundraiser\Campaign\Core\Dto\Queries\CampaignCategoryQuery;
use Fundraiser\Campaign\Core\Services\CampaignCategoryService;
use Fundraiser\Common\Adapters\Helpers\HttpResponseHelper as Response;
use Illuminate\Http\JsonResponse;

readonly class CampaignCategoryController
{
    public function __construct(private CampaignCategoryService $service) {}

    public function index(CampaignCategoryQuery $categoryQuery): JsonResponse
    {
        $paginator = $this->service->paginate(
            $categoryQuery->pagination->perPage,
            $categoryQuery->pagination->page,
            $categoryQuery
        );

        return Response::success($paginator);
    }

    public function show(CampaignCategory $campaignCategory): JsonResponse
    {
        return Response::success($campaignCategory);
    }

    public function store(CreateCampaignCategoryForm $categoryForm): JsonResponse
    {
        $category = $this->service->create($categoryForm);

        return Response::success($category, status: 201, message: 'Created');
    }

    public function update(UpdateCampaignCategoryForm $categoryForm, CampaignCategory $campaignCategory): JsonResponse
    {
        $category = $this->service->update($campaignCategory, $categoryForm);

        return Response::success($category, message: 'Updated');
    }
}
