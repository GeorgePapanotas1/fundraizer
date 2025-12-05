<?php

namespace Fundraiser\Donations\Core\Services\Crud;

use Fundraiser\Donations\Adapters\Models\Donation;

class DonationCrudService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Donation
    {
        /** @var array<string, mixed> $payload */
        $payload = $data;

        return Donation::create($payload);
    }
}
