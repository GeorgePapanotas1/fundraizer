<?php

namespace Fundraiser\Campaign\Adapters\Http\Controllers;

use Fundraiser\Campaign\Adapters\Models\Campaign;
use Fundraiser\Campaign\Core\Dto\Forms\CreateCampaignForm;
use Fundraiser\Campaign\Core\Dto\Forms\UpdateCampaignForm;
use Fundraiser\Campaign\Core\Dto\Queries\CampaignQuery;
use Fundraiser\Campaign\Core\Services\CampaignService;
use Fundraiser\Common\Adapters\Helpers\HttpResponseHelper as Response;
use Illuminate\Http\JsonResponse;

readonly class CampaignController
{
    public function __construct(private CampaignService $service) {}

    public function index(CampaignQuery $query): JsonResponse
    {
        $paginator = $this->service->paginate(
            $query->pagination->perPage,
            $query->pagination->page,
            $query
        );

        return Response::success($paginator);
    }

    public function show(Campaign $campaign): JsonResponse
    {
        return Response::success($campaign);
    }

    public function store(CreateCampaignForm $campaignForm): JsonResponse
    {
        $campaign = $this->service->create($campaignForm);

        return Response::success($campaign, status: 201, message: 'Created');
    }

    public function update(UpdateCampaignForm $campaignForm, Campaign $campaign): JsonResponse
    {
        $campaign = $this->service->update($campaign, $campaignForm);

        return Response::success($campaign, message: 'Updated');
    }
}
