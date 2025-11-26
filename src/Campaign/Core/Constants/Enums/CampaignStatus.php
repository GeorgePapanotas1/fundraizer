<?php

namespace Fundraiser\Campaign\Core\Constants\Enums;

enum CampaignStatus: string
{
    case Draft = 'draft';
    case PendingApproval = 'pending_approval';
    case Active = 'active';
    case Closed = 'closed';
    case Cancelled = 'cancelled';

    /**
     * @return array<string>
     */
    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
