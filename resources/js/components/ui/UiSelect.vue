<template>
  <div :class="wrapperClass">
    <label v-if="label" :for="id" class="label">{{ label }}</label>
    <select :id="id" v-bind="$attrs" :class="['select', selectClass]" :value="modelValue as any" @change="onChange">
      <slot />
    </select>
    <p v-if="help" class="help-text mt-1">{{ help }}</p>
  </div>
</template>

<script setup lang="ts">
const props = withDefaults(defineProps<{
  id?: string;
  label?: string;
  help?: string;
  wrapperClass?: string;
  selectClass?: string;
  modelValue?: string | number | null;
}>(), {
  id: undefined,
  label: undefined,
  help: undefined,
  wrapperClass: '',
  selectClass: '',
  modelValue: '',
});

const emit = defineEmits<{ (e: 'update:modelValue', v: string | number | null): void }>();
function onChange(e: Event) {
  const target = e.target as HTMLSelectElement;
  emit('update:modelValue', target.value);
}
</script>
