<?php

namespace Database\Factories\Fundraiser\Campaign\Adapters\Models;

use Fundraiser\Campaign\Adapters\Models\Campaign;
use Fundraiser\Campaign\Adapters\Models\CampaignCategory;
use Fundraiser\Campaign\Core\Constants\Enums\CampaignStatus;
use Fundraiser\Identity\Adapters\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Campaign>
 */
class CampaignFactory extends Factory
{
    protected $model = Campaign::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(3);

        return [
            'title' => $title,
            'short_description' => $this->faker->sentence(),
            'description' => $this->faker->paragraphs(2, true),

            'goal_amount' => $this->faker->randomFloat(2, 100, 50000),
            'currency' => 'EUR',
            'status' => $this->faker->randomElement(CampaignStatus::all()),

            'campaign_category_id' => CampaignCategory::factory(),
            'starts_at' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'ends_at' => $this->faker->dateTimeBetween('+1 month', '+6 months'),

            // leave user IDs null until Identity is ready
            'created_by_user_id' => User::factory(),
            'approved_by_user_id' => User::factory(),
        ];
    }

    public function active(): self
    {
        return $this->state(fn () => ['status' => CampaignStatus::Active->value]);
    }

    public function draft(): self
    {
        return $this->state(fn () => ['status' => CampaignStatus::Draft->value]);
    }

    public function pendingApproval(): self
    {
        return $this->state(fn () => ['status' => CampaignStatus::PendingApproval->value]);
    }

    public function closed(): self
    {
        return $this->state(fn () => ['status' => CampaignStatus::Closed->value]);
    }

    public function cancelled(): self
    {
        return $this->state(fn () => ['status' => CampaignStatus::Cancelled->value]);
    }

    public function createdBy(User $user): self
    {
        return $this->state(fn () => [
            'created_by_user_id' => $user->id,
        ]);
    }

    public function approvedBy(User $user): self
    {
        return $this->state(fn () => [
            'approved_by_user_id' => $user->id,
        ]);
    }

    public function withCategory(CampaignCategory $category): self
    {
        return $this->state(fn () => [
            'campaign_category_id' => $category->id,
        ]);
    }
}
