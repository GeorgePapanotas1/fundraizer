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
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Rule as RuleAttribute;
use Spatie\LaravelData\Data;

class CreateCampaignForm extends Data
{
    public function __construct(
        #[Required]
        #[Max(150)]
        public string $title,

        #[Nullable]
        #[Max(255)]
        public ?string $short_description,

        #[Nullable]
        public ?string $description,

        #[Required]
        #[NumericAttribute]
        #[Min(0.01)]
        public float $goal_amount,

        #[Required]
        #[RuleAttribute(new EnumRule(SupportedCurrencies::class))]
        public string $currency,

        #[Nullable]
        #[RuleAttribute('ulid|exists:campaign_categories,id')]
        public ?string $campaign_category_id,

        #[Required]
        #[RuleAttribute(new EnumRule(CampaignStatus::class))]
        public string $status,

        #[Nullable]
        #[Date]
        public ?string $starts_at,

        #[Nullable]
        #[Date]
        #[After('starts_at')]
        public ?string $ends_at,

        #[Required]
        #[RuleAttribute('ulid|exists:users,id')]
        public string $created_by_user_id,

        #[Nullable]
        #[RuleAttribute('ulid|exists:users,id')]
        public ?string $approved_by_user_id,
    ) {}
}
