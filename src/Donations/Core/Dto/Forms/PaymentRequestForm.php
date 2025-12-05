<?php

namespace Fundraiser\Donations\Core\Dto\Forms;

use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Rule as RuleAttribute;
use Spatie\LaravelData\Data;

class PaymentRequestForm extends Data
{
    public function __construct(
        #[RuleAttribute('ulid')]
        public string $campaign_id,

        #[RuleAttribute('ulid')]
        public string $user_id,

        #[Min(1)]
        public int $amount_cents,

        #[RuleAttribute('string|size:3')]
        public string $currency,

        /** @var array<string, string> $metadata */
        public array $metadata = [],
    ) {}
}
