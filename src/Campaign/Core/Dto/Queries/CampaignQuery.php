<?php

namespace Fundraiser\Campaign\Core\Dto\Queries;

use Fundraiser\Campaign\Core\Constants\Enums\CampaignStatus;
use Fundraiser\Common\Core\Dto\Queries\PaginationQuery;
use Illuminate\Validation\Rules\Enum as EnumRule;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Rule as RuleAttribute;
use Spatie\LaravelData\Data;

/**
 * Read-only query DTO describing available Campaign filters.
 * Core may construct this from request arrays but must keep reads side-effect free.
 */
class CampaignQuery extends Data
{
    public function __construct(
        #[RuleAttribute(new EnumRule(CampaignStatus::class))]
        public ?string $status,

        #[RuleAttribute('ulid|exists:campaign_categories,id')]
        public ?string $campaign_category_id,

        #[RuleAttribute('ulid|exists:users,id')]
        public ?string $created_by_user_id,

        #[Max(255)]
        public ?string $search,

        public PaginationQuery $pagination,

        // Placed last with default to preserve BC with positional ctor calls in tests
        #[RuleAttribute('boolean')]
        public ?bool $mine = null,

    ) {}

}
