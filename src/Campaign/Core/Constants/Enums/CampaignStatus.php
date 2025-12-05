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

    /**
     * Human-readable label for each status to be used by Adapters/UI.
     */
    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::PendingApproval => 'Pending approval',
            self::Active => 'Active',
            self::Closed => 'Closed',
            self::Cancelled => 'Cancelled',
        };
    }

    /**
     * Convenience helper to resolve a label from a raw value safely.
     */
    public static function labelFor(?string $value): string
    {
        $enum = $value ? self::tryFrom($value) : null;
        return $enum?->label() ?? (string) ($value ?? '');
    }
}
