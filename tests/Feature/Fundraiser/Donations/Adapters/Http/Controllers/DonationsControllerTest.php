<?php

use Database\Factories\Fundraiser\Campaign\Adapters\Models\CampaignFactory;
use Fundraiser\Campaign\Core\Constants\Enums\CampaignStatus;

it('creates a donation for an active campaign and returns confirmation reference', function () {
    // Arrange: active campaign
    $campaign = CampaignFactory::new()->active()->create([
        'title' => 'Plant Trees',
    ]);

    // Act
    $res = $this->postJson("/api/v1/campaigns/{$campaign->slug}/donations", [
        'amount_cents' => 2500,
        'currency' => 'EUR',
    ]);

    // Assert
    $res->assertOk()
        ->assertJsonStructure([
            'data' => ['reference', 'message'],
        ]);

    expect(data_get($res->json(), 'data.reference'))
        ->toBeString()
        ->and(strlen(data_get($res->json(), 'data.reference')))->toBeGreaterThan(3);

    $this->assertDatabaseHas('donations', [
        'campaign_id' => $campaign->id,
        'status' => 'paid',
        'currency' => 'EUR',
        'amount_cents' => 2500,
    ]);
});

it('rejects donation for non-active campaign with 422 and does not persist', function () {
    // Arrange: draft campaign (not accepting donations)
    $campaign = CampaignFactory::new()->draft()->create([
        'title' => 'Draft Project',
        'status' => CampaignStatus::Draft->value,
    ]);

    // Act
    $res = $this->postJson("/api/v1/campaigns/{$campaign->slug}/donations", [
        'amount_cents' => 1500,
        'currency' => 'EUR',
    ]);

    // Assert
    $res->assertUnprocessable();

    $this->assertDatabaseMissing('donations', [
        'campaign_id' => $campaign->id,
        'amount_cents' => 1500,
    ]);
});
