<?php

use Database\Factories\Fundraiser\Campaign\Adapters\Models\CampaignCategoryFactory;
use Database\Factories\Fundraiser\Campaign\Adapters\Models\CampaignFactory;
use Database\Factories\Fundraiser\Identity\Adapters\Models\UserFactory;
use Fundraiser\Campaign\Core\Constants\Enums\CampaignStatus;
use Fundraiser\Common\Core\Constants\Enums\SupportedCurrencies;

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

    $response = $this->getJson('/api/v1/campaigns/'.$campaign->id);

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

it('updates a campaign via API with UpdateCampaignForm DTO', function () {
    $campaign = CampaignFactory::new()->draft()->create(['title' => 'Before']);

    $payload = [
        'title' => 'After',
        'goal_amount' => 123.45,
        'status' => CampaignStatus::Active->value,
        'currency' => SupportedCurrencies::USDollar->value,
    ];

    $response = $this->patchJson('/api/v1/campaigns/'.$campaign->id, $payload);

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
