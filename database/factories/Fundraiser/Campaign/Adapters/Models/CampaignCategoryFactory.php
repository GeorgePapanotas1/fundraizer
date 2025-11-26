<?php

namespace Database\Factories\Fundraiser\Campaign\Adapters\Models;

use Fundraiser\Campaign\Adapters\Models\CampaignCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CampaignCategory>
 */
class CampaignCategoryFactory extends Factory
{
    protected $model = CampaignCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);

        return [
            'name' => ucfirst($name),
            'description' => $this->faker->sentence(),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
