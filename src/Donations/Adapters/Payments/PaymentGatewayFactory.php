<?php

namespace Fundraiser\Donations\Adapters\Payments;

use Fundraiser\Donations\Core\Payments\PaymentGatewayInterface;

class PaymentGatewayFactory
{
    public static function make(string $driver): PaymentGatewayInterface
    {
        return match ($driver) {
            default => new FakePaymentGateway,
        };
    }
}
