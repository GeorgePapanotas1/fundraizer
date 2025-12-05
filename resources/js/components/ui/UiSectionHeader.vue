<template>
  <div class="space-y-1">
    <h2 :class="['h2', titleClass]">{{ title }}</h2>
    <p v-if="subtitle" :class="['text-[color:var(--fg-muted)]', subtitleClass]">{{ subtitle }}</p>
  </div>
</template>

<script setup lang="ts">
const props = withDefaults(defineProps<{ title: string; subtitle?: string; titleClass?: string; subtitleClass?: string }>(), {
  subtitle: undefined,
  titleClass: '',
  subtitleClass: '',
});
</script>
