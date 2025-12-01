<template>
  <label class="flex items-center gap-3 select-none cursor-pointer">
    <span v-if="label" class="label m-0">{{ label }}</span>
    <button
      type="button"
      role="switch"
      :aria-checked="modelValue ? 'true' : 'false'"
      @click="toggle"
      :class="[
        'relative inline-flex h-6 w-11 items-center rounded-full transition-colors duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-[color:var(--ring)]',
        modelValue ? 'bg-[color:var(--color-primary-500)]' : 'bg-[color:var(--bg-subtle)]',
      ]"
    >
      <span
        :class="[
          'inline-block h-5 w-5 transform rounded-full bg-white shadow transition',
          modelValue ? 'translate-x-5' : 'translate-x-1',
        ]"
      />
    </button>
  </label>
</template>

<script setup lang="ts">
const props = withDefaults(defineProps<{ modelValue: boolean; label?: string }>(), { label: undefined });
const emit = defineEmits<{ (e: 'update:modelValue', v: boolean): void }>();
const toggle = () => emit('update:modelValue', !props.modelValue);
</script>
