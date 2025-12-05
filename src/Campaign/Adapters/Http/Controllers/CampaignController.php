<?php

namespace Fundraiser\Campaign\Adapters\Http\Controllers;

use Fundraiser\Campaign\Adapters\Models\Campaign;
use Fundraiser\Campaign\Core\Constants\Enums\CampaignStatus;
use Fundraiser\Campaign\Core\Dto\Forms\CreateCampaignForm;
use Fundraiser\Campaign\Core\Dto\Forms\UpdateCampaignForm;
use Fundraiser\Campaign\Core\Dto\Queries\CampaignQuery;
use Fundraiser\Campaign\Core\Services\CampaignService;
use Fundraiser\Common\Adapters\Helpers\HttpResponseHelper as Response;
use Fundraiser\Identity\Adapters\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

readonly class CampaignController
{
    public function __construct(private CampaignService $service) {}

    public function index(CampaignQuery $query): JsonResponse
    {
        if ($query->mine) {
            $query->created_by_user_id = (string) Auth::id();
        }

        $paginator = $this->service->paginate(
            $query->pagination->perPage,
            $query->pagination->page,
            $query
        );

        return Response::success($paginator);
    }

    public function show(Campaign $campaign): JsonResponse
    {
        // Eager-load relations needed for presentation-only computed fields
        $campaign->load(['category', 'creator']);

        return Response::success($campaign);
    }

    public function store(CreateCampaignForm $campaignForm): JsonResponse
    {
        $campaignForm->created_by_user_id = (string) Auth::id();
        $campaign = $this->service->create($campaignForm);

        return Response::success($campaign, status: 201, message: 'Created');
    }

    public function update(UpdateCampaignForm $campaignForm, Campaign $campaign): JsonResponse
    {
        $campaign = $this->service->update($campaign, $campaignForm);

        return Response::success($campaign, message: 'Updated');
    }

    public function approve(Campaign $campaign): JsonResponse
    {
        $user = Auth::user();
        /** @var User $user */
        $campaign = $this->service->approve($campaign, $user);

        return Response::success($campaign, message: 'Approved');
    }

    public function reject(Campaign $campaign): JsonResponse
    {
        $user = Auth::user();
        /** @var User $user */
        $campaign = $this->service->reject($campaign, $user);

        return Response::success($campaign, message: 'Rejected');
    }

    public function active(CampaignQuery $query): JsonResponse
    {
        $query->status = CampaignStatus::Active->value;

        Cache::add('campaigns:active:version', 1);

        /** @var int $version */
        $version = Cache::get('campaigns:active:version', 1);
        $perPage = $query->pagination->perPage;
        $page = $query->pagination->page;

        // Apply server-side mapping for "mine" to ensure client cannot tamper with user id
        if ($query->mine) {
            $query->created_by_user_id = (string) Auth::id();
        }

        // Build a cache key that takes into account filters to avoid serving stale/mismatched cached pages
        $searchHash = md5((string) ($query->search ?? ''));
        $category = (string) ($query->campaign_category_id ?? '');
        $creator = (string) ($query->created_by_user_id ?? '');
        $key = "campaigns:active:v{$version}:s_{$searchHash}:cat_{$category}:uid_{$creator}:per_{$perPage}:page_{$page}";

        $payload = Cache::remember($key, now()->addMinutes(10), function () use ($query) {
            return $this->service->paginate($query->pagination->perPage, $query->pagination->page, $query);
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
