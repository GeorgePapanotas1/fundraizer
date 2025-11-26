<?php

namespace Fundraiser\Campaign\Core\Dto\Forms;

use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;

class CreateCampaignCategoryForm extends Data
{
    public function __construct(
        #[Max(100)]
        public string $name,
        public string $description,
    ) {}

}
