<template>
  <UiCard :hover="true" class="overflow-hidden p-0">
    <div :class="coverClass"></div>
    <div class="p-4 space-y-3">
      <div class="flex items-start justify-between gap-3">
        <div>
          <h3 class="font-medium leading-tight">{{ title }}</h3>
          <div class="text-sm text-[color:var(--fg-muted)]">{{ meta }}</div>
        </div>
        <UiBadge><slot name="badge">{{ badge }}</slot></UiBadge>
      </div>
      <p class="text-sm">{{ excerpt }}</p>
      <div class="flex items-center gap-2">
        <UiButton variant="primary">Approve</UiButton>
        <UiButton variant="danger">Reject</UiButton>
        <UiButton variant="subtle">View</UiButton>
      </div>
    </div>
  </UiCard>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import UiCard from '@/components/ui/UiCard.vue';
import UiBadge from '@/components/ui/UiBadge.vue';
import UiButton from '@/components/ui/UiButton.vue';

const props = withDefaults(defineProps<{
  title: string;
  meta: string;
  badge?: string;
  excerpt: string;
  cover?: 'primary' | 'secondary' | 'accent' | 'mix';
}>(), {
  badge: '',
  cover: 'primary',
});

const coverClass = computed(() => {
  switch (props.cover) {
    case 'secondary':
      return 'h-28 bg-gradient-to-br from-[color:var(--color-secondary-100)] to-[color:var(--color-secondary-200)]';
    case 'accent':
      return 'h-28 bg-gradient-to-br from-[color:var(--color-accent-100)] to-[color:var(--color-accent-200)]';
    case 'mix':
      return 'h-28 bg-gradient-to-br from-[color:var(--color-primary-100)] to-[color:var(--color-secondary-100)]';
    default:
      return 'h-28 bg-gradient-to-br from-[color:var(--color-primary-100)] to-[color:var(--color-accent-100)]';
  }
});
</script>
