<?php

namespace Fundraiser\Campaign\Adapters\Http\Controllers;

use Fundraiser\Campaign\Adapters\Models\Campaign;
use Fundraiser\Campaign\Core\Constants\Enums\CampaignStatus;
use Fundraiser\Campaign\Core\Dto\Forms\CreateCampaignForm;
use Fundraiser\Campaign\Core\Dto\Forms\UpdateCampaignForm;
use Fundraiser\Campaign\Core\Dto\Queries\CampaignQuery;
use Fundraiser\Campaign\Core\Services\CampaignService;
use Fundraiser\Common\Adapters\Helpers\HttpResponseHelper as Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

readonly class CampaignController
{
    public function __construct(private CampaignService $service) {}

    public function index(CampaignQuery $query): JsonResponse
    {
        // Adapter-side convenience: allow filtering "my campaigns" via ?mine=1
        // without leaking adapter concerns into Core DTOs. We simply map it to
        // created_by_user_id when requested and the user is authenticated.
        if (request()->boolean('mine')) {
            $userId = Auth::id();
            if ($userId) {
                $query->created_by_user_id = $userId;
            }
        }

        $paginator = $this->service->paginate(
            $query->pagination->perPage,
            $query->pagination->page,
            $query
        );

        $paginator->getCollection()->load('category');

        return Response::success($paginator);
    }

    public function show(Campaign $campaign): JsonResponse
    {
        $campaign->load('category');

        return Response::success($campaign);
    }

    public function store(CreateCampaignForm $campaignForm): JsonResponse
    {
        $campaignForm->created_by_user_id = Auth::id();
        $campaign = $this->service->create($campaignForm);

        return Response::success($campaign, status: 201, message: 'Created');
    }

    public function update(UpdateCampaignForm $campaignForm, Campaign $campaign): JsonResponse
    {
        $campaign = $this->service->update($campaign, $campaignForm);

        return Response::success($campaign, message: 'Updated');
    }

    public function active(CampaignQuery $query): JsonResponse
    {
        $query->status = CampaignStatus::Active->value;

        Cache::add('campaigns:active:version', 1);

        /** @var int $version */
        $version = Cache::get('campaigns:active:version', 1);
        $perPage = $query->pagination->perPage;
        $page = $query->pagination->page;

        $key = "campaigns:active:v$version:per_$perPage:page_$page";
        $payload = Cache::remember($key, now()->addMinutes(10), function () use ($query) {
            $p = $this->service->paginate($query->pagination->perPage, $query->pagination->page, $query);
            // Eager-load category on the page items to avoid N+1 when serializing
            $p->getCollection()->load('category');

            return $p;
        });

        return Response::success($payload);
    }

    public function statuses(): JsonResponse
    {
        return Response::success([
            'statuses' => [CampaignStatus::Draft->value, CampaignStatus::PendingApproval->value],
        ]);
    }

    public function statusesForCampaign(Campaign $campaign): JsonResponse
    {
        $user = Auth::user();

        if ($user && $user->can('campaign.moderate')) {

            return Response::success([
                'campaign_id' => $campaign->id,
                'statuses' => [CampaignStatus::Active->value, CampaignStatus::PendingApproval->value, CampaignStatus::Cancelled->value],
            ]);
        }

        return Response::success([
            'campaign_id' => $campaign->id,
            'statuses' => [CampaignStatus::Draft->value, CampaignStatus::PendingApproval->value],
        ]);

    }
}
