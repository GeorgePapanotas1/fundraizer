<?php

use Database\Factories\Fundraiser\Campaign\Adapters\Models\CampaignCategoryFactory;

it('lists campaign categories with pagination via API', function () {
    CampaignCategoryFactory::new()->count(5)->create();

    $response = $this->getJson('/api/v1/campaign-categories?pagination[perPage]=2&pagination[page]=2');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                'data',
            ],
        ]);

    expect(data_get($response->json(), 'data.current_page'))->toBe(2)
        ->and(count(data_get($response->json(), 'data.data')))->toBe(2);
});

it('lists campaign categories with search filter via API', function () {
    CampaignCategoryFactory::new()->create(['name' => 'Health', 'description' => 'Medical']);
    CampaignCategoryFactory::new()->create(['name' => 'Education', 'description' => 'Schools']);

    $response = $this->getJson('/api/v1/campaign-categories?search=Edu&pagination[perPage]=10&pagination[page]=1');

    $response->assertOk();
    $items = data_get($response->json(), 'data.data');
    expect($items)->toBeArray()
        ->and(collect($items)->every(fn ($c) => str_contains($c['name'], 'Edu') || str_contains((string) ($c['description'] ?? ''), 'Edu')))->toBeTrue();
});

it('shows a single campaign category via API', function () {
    $category = CampaignCategoryFactory::new()->create(['name' => 'Environment']);

    $response = $this->getJson('/api/v1/campaign-categories/'.$category->id);

    $response->assertOk()
        ->assertJsonPath('data.id', $category->id)
        ->assertJsonPath('data.name', 'Environment');
});

it('stores a campaign category via API with CreateCampaignCategoryForm DTO', function () {
    $payload = [
        'name' => 'Animals',
        'description' => 'Animal welfare',
    ];

    $response = $this->postJson('/api/v1/campaign-categories', $payload);

    $response->assertCreated()
        ->assertJsonPath('message', 'Created')
        ->assertJsonPath('data.name', 'Animals');

    $this->assertDatabaseHas('campaign_categories', [
        'name' => 'Animals',
        'slug' => 'animals',
    ]);
});

it('updates a campaign category via API with UpdateCampaignCategoryForm DTO', function () {
    $category = CampaignCategoryFactory::new()->create(['name' => 'Env', 'description' => 'Old']);

    $payload = [
        'name' => 'Environment',
        'description' => 'Updated description',
    ];

    $response = $this->patchJson('/api/v1/campaign-categories/'.$category->id, $payload);

    $response->assertOk()
        ->assertJsonPath('message', 'Updated')
        ->assertJsonPath('data.name', 'Environment')
        ->assertJsonPath('data.description', 'Updated description');

    $this->assertDatabaseHas('campaign_categories', [
        'id' => $category->id,
        'name' => 'Environment',
        'description' => 'Updated description',
    ]);
});
