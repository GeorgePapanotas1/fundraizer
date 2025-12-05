<?php

namespace Fundraiser\Identity\Core\Dto\Queries;

use Fundraiser\Common\Core\Dto\Queries\PaginationQuery;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Rule as RuleAttribute;
use Spatie\LaravelData\Data;

class UserQuery extends Data
{
    public function __construct(
        #[Max(255)]
        public ?string $search,

        public PaginationQuery $pagination,
    ) {}
}
