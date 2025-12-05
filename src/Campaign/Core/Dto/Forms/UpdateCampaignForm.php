<?php

namespace Fundraiser\Campaign\Core\Dto\Forms;

use Fundraiser\Campaign\Core\Constants\Enums\CampaignStatus;
use Fundraiser\Common\Core\Constants\Enums\SupportedCurrencies;
use Illuminate\Validation\Rules\Enum as EnumRule;
use Spatie\LaravelData\Attributes\Validation\After;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Numeric as NumericAttribute;
use Spatie\LaravelData\Attributes\Validation\Rule as RuleAttribute;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Data;

/**
 * Partial update form for Campaign. All fields are optional (Sometimes) and validated when present.
 */
class UpdateCampaignForm extends Data
{
    public function __construct(
        #[Sometimes]
        #[Max(150)]
        public ?string $title = null,

        #[Sometimes]
        #[Nullable]
        #[Max(255)]
        public ?string $short_description = null,

        #[Sometimes]
        #[Nullable]
        public ?string $description = null,

        #[Sometimes]
        #[NumericAttribute]
        #[Min(1)]
        public ?float $goal_amount = null,

        #[Sometimes]
        #[RuleAttribute(new EnumRule(SupportedCurrencies::class))]
        public ?string $currency = null,

        #[Sometimes]
        #[Nullable]
        #[RuleAttribute('ulid|exists:campaign_categories,id')]
        public ?string $campaign_category_id = null,

        #[Sometimes]
        #[RuleAttribute(new EnumRule(CampaignStatus::class))]
        public ?string $status = null,

        #[Sometimes]
        #[Nullable]
        #[Date]
        public ?string $starts_at = null,

        #[Sometimes]
        #[Nullable]
        #[Date]
        #[After('starts_at')]
        public ?string $ends_at = null,

        #[Nullable]
        public ?string $image = null,

        #[Sometimes]
        #[RuleAttribute('ulid|exists:users,id')]
        public ?string $created_by_user_id = null,

        #[Sometimes]
        #[Nullable]
        #[RuleAttribute('ulid|exists:users,id')]
        public ?string $approved_by_user_id = null,
    ) {}
}
