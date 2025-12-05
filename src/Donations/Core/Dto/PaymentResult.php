<?php

namespace Fundraiser\Donations\Core\Dto;

use Spatie\LaravelData\Data;

/**
 * Value object returned by payment providers to the Domain.
 */
class PaymentResult extends Data
{
    public function __construct(
        public bool $success,
        public ?string $reference = null,
        public ?string $message = null,

        /** @var array<string, int|string> $raw */
        public array $raw = [],
    ) {}
}
