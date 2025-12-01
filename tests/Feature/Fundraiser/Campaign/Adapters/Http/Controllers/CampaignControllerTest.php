<?php

use Database\Factories\Fundraiser\Campaign\Adapters\Models\CampaignCategoryFactory;
use Database\Factories\Fundraiser\Campaign\Adapters\Models\CampaignFactory;
use Database\Factories\Fundraiser\Identity\Adapters\Models\UserFactory;
use Fundraiser\Campaign\Adapters\Models\Campaign;
use Fundraiser\Campaign\Core\Constants\Enums\CampaignStatus;
use Fundraiser\Campaign\Core\Dto\Forms\UpdateCampaignForm;
use Fundraiser\Campaign\Core\Services\CampaignService;
use Fundraiser\Common\Core\Constants\Enums\SupportedCurrencies;
use Illuminate\Testing\Fluent\AssertableJson;

it('lists campaigns with pagination and filters via API', function () {
    $category = CampaignCategoryFactory::new()->create(['name' => 'Medical']);
    $creator = UserFactory::new()->create();

    CampaignFactory::new()->count(3)->active()->withCategory($category)->createdBy($creator)->create(['title' => 'Heart Project']);
    CampaignFactory::new()->count(2)->draft()->create(['title' => 'Other Project']);

    $response = $this->getJson('/api/v1/campaigns?status='.CampaignStatus::Active->value.'&campaign_category_id='.$category->id.'&created_by_user_id='.$creator->id.'&search=Heart&pagination[perPage]=2&pagination[page]=1');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                'data', // paginator items array
            ],
        ]);

    // Ensure we got paginated first page with 2 items
    expect(data_get($response->json(), 'data.current_page'))->toBe(1)
        ->and(count(data_get($response->json(), 'data.data')))->toBe(2);
});

it('shows a single campaign via API', function () {
    $campaign = CampaignFactory::new()->create();

    $response = $this->getJson('/api/v1/campaigns/'.$campaign->slug);

    $response->assertOk()
        ->assertJsonPath('data.id', $campaign->id)
        ->assertJsonPath('data.title', $campaign->title);
});

it('stores a campaign via API with CreateCampaignForm DTO', function () {
    $user = UserFactory::new()->create();
    $category = CampaignCategoryFactory::new()->create();

    $payload = [
        'title' => 'Save the Ocean',
        'short_description' => 'Clean beaches',
        'description' => 'We will organize cleanups.',
        'goal_amount' => 25000.50,
        'currency' => SupportedCurrencies::Euro->value,
        'campaign_category_id' => $category->id,
        'status' => CampaignStatus::Draft->value,
        'starts_at' => now()->toDateTimeString(),
        'ends_at' => now()->addMonth()->toDateTimeString(),
        'created_by_user_id' => $user->id,
        'approved_by_user_id' => null,
    ];

    $response = $this->postJson('/api/v1/campaigns', $payload);

    $response->assertCreated()
        ->assertJsonPath('message', 'Created')
        ->assertJsonPath('data.title', 'Save the Ocean')
        ->assertJsonPath('data.currency', SupportedCurrencies::Euro->value);

    $this->assertDatabaseHas('campaigns', [
        'title' => 'Save the Ocean',
        'currency' => SupportedCurrencies::Euro->value,
        'status' => CampaignStatus::Draft->value,
    ]);
});

it('rejects creating a campaign with forbidden status (e.g. active)', function () {
    $user = UserFactory::new()->create();
    $category = CampaignCategoryFactory::new()->create();

    $payload = [
        'title' => 'Forbidden Status',
        'short_description' => 'x',
        'description' => 'y',
        'goal_amount' => 100.00,
        'currency' => SupportedCurrencies::Euro->value,
        'campaign_category_id' => $category->id,
        'status' => CampaignStatus::Active->value, // not allowed on creation
        'starts_at' => now()->toDateTimeString(),
        'ends_at' => now()->addDay()->toDateTimeString(),
        'created_by_user_id' => $user->id,
        'approved_by_user_id' => null,
    ];

    $response = $this->postJson('/api/v1/campaigns', $payload);

    $response->assertStatus(422);
});

it('defaults status to pending_approval when omitted on create', function () {
    $user = UserFactory::new()->create();
    $category = CampaignCategoryFactory::new()->create();

    $payload = [
        'title' => 'Default Pending',
        'short_description' => 'x',
        'description' => 'y',
        'goal_amount' => 100.00,
        'currency' => SupportedCurrencies::Euro->value,
        'campaign_category_id' => $category->id,
        // no status provided
        'starts_at' => now()->toDateTimeString(),
        'ends_at' => now()->addDay()->toDateTimeString(),
        'created_by_user_id' => $user->id,
        'approved_by_user_id' => null,
    ];

    $response = $this->postJson('/api/v1/campaigns', $payload);

    $response->assertCreated();
    $this->assertDatabaseHas('campaigns', [
        'title' => 'Default Pending',
        'status' => CampaignStatus::PendingApproval->value,
    ]);
});

it('returns creation statuses for dropdown via API', function () {
    $response = $this->getJson('/api/v1/campaigns/statuses');

    $response->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->whereType('data.statuses', 'array')
            ->where('data.statuses', ['draft', 'pending_approval'])
            ->etc()
        );
});

it('returns edit statuses for employee vs moderator', function () {
    $campaign = CampaignFactory::new()->draft()->create();

    // As employee: only draft & pending_approval
    $employee = UserFactory::new()->create();
    $employee->assignRole('employee');
    $this->actingAs($employee, 'api');
    $respEmp = $this->getJson('/api/v1/campaigns/'.$campaign->slug.'/statuses');
    $respEmp->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('data.campaign_id', $campaign->id)
            ->where('data.statuses', ['draft', 'pending_approval'])
            ->etc()
        );

    // As csr_admin: active, pending_approval, cancelled
    $csr = UserFactory::new()->create();
    $csr->assignRole('csr_admin');
    $this->actingAs($csr, 'api');
    $respCsr = $this->getJson('/api/v1/campaigns/'.$campaign->slug.'/statuses');
    $respCsr->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->where('data.campaign_id', $campaign->id)
            ->where('data.statuses', ['active', 'pending_approval', 'cancelled'])
            ->etc()
        );
});

it('updates a campaign via API with UpdateCampaignForm DTO', function () {
    $campaign = CampaignFactory::new()->draft()->create(['title' => 'Before']);

    $payload = [
        'title' => 'After',
        'goal_amount' => 123.45,
        'status' => CampaignStatus::Active->value,
        'currency' => SupportedCurrencies::USDollar->value,
    ];

    $response = $this->patchJson('/api/v1/campaigns/'.$campaign->slug, $payload);

    $response->assertOk()
        ->assertJsonPath('message', 'Updated')
        ->assertJsonPath('data.title', 'After')
        ->assertJsonPath('data.status', CampaignStatus::Active->value)
        ->assertJsonPath('data.currency', SupportedCurrencies::USDollar->value);

    $this->assertDatabaseHas('campaigns', [
        'id' => $campaign->id,
        'title' => 'After',
        'status' => CampaignStatus::Active->value,
        'currency' => SupportedCurrencies::USDollar->value,
    ]);
});

it('serves cached list of active campaigns and invalidates on status changes', function () {
    // Prepare data: 3 active + 2 draft campaigns
    CampaignFactory::new()->count(3)->active()->create();
    CampaignFactory::new()->count(2)->draft()->create();

    // First request should cache the result
    $resp1 = $this->getJson('/api/v1/campaigns/active?pagination[perPage]=50&pagination[page]=1');
    $resp1->assertOk();
    $count1 = count(data_get($resp1->json(), 'data.data'));
    expect($count1)->toBe(3);

    // Second request should hit cache and return same count
    $resp2 = $this->getJson('/api/v1/campaigns/active?pagination[perPage]=50&pagination[page]=1');
    $resp2->assertOk();
    $count2 = count(data_get($resp2->json(), 'data.data'));
    expect($count2)->toBe(3);

    // Now change one active to draft via service update → should invalidate cache version via observer
    /** @var CampaignService $service */
    $service = app(CampaignService::class);
    $firstActiveId = data_get($resp1->json(), 'data.data.0.id');
    $firstActive = Campaign::query()->findOrFail($firstActiveId);

    $service->update($firstActive, new UpdateCampaignForm(status: CampaignStatus::Draft->value));
    $firstActive = Campaign::query()->findOrFail($firstActiveId);

    // Fetch again → should reflect new count (2)
    $resp3 = $this->getJson('/api/v1/campaigns/active?pagination[perPage]=50&pagination[page]=1');
    $resp3->assertOk();
    $count3 = count(data_get($resp3->json(), 'data.data'));
    expect($count3)->toBe(2);

    // Promote a draft to active via service update and ensure cache reflects (back to 3)
    $aDraft = Campaign::query()->where('status', CampaignStatus::Draft->value)->firstOrFail();
    $service->update($aDraft, new UpdateCampaignForm(status: CampaignStatus::Active->value));

    $resp4 = $this->getJson('/api/v1/campaigns/active?pagination[perPage]=50&pagination[page]=1');
    $resp4->assertOk();
    $count4 = count(data_get($resp4->json(), 'data.data'));
    expect($count4)->toBe(3);
});
