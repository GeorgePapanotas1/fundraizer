<?php

namespace Fundraiser\Campaign\Core\Dto\Forms;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Data;

/**
 * Partial update form for CampaignCategory. Fields are optional and validated when present.
 */
class UpdateCampaignCategoryForm extends Data
{
    public function __construct(
        #[Sometimes]
        #[Max(100)]
        public ?string $name = null,

        #[Sometimes]
        public ?string $description = null,
    ) {}
}
