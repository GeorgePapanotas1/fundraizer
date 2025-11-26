<?php

use Database\Factories\Fundraiser\Campaign\Adapters\Models\CampaignCategoryFactory;
use Fundraiser\Campaign\Adapters\Models\CampaignCategory;
use Fundraiser\Campaign\Core\Dto\Forms\CreateCampaignCategoryForm;
use Fundraiser\Campaign\Core\Dto\Forms\UpdateCampaignCategoryForm;
use Fundraiser\Campaign\Core\Dto\Queries\CampaignCategoryQuery;
use Fundraiser\Campaign\Core\Services\CampaignCategoryService;
use Fundraiser\Common\Core\Dto\Queries\PaginationQuery;

function makeCampaignCategoryService(): CampaignCategoryService
{
    return app(CampaignCategoryService::class);
}

it('creates a campaign category via service', function () {
    $form = new CreateCampaignCategoryForm(
        name: 'Medical',
        description: 'Medical causes'
    );

    $category = makeCampaignCategoryService()->create($form);

    expect($category)
        ->toBeInstanceOf(CampaignCategory::class)
        ->and($category->exists)->toBeTrue()
        ->and($category->name)->toBe('Medical')
        ->and($category->slug)->not()->toBe('');
});

it('updates a campaign category via service with partial DTO', function () {
    $category = CampaignCategoryFactory::new()->create(['name' => 'Education', 'description' => 'Old']);

    $update = new UpdateCampaignCategoryForm(
        name: 'Education & Schools',
        description: 'Updated description'
    );

    $updated = makeCampaignCategoryService()->update($category, $update);

    expect($updated->name)->toBe('Education & Schools')
        ->and($updated->description)->toBe('Updated description');
});

it('lists categories with search filter and paginates', function () {
    CampaignCategoryFactory::new()->create(['name' => 'Health']);
    CampaignCategoryFactory::new()->create(['name' => 'Education']);
    CampaignCategoryFactory::new()->create(['name' => 'Environment']);

    $service = makeCampaignCategoryService();

    $filters = new CampaignCategoryQuery(search: 'Edu', pagination: new PaginationQuery);
    $list = $service->list($filters);
    expect($list->count())->toBeGreaterThan(0)
        ->and($list->every(fn ($c) => str_contains($c->name, 'Edu') || str_contains((string) $c->description, 'Edu')))->toBeTrue();

    $page = $service->paginate(perPage: 2, page: 1, filters: new CampaignCategoryQuery(search: null, pagination: new PaginationQuery(perPage: 2, page: 1)));

    expect($page->perPage())->toBe(2)
        ->and($page->currentPage())->toBe(1)
        ->and($page->items())->toHaveCount(2);
});
