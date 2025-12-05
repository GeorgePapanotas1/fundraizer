<?php

namespace Fundraiser\Donations\Core\Services;

use Fundraiser\Campaign\Adapters\Models\Campaign;
use Fundraiser\Campaign\Core\Constants\Enums\CampaignStatus;
use Fundraiser\Donations\Core\Dto\Forms\CreateDonationForm;
use Fundraiser\Donations\Core\Dto\Forms\PaymentRequestForm;
use Fundraiser\Donations\Core\Dto\PaymentResult;
use Fundraiser\Donations\Core\Payments\PaymentGatewayInterface;
use Fundraiser\Donations\Core\Services\Crud\DonationCrudService;
use Fundraiser\Identity\Adapters\Models\User;
use Illuminate\Support\Facades\Gate;

readonly class DonationService
{
    public function __construct(
        private PaymentGatewayInterface $gateway,
        private DonationCrudService $donationCrudService,
    ) {}

    public function donate(Campaign $campaign, User $user, CreateDonationForm $form): PaymentResult
    {
        Gate::authorize('view', $campaign);

        if ($campaign->status !== CampaignStatus::Active->value) {
            return new PaymentResult(false, null, 'Campaign is not accepting donations');
        }

        $request = new PaymentRequestForm(
            campaign_id: (string) $campaign->id,
            user_id: (string) $user->id,
            amount_cents: (int) $form->amount_cents,
            currency: $form->currency,
            metadata: [
                'campaign_slug' => $campaign->slug,
                'campaign_title' => $campaign->title,
                'user_email' => $user->email,
            ],
        );

        // Call the gateway via domain interface
        $result = $this->gateway->charge($request);

        // Persist the donation via CRUD service (write layer)
        $this->donationCrudService->create([
            'campaign_id' => (string) $campaign->id,
            'user_id' => (string) $user->id,
            'amount_cents' => (int) $form->amount_cents,
            'currency' => $form->currency,
            'status' => $result->success ? 'paid' : 'failed',
            'provider' => get_class($this->gateway),
            'provider_reference' => $result->reference,
            'meta' => $result->raw,
        ]);

        return $result;
    }
}
