<?php

namespace Fundraiser\Campaign\Core\Constants\Enums;

enum CampaignStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Closed = 'closed';
    case Cancelled = 'cancelled';

    // write a custom function that returns an array of all the values
    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
