<template>
  <div class="md:w-3/4 space-y-4">
    <div class="flex items-center justify-between">
      <h2 class="h4">Pending (12)</h2>
      <SortTabs />
    </div>

    <div class="grid sm:grid-cols-2 gap-4">
      <CampaignCard
        title="Eco Tree Planting"
        meta="Environment • Submitted by Jane Doe"
        :badge="'Goal $10,000'"
        excerpt="Company-wide volunteering and donation drive to plant 5,000 trees."
        cover="primary"
      />

      <CampaignCard
        title="STEM Kits for Schools"
        meta="Education • Submitted by Alex Smith"
        :badge="'Goal $5,000'"
        excerpt="Providing hands-on STEM kits to underserved classrooms."
        cover="secondary"
      />

      <CampaignCard
        title="Community Food Bank"
        meta="Community • Submitted by Maria"
        :badge="'Goal $20,000'"
        excerpt="Support weekly food distributions for families in need."
        cover="accent"
      />

      <CampaignCard
        title="Clean Water Initiative"
        meta="Health • Submitted by Lee"
        :badge="'Goal $15,000'"
        excerpt="Install filtration systems in rural communities."
        cover="mix"
      />
    </div>

    <PaginationControls summary="Showing 1–8 of 12" />
  </div>
</template>

<script setup lang="ts">
import SortTabs from '@/components/campaign/SortTabs.vue';
import CampaignCard from '@/components/campaign/CampaignCard.vue';
import PaginationControls from '@/components/campaign/PaginationControls.vue';
</script>
