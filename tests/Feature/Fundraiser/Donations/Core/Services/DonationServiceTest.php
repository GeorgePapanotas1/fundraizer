<?php

use Database\Factories\Fundraiser\Campaign\Adapters\Models\CampaignFactory;
use Database\Factories\Fundraiser\Identity\Adapters\Models\UserFactory;
use Fundraiser\Campaign\Core\Constants\Enums\CampaignStatus;
use Fundraiser\Donations\Core\Dto\Forms\CreateDonationForm;
use Fundraiser\Donations\Core\Dto\Forms\PaymentRequestForm;
use Fundraiser\Donations\Core\Dto\PaymentResult;
use Fundraiser\Donations\Core\Payments\PaymentGatewayInterface;
use Fundraiser\Donations\Core\Services\Crud\DonationCrudService;
use Fundraiser\Donations\Core\Services\DonationService;

it('charges via gateway and persists donation on success', function () {
    // Given an authenticated admin (see Pest.php) and an active campaign
    $campaign = CampaignFactory::new()->active()->create();
    $user = auth('api')->user() ?: UserFactory::new()->create();
    // Ensure Gate authorization uses an authenticated user on the default guard
    $this->actingAs($user);

    // Mock gateway to return a successful payment
    $this->mock(PaymentGatewayInterface::class, function ($mock) use ($campaign, $user) {
        $mock->shouldReceive('charge')
            ->once()
            ->with(Mockery::on(function ($arg) use ($campaign, $user) {
                return $arg instanceof PaymentRequestForm
                    && $arg->campaign_id === (string) $campaign->id
                    && $arg->user_id === (string) $user->id
                    && $arg->amount_cents === 2500
                    && $arg->currency === 'EUR';
            }))
            ->andReturn(new PaymentResult(true, 'TEST-REF-123', 'ok'));
    });

    // Mock CRUD service to assert persistence
    $this->mock(DonationCrudService::class, function ($mock) use ($campaign, $user) {
        $mock->shouldReceive('create')->once()->with(Mockery::on(function ($data) use ($campaign, $user) {
            return is_array($data)
                && ($data['campaign_id'] ?? null) === (string) $campaign->id
                && ($data['user_id'] ?? null) === (string) $user->id
                && ($data['amount_cents'] ?? null) === 2500
                && ($data['currency'] ?? null) === 'EUR'
                && ($data['status'] ?? null) === 'paid';
        }));
    });

    /** @var DonationService $service */
    $service = app(DonationService::class);

    $result = $service->donate($campaign, $user, new CreateDonationForm(amount_cents: 2500, currency: 'EUR'));

    expect($result->success)->toBeTrue()
        ->and($result->reference)->toBe('TEST-REF-123');
});

it('does not call gateway and does not persist when campaign is not active', function () {
    // Given a draft campaign (not accepting donations)
    $campaign = CampaignFactory::new()->draft()->create(['status' => CampaignStatus::Draft->value]);
    $user = auth('api')->user() ?: UserFactory::new()->create();
    // Ensure Gate authorization uses an authenticated user on the default guard
    $this->actingAs($user);

    // Ensure gateway is NOT called
    $this->mock(PaymentGatewayInterface::class, function ($mock) {
        $mock->shouldNotReceive('charge');
    });

    // Ensure CRUD create is NOT called
    $this->mock(DonationCrudService::class, function ($mock) {
        $mock->shouldNotReceive('create');
    });

    /** @var DonationService $service */
    $service = app(DonationService::class);

    $result = $service->donate($campaign, $user, new CreateDonationForm(amount_cents: 1500, currency: 'EUR'));

    expect($result->success)->toBeFalse()
        ->and($result->message)->toBe('Campaign is not accepting donations');
});
