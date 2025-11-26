<?php

namespace Database\Seeders;

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
            IdentityUsersSeeder::class,
        ]);
    }
}
