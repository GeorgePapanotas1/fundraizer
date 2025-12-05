<?php

namespace Fundraiser\Donations\Adapters\Models;

use Database\Factories\Fundraiser\Donations\Adapters\Models\DonationFactory;
use Fundraiser\Campaign\Adapters\Models\Campaign;
use Fundraiser\Identity\Adapters\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    /** @use HasFactory<DonationFactory> */
    use HasFactory;

    use HasUlids;

    protected $table = 'donations';

    protected $fillable = [
        'campaign_id',
        'user_id',
        'amount_cents',
        'currency',
        'status',
        'provider',
        'provider_reference',
        'meta',
    ];

    protected $casts = [
        'amount_cents' => 'integer',
        'meta' => 'array',
    ];

    /** @return BelongsTo<Campaign, $this> */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
