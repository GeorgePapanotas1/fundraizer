<?php

use Database\Factories\Fundraiser\Campaign\Adapters\Models\CampaignCategoryFactory;
use Database\Factories\Fundraiser\Campaign\Adapters\Models\CampaignFactory;
use Database\Factories\Fundraiser\Identity\Adapters\Models\UserFactory;
use Fundraiser\Campaign\Adapters\Models\Campaign;
use Fundraiser\Campaign\Core\Constants\Enums\CampaignStatus;
use Fundraiser\Campaign\Core\Dto\Forms\CreateCampaignForm;
use Fundraiser\Campaign\Core\Dto\Forms\UpdateCampaignForm;
use Fundraiser\Campaign\Core\Dto\Queries\CampaignQuery;
use Fundraiser\Campaign\Core\Services\CampaignService;
use Fundraiser\Common\Core\Constants\Enums\SupportedCurrencies;
use Fundraiser\Common\Core\Dto\Queries\PaginationQuery;

function makeCampaignService(): CampaignService
{
    return app(CampaignService::class);
}

it('creates a campaign via service and persists with correct attributes', function () {
    $user = UserFactory::new()->create();
    $category = CampaignCategoryFactory::new()->create();

    $form = new CreateCampaignForm(
        title: 'Save the Rainforest',
        short_description: 'Plant trees',
        description: 'Let us plant many trees to save the rainforest.',
        goal_amount: 10000.00,
        currency: SupportedCurrencies::Euro->value,
        campaign_category_id: $category->id,
        status: CampaignStatus::Draft->value,
        starts_at: now()->toDateTimeString(),
        ends_at: now()->addMonth()->toDateTimeString(),
        created_by_user_id: $user->id,
        approved_by_user_id: null,
    );

    $campaign = makeCampaignService()->create($form);

    expect($campaign)
        ->toBeInstanceOf(Campaign::class)
        ->and($campaign->exists)->toBeTrue()
        ->and($campaign->title)->toBe('Save the Rainforest')
        ->and($campaign->currency)->toBe('EUR')
        ->and($campaign->status)->toBe(CampaignStatus::Draft->value)
        ->and($campaign->campaign_category_id)->toBe($category->id)
        ->and($campaign->created_by_user_id)->toBe($user->id);
});

it('updates a campaign via service with partial DTO', function () {
    $campaign = CampaignFactory::new()->draft()->create();

    $update = new UpdateCampaignForm(
        title: 'Updated Title',
        short_description: null,
        description: null,
        goal_amount: 1234.56,
        currency: SupportedCurrencies::USDollar->value,
        campaign_category_id: null,
        status: CampaignStatus::Active->value,
        starts_at: null,
        ends_at: null,
        created_by_user_id: null,
        approved_by_user_id: null,
    );

    $updated = makeCampaignService()->update($campaign, $update);

    expect($updated->title)->toBe('Updated Title')
        ->and((float) $updated->goal_amount)->toBe(1234.56)
        ->and($updated->currency)->toBe('USD')
        ->and($updated->status)->toBe(CampaignStatus::Active->value);
});

it('finds by id and returns null for missing', function () {
    $existing = CampaignFactory::new()->create();
    $found = makeCampaignService()->findById($existing->id);
    $missing = makeCampaignService()->findById(str()->ulid());

    expect($found?->id)->toBe($existing->id)
        ->and($missing)->toBeNull();
});

it('lists campaigns with filters: status, category, creator, and search', function () {
    $creator = UserFactory::new()->create();
    $otherCreator = UserFactory::new()->create();
    $category = CampaignCategoryFactory::new()->create(['name' => 'Health']);
    $otherCategory = CampaignCategoryFactory::new()->create(['name' => 'Education']);

    // Create a mix
    CampaignFactory::new()->count(2)->active()->createdBy($creator)->withCategory($category)->create(['title' => 'Heart Help']);
    CampaignFactory::new()->count(1)->draft()->createdBy($creator)->withCategory($otherCategory)->create(['title' => 'Heart Study']);
    CampaignFactory::new()->count(3)->active()->createdBy($otherCreator)->withCategory($category)->create(['title' => 'School Supplies']);

    $service = makeCampaignService();

    // status filter
    $statusFiltered = $service->list(new CampaignQuery(status: CampaignStatus::Active->value, campaign_category_id: null, created_by_user_id: null, search: null, pagination: new PaginationQuery));
    expect($statusFiltered->every(fn ($c) => $c->status === CampaignStatus::Active->value))->toBeTrue();

    // category filter
    $categoryFiltered = $service->list(new CampaignQuery(status: null, campaign_category_id: $category->id, created_by_user_id: null, search: null, pagination: new PaginationQuery));
    expect($categoryFiltered->every(fn ($c) => $c->campaign_category_id === $category->id))->toBeTrue();

    // creator filter
    $creatorFiltered = $service->list(new CampaignQuery(status: null, campaign_category_id: null, created_by_user_id: $creator->id, search: null, pagination: new PaginationQuery));
    expect($creatorFiltered->every(fn ($c) => $c->created_by_user_id === $creator->id))->toBeTrue();

    // search filter (title and short_description)
    $searchFiltered = $service->list(new CampaignQuery(status: null, campaign_category_id: null, created_by_user_id: null, search: 'Heart', pagination: new PaginationQuery));
    expect($searchFiltered->count())->toBeGreaterThan(0)
        ->and($searchFiltered->contains(fn ($c) => str_contains($c->title, 'Heart') || str_contains((string) $c->short_description, 'Heart')))->toBeTrue();
});

it('paginates results respecting perPage and page', function () {
    CampaignFactory::new()->count(30)->create();
    $service = makeCampaignService();

    $filters = new CampaignQuery(status: null, campaign_category_id: null, created_by_user_id: null, search: null, pagination: new PaginationQuery(perPage: 10, page: 2));
    $page2 = $service->paginate(perPage: 10, page: 2, filters: $filters);

    expect($page2->perPage())->toBe(10)
        ->and($page2->currentPage())->toBe(2)
        ->and($page2->items())
        ->toHaveCount(10);
});
