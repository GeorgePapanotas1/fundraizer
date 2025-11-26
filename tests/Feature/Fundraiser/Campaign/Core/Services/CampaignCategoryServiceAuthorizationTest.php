<?php

use Database\Factories\Fundraiser\Campaign\Adapters\Models\CampaignCategoryFactory;
use Database\Factories\Fundraiser\Identity\Adapters\Models\UserFactory;
use Fundraiser\Campaign\Core\Dto\Forms\CreateCampaignCategoryForm;
use Fundraiser\Campaign\Core\Dto\Forms\UpdateCampaignCategoryForm;
use Fundraiser\Campaign\Core\Services\CampaignCategoryService;
use Illuminate\Auth\Access\AuthorizationException;

function makeAuthCampaignCategoryService(): CampaignCategoryService
{
    return app(CampaignCategoryService::class);
}

it('denies employee from creating or updating categories (requires moderate)', function () {
    $employee = UserFactory::new()->create();
    $employee->assignRole('employee');
    $this->actingAs($employee, 'api');

    // Create should be denied
    $create = new CreateCampaignCategoryForm(name: 'Health', description: 'desc');
    expect(fn () => makeAuthCampaignCategoryService()->create($create))
        ->toThrow(AuthorizationException::class);

    // Update should be denied
    $category = CampaignCategoryFactory::new()->create(['name' => 'Old']);
    $update = new UpdateCampaignCategoryForm(name: 'New');
    expect(fn () => makeAuthCampaignCategoryService()->update($category, $update))
        ->toThrow(AuthorizationException::class);
});

it('allows csr_admin to create and update categories', function () {
    $admin = UserFactory::new()->create();
    $admin->assignRole('csr_admin');
    $this->actingAs($admin, 'api');

    $create = new CreateCampaignCategoryForm(name: 'Environment', description: 'desc');
    $created = makeAuthCampaignCategoryService()->create($create);
    expect($created->exists)->toBeTrue();

    $update = new UpdateCampaignCategoryForm(description: 'updated');
    $updated = makeAuthCampaignCategoryService()->update($created, $update);
    expect($updated->description)->toBe('updated');
});

it('service delete category: employee denied; csr_admin allowed', function () {
    $employee = UserFactory::new()->create();
    $employee->assignRole('employee');
    $this->actingAs($employee, 'api');

    $category = CampaignCategoryFactory::new()->create(['name' => 'To Delete']);

    // employee cannot delete (requires campaign.moderate)
    expect(fn () => makeAuthCampaignCategoryService()->delete($category))
        ->toThrow(AuthorizationException::class);

    // csr_admin can delete
    $admin = UserFactory::new()->create();
    $admin->assignRole('csr_admin');
    $this->actingAs($admin, 'api');
    expect(fn () => makeAuthCampaignCategoryService()->delete($category))->not()->toThrow(Exception::class);
    $this->assertDatabaseMissing('campaign_categories', ['id' => $category->id]);
});
