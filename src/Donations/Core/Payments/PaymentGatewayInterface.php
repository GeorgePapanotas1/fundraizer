<?php

namespace Fundraiser\Donations\Core\Payments;

use Fundraiser\Donations\Core\Dto\Forms\PaymentRequestForm;
use Fundraiser\Donations\Core\Dto\PaymentResult;

interface PaymentGatewayInterface
{
    public function charge(PaymentRequestForm $request): PaymentResult;
}
