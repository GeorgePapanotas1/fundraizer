<template>
  <div class="text-center py-12 text-[color:var(--fg-muted)]">
    <div class="mx-auto h-12 w-12 rounded-xl bg-[color:var(--bg-subtle)] flex items-center justify-center mb-3">{{ icon }}</div>
    <h3 class="h4">{{ title }}</h3>
    <p class="mt-1">{{ message }}</p>
    <div class="mt-4"><slot name="action" /></div>
  </div>
</template>

<script setup lang="ts">
const props = withDefaults(defineProps<{ title: string; message: string; icon?: string }>(), {
  icon: '💡',
});
</script>
