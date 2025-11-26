<?php

namespace Database\Seeders;

use Fundraiser\Identity\Adapters\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed base roles & permissions for the Campaign context
        // Comment out if you prefer manual seeding.
        $this->call([
            IdentityCampaignPermissionSeeder::class,
        ]);

        // Example demo user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
