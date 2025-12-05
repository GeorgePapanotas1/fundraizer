<?php

namespace Database\Seeders;

use Fundraiser\Campaign\Adapters\Models\Campaign;
use Fundraiser\Campaign\Adapters\Models\CampaignCategory;
use Illuminate\Database\Seeder;
use Str;

class CampaignDemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Seed 15 sensible categories (idempotent)
        $categories = [
            'Environment',
            'Education',
            'Health & Wellbeing',
            'Community Development',
            'Disaster Relief',
            'Clean Water & Sanitation',
            'Food Security',
            'Animal Welfare',
            'Arts & Culture',
            'Diversity & Inclusion',
            'STEM & Innovation',
            'Mental Health',
            'Youth Programs',
            'Elder Support',
            'Sports & Recreation',
        ];

        $categoryModels = [];
        foreach ($categories as $name) {
            $categoryModels[] = CampaignCategory::query()->firstOrCreate(
                ['name' => $name],
                [
                    'description' => $name.' initiatives and campaigns',
                    'slug' => Str::slug($name),
                    'is_active' => true,
                ]
            );
        }

        // 2) Seed 20 campaigns linked to random categories
        //    Use the factory for realistic data and ensure association
        if (count($categoryModels) === 0) {
            $categoryModels[] = CampaignCategory::factory()->create();
        }

        // Create 20 campaigns with a mix of statuses
        for ($i = 0; $i < 20; $i++) {
            $category = $categoryModels[array_rand($categoryModels)];
            Campaign::factory()
                ->withCategory($category)
                ->create();
        }
    }
}
