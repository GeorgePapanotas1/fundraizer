<?php

use Database\Factories\Fundraiser\Campaign\Adapters\Models\CampaignFactory;
use Database\Factories\Fundraiser\Identity\Adapters\Models\UserFactory;
use Fundraiser\Campaign\Core\Constants\Enums\CampaignStatus;
use Fundraiser\Campaign\Core\Dto\Forms\UpdateCampaignForm;
use Fundraiser\Campaign\Core\Services\CampaignService;
use Fundraiser\Common\Core\Constants\Enums\SupportedCurrencies;
use Illuminate\Auth\Access\AuthorizationException;

function makeAuthCampaignService(): CampaignService
{
    return app(CampaignService::class);
}

it('allows employee to update own campaign but denies updating others', function () {
    $owner = UserFactory::new()->create();
    $owner->assignRole('employee');

    $other = UserFactory::new()->create();
    $other->assignRole('employee');

    $campaign = CampaignFactory::new()->draft()->createdBy($owner)->create();

    // As owner (employee with update_own), update succeeds
    $this->actingAs($owner, 'api');
    $update = new UpdateCampaignForm(
        title: 'Owned Update',
        short_description: null,
        description: null,
        goal_amount: 111.11,
        currency: SupportedCurrencies::Euro->value,
        campaign_category_id: null,
        status: CampaignStatus::Active->value,
        starts_at: null,
        ends_at: null,
        created_by_user_id: null,
        approved_by_user_id: null,
    );
    $updated = makeAuthCampaignService()->update($campaign, $update);
    expect($updated->title)->toBe('Owned Update');

    // As non-owner employee, update must be denied
    $this->actingAs($other, 'api');
    $update2 = new UpdateCampaignForm(title: 'Hacker');
    expect(fn () => makeAuthCampaignService()->update($campaign->refresh(), $update2))
        ->toThrow(AuthorizationException::class);
});

it('allows csr_admin to update any campaign', function () {
    $creator = UserFactory::new()->create();
    $creator->assignRole('employee');

    $admin = UserFactory::new()->create();
    $admin->assignRole('csr_admin');

    $campaign = CampaignFactory::new()->draft()->createdBy($creator)->create();

    $this->actingAs($admin, 'api');
    $update = new UpdateCampaignForm(title: 'Admin Edit', status: CampaignStatus::Active->value);
    $updated = makeAuthCampaignService()->update($campaign, $update);

    expect($updated->title)->toBe('Admin Edit')
        ->and($updated->status)->toBe(CampaignStatus::Active->value);
});

it('service delete: employee can delete own, not others; csr_admin can delete any', function () {
    $owner = UserFactory::new()->create();
    $owner->assignRole('employee');
    $other = UserFactory::new()->create();
    $other->assignRole('employee');

    $campaign = CampaignFactory::new()->createdBy($owner)->create();

    // owner employee can delete own
    $this->actingAs($owner, 'api');
    expect(fn () => makeAuthCampaignService()->delete($campaign))->not()->toThrow(Exception::class);
    $this->assertSoftDeleted('campaigns', ['id' => $campaign->id]);

    // other employee cannot delete
    $this->actingAs($other, 'api');
    $campaign2 = CampaignFactory::new()->createdBy($owner)->create();
    expect(fn () => makeAuthCampaignService()->delete($campaign2))->toThrow(Illuminate\Auth\Access\AuthorizationException::class);

    // csr_admin can delete any
    $admin = UserFactory::new()->create();
    $admin->assignRole('csr_admin');
    $this->actingAs($admin, 'api');
    $campaign3 = CampaignFactory::new()->createdBy($owner)->create();
    expect(fn () => makeAuthCampaignService()->delete($campaign3))->not()->toThrow(Exception::class);
    $this->assertSoftDeleted('campaigns', ['id' => $campaign3->id]);
});

it('policy moderate: employee denied; csr_admin and system_admin allowed', function () {
    $campaign = CampaignFactory::new()->create();

    // employee denied
    $employee = UserFactory::new()->create();
    $employee->assignRole('employee');
    $this->actingAs($employee, 'api');
    expect(fn () => Gate::authorize('moderate', $campaign))
        ->toThrow(Illuminate\Auth\Access\AuthorizationException::class);

    // csr_admin allowed
    $csr = UserFactory::new()->create();
    $csr->assignRole('csr_admin');
    $this->actingAs($csr, 'api');
    expect(fn () => Gate::authorize('moderate', $campaign))->not()->toThrow(Exception::class);

    // system_admin allowed
    $sys = UserFactory::new()->create();
    $sys->assignRole('system_admin');
    $this->actingAs($sys, 'api');
    expect(fn () => Gate::authorize('moderate', $campaign))->not()->toThrow(Exception::class);
});
