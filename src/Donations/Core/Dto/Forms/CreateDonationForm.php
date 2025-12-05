<?php

namespace Fundraiser\Donations\Core\Dto\Forms;

use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Rule as RuleAttribute;
use Spatie\LaravelData\Data;

/**
 * Form DTO for creating a Donation. Used by Adapters to pass validated input into Core.
 */
class CreateDonationForm extends Data
{
    public function __construct(
        #[Min(1)]
        public int $amount_cents,

        #[RuleAttribute('string|size:3')]
        public string $currency = 'EUR',
    ) {}
}
