<?php

namespace Fundraiser\Identity\Core\Dto\Responses;

use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Rule as RuleAttribute;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Data;

/**
 * A Core-level DTO for exposing the authenticated user's basic profile
 * and authorization capabilities (roles and permissions).
 *
 * Domain note:
 * - This DTO is framework-agnostic and only contains primitives/arrays.
 * - Adapters should construct it from their User model/guards.
 */
class MeResponse extends Data
{
    /**
     * @param array<int, string> $roles
     * @param array<int, string> $permissions
     */
    public function __construct(
        public string $id,
        #[Max(150)]
        public string $name,
        #[Email]
        public string $email,
        public array $roles = [],
        public array $permissions = [],
    ) {}
}
