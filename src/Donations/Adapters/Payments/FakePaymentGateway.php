<?php

namespace Fundraiser\Donations\Adapters\Payments;

use Fundraiser\Donations\Core\Dto\Forms\PaymentRequestForm;
use Fundraiser\Donations\Core\Dto\PaymentResult;
use Fundraiser\Donations\Core\Payments\PaymentGatewayInterface;

/**
 * Fake payment gateway for development and tests.
 * Always returns success with a generated reference.
 */
class FakePaymentGateway implements PaymentGatewayInterface
{
    public function charge(PaymentRequestForm $request): PaymentResult
    {
        $ref = 'FAKE-'.strtoupper(bin2hex(random_bytes(6)));

        return new PaymentResult(
            success: true,
            reference: $ref,
            message: 'Payment processed by Fake gateway',
            raw: [
                'amount_cents' => $request->amount_cents,
                'currency' => $request->currency,
                'campaign_id' => $request->campaign_id,
                'user_id' => $request->user_id,
            ]
        );
    }
}
