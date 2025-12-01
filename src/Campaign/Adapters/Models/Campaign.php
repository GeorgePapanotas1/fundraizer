<?php

namespace Fundraiser\Campaign\Adapters\Models;

use Database\Factories\Fundraiser\Campaign\Adapters\Models\CampaignFactory;
use Fundraiser\Campaign\Core\Constants\Enums\CampaignStatus;
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
        'image',
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

    /**
     * Append human-readable status text to JSON output while keeping the enum value in `status`.
     */
    protected $appends = [
        'status_text',
        'category_name',
        'is_mine',
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
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    /**
     * Use slug for implicit route-model binding.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Human-readable status label for UI display.
     */
    public function getStatusTextAttribute(): string
    {
        /** @var ?string $currentStatus */
        $currentStatus = $this->attributes['status'] ?? null;

        return CampaignStatus::labelFor($currentStatus);
    }

    /**
     * Expose related category name directly on the model JSON: `category_name`.
     */
    public function getCategoryNameAttribute(): ?string
    {
        return $this->relationLoaded('category') ? ($this->category?->name) : null;
    }

    /**
     * Indicates whether the authenticated user is the creator of the campaign.
     * Adapter concern only; safe for UI to decide if "Edit" link should show.
     */
    public function getIsMineAttribute(): bool
    {
        /** @var string|null $authId */
        $authId = \Illuminate\Support\Facades\Auth::id();
        /** @var string|null $creatorId */
        $creatorId = $this->attributes['created_by_user_id'] ?? null;
        return $authId !== null && $creatorId !== null && $authId === $creatorId;
    }
}
