<?php

namespace Fundraiser\Common\Core\Dto\Queries;

use Spatie\LaravelData\Data;

class PaginationQuery extends Data
{
    public function __construct(
        public int $perPage = 15,
        public int $page = 1
    ) {}

}
