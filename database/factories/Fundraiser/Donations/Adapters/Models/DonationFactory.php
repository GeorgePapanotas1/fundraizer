<?php

namespace Database\Factories\Fundraiser\Donations\Adapters\Models;

use Fundraiser\Campaign\Adapters\Models\Campaign;
use Fundraiser\Donations\Adapters\Models\Donation;
use Fundraiser\Identity\Adapters\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Donation>
 */
class DonationFactory extends Factory
{
    protected $model = Donation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'campaign_id' => Campaign::factory(),
            'user_id' => User::factory(),
            'amount_cents' => $this->faker->numberBetween(100, 50000), // 1.00 – 500.00
            'currency' => 'EUR',
            'status' => $this->faker->randomElement(['paid', 'failed', 'pending']),
            'provider' => 'fake',
            'provider_reference' => (string) str()->uuid(),
            'meta' => [
                'ip' => $this->faker->ipv4(),
                'user_agent' => $this->faker->userAgent(),
            ],
        ];
    }

    public function paid(): self
    {
        return $this->state(fn () => ['status' => 'paid']);
    }

    public function failed(): self
    {
        return $this->state(fn () => ['status' => 'failed']);
    }

    public function pending(): self
    {
        return $this->state(fn () => ['status' => 'pending']);
    }
}
