<template>
  <div :class="['card', padding ? padding : '', hover ? 'card-hover' : '', props.class]">
    <slot />
  </div>
</template>

<script setup lang="ts">
const props = withDefaults(defineProps<{ hover?: boolean; padding?: string; class?: string }>(), {
  hover: false,
  padding: 'p-4',
  class: '',
});
</script>
