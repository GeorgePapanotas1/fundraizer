<?php

namespace Fundraiser\Campaign\Core\Dto\Queries;

use Fundraiser\Common\Core\Dto\Queries\PaginationQuery;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Data;

/**
 * Read-only query DTO describing available CampaignCategory filters.
 */
class CampaignCategoryQuery extends Data
{
    public function __construct(
        #[Max(255)]
        public ?string $search,
        public PaginationQuery $pagination,
    ) {}
}
