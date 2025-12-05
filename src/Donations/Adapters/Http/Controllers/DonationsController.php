<?php

namespace Fundraiser\Donations\Adapters\Http\Controllers;

use Fundraiser\Campaign\Adapters\Models\Campaign;
use Fundraiser\Common\Adapters\Helpers\HttpResponseHelper as Response;
use Fundraiser\Donations\Adapters\Jobs\Donations\SendDonationReceipt;
use Fundraiser\Donations\Core\Dto\Forms\CreateDonationForm;
use Fundraiser\Donations\Core\Services\DonationService;
use Fundraiser\Identity\Adapters\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

readonly class DonationsController
{
    public function __construct(private DonationService $service) {}

    public function store(Campaign $campaign, CreateDonationForm $form): JsonResponse
    {
        $user = Auth::user();

        /** @var User $user */
        $result = $this->service->donate($campaign, $user, $form);

        if (! $result->success) {
            return Response::error($result->message ?? 'Payment failed', 422);
        }

        // Dispatch async job to send donation receipt email
        SendDonationReceipt::dispatch(
            user: $user,
            campaign: $campaign,
            amountCents: (int) $form->amount_cents,
            currency: (string) $form->currency,
            reference: $result->reference,
        );

        return Response::success([
            'reference' => $result->reference,
            'message' => $result->message ?? 'Donation completed',
        ], message: 'Donation confirmed');
    }
}
