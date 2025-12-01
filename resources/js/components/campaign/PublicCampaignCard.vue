<template>
  <UiCard :hover="true" class="overflow-hidden p-0 flex flex-col">
    <!-- Media: prefer real image; fallback to themed cover pattern -->
    <div class="aspect-[16/9] w-full overflow-hidden bg-[color:var(--bg-subtle)]">
      <img
        v-if="image"
        :src="image"
        :alt="title"
        class="w-full h-full object-cover"
        loading="lazy"
      />
      <div v-else :class="coverClass" class="w-full h-full"></div>
    </div>
    <div class="card-section flex-1 flex flex-col gap-3">
      <h3 class="h3">{{ title }}</h3>
      <p class="text-[color:var(--fg-muted)]">{{ excerpt }}</p>
      <div class="mt-auto flex items-center justify-between">
        <span class="text-sm text-[color:var(--fg-muted)]">Goal: {{ goal }}</span>
        <div class="flex items-center gap-2">
          <RouterLink :to="to" class="btn-soft">View</RouterLink>
          <RouterLink v-if="canEdit && editTo" :to="editTo" class="btn-subtle">Edit</RouterLink>
        </div>
      </div>
    </div>
  </UiCard>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { RouterLink } from 'vue-router';
import UiCard from '@/components/ui/UiCard.vue';

const props = withDefaults(defineProps<{
  title: string;
  excerpt: string;
  goal: string;
  to: string;
  image?: string | null;
  cover?: 'primary' | 'secondary' | 'accent' | 'grid-primary' | 'grid-secondary';
  canEdit?: boolean;
  editTo?: string;
}>(), {
  cover: 'primary',
  canEdit: false,
});

const coverClass = computed(() => {
  switch (props.cover) {
    case 'secondary':
      return 'bg-[color:var(--color-secondary-50)] pattern-dots';
    case 'accent':
      return 'bg-[color:var(--color-accent-50)] pattern-dots';
    case 'grid-primary':
      return 'bg-[color:var(--color-primary-50)] pattern-grid';
    case 'grid-secondary':
      return 'bg-[color:var(--color-secondary-50)] pattern-grid';
    default:
      return 'bg-[color:var(--color-primary-50)] pattern-dots';
  }
});
</script>
