<template>
  <button
    :type="type"
    :disabled="disabled"
    :class="[
      baseClass,
      fullWidth ? 'w-full' : '',
      block ? 'inline-flex' : '',
      props.class,
    ]"
  >
    <slot />
  </button>

</template>

<script setup lang="ts">
import { computed } from 'vue';
type Variant = 'primary' | 'secondary' | 'accent' | 'soft' | 'subtle' | 'danger';

const props = withDefaults(defineProps<{
  variant?: Variant;
  type?: 'button' | 'submit' | 'reset';
  disabled?: boolean;
  fullWidth?: boolean;
  block?: boolean;
  class?: string;
}>(), {
  variant: 'primary',
  type: 'button',
  disabled: false,
  fullWidth: false,
  block: true,
  class: '',
});

const variants: Record<Variant, string> = {
  primary: 'btn-primary',
  secondary: 'btn-secondary',
  accent: 'btn-accent',
  soft: 'btn-soft',
  subtle: 'btn-subtle',
  danger: 'btn-danger',
};

const baseClass = computed(() => variants[props.variant]);
</script>
