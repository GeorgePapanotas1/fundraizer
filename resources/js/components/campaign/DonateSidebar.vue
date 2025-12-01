<template>
  <aside class="space-y-6">
    <UiCard class="card-section space-y-4">
      <h3 class="h4">Donate</h3>
      <div class="grid grid-cols-3 gap-2">
        <UiButton variant="soft">$10</UiButton>
        <UiButton variant="soft">$25</UiButton>
        <UiButton variant="soft">$50</UiButton>
        <UiButton variant="soft">$100</UiButton>
        <UiButton variant="soft">$250</UiButton>
        <UiButton variant="soft">$500</UiButton>
      </div>
      <UiInput id="amount" type="number" label="Custom amount" placeholder="25" />
      <UiButton variant="accent" :full-width="true">Donate to cause</UiButton>
      <p class="help-text">Payments are processed securely. Your employer may match donations when enabled.</p>
    </UiCard>

    <UiCard class="card-section space-y-3 text-sm">
      <div class="flex items-center justify-between">
        <span class="text-[color:var(--fg-muted)]">Goal</span><span>$50,000</span>
      </div>
      <div class="flex items-center justify-between">
        <span class="text-[color:var(--fg-muted)]">Raised</span><span>$28,450</span>
      </div>
      <div class="flex items-center justify-between">
        <span class="text-[color:var(--fg-muted)]">Donors</span><span>534</span>
      </div>
      <div class="flex items-center justify-between">
        <span class="text-[color:var(--fg-muted)]">Days left</span><span>32</span>
      </div>
    </UiCard>

    <UiCard class="card-section space-y-3">
      <h3 class="h4">Organizers</h3>
      <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-full bg-[color:var(--color-primary-100)]"></div>
        <div>
          <div class="font-medium">Jane Doe</div>
          <div class="text-sm text-[color:var(--fg-muted)]">Employee • Sustainability Guild</div>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <UiButton variant="subtle" :full-width="true">Share</UiButton>
        <UiButton variant="subtle" :full-width="true">Contact</UiButton>
      </div>
      <p class="help-text">This page is static markup. Actual writes go through ModelCrudService per project rules.</p>
    </UiCard>
  </aside>
</template>

<script setup lang="ts">
import UiCard from '@/components/ui/UiCard.vue';
import UiButton from '@/components/ui/UiButton.vue';
import UiInput from '@/components/ui/UiInput.vue';
</script>
