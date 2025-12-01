<template>
  <span :class="['badge', props.class]">
    <slot />
  </span>
</template>

<script setup lang="ts">
const props = withDefaults(defineProps<{ class?: string }>(), { class: '' });
</script>
