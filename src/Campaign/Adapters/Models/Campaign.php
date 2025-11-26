<?php

namespace Fundraiser\Campaign\Adapters\Models;

use Database\Factories\Fundraiser\Campaign\Adapters\Models\CampaignFactory;
use Fundraiser\Identity\Adapters\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Campaign extends Model
{
    /** @use HasFactory<CampaignFactory> */
    use HasFactory;

    use HasSlug;
    use HasUlids;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'goal_amount',
        'currency',
        'status',
        'campaign_category_id',
        'starts_at',
        'ends_at',
        'created_by_user_id',
        'approved_by_user_id',
    ];

    protected $casts = [
        'goal_amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /** @return BelongsTo<CampaignCategory, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(CampaignCategory::class, 'campaign_category_id');
    }

    /** @return BelongsTo<User, $this> */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /** @return BelongsTo<User, $this> */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
