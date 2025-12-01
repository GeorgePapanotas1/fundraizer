<template>
  <div :class="wrapperClass">
    <label v-if="label" :for="id" class="label">{{ label }}</label>
    <input
      :id="id"
      v-bind="$attrs"
      :class="['input', inputClass]"
      :type="type"
      :value="modelValue as any"
      @input="onInput"
    />
    <p v-if="help" class="help-text mt-1">{{ help }}</p>
  </div>

</template>

<script setup lang="ts">
const props = withDefaults(defineProps<{
  id?: string;
  label?: string;
  type?: string;
  help?: string;
  wrapperClass?: string;
  inputClass?: string;
  modelValue?: string | number | null;
}>(), {
  id: undefined,
  label: undefined,
  type: 'text',
  help: undefined,
  wrapperClass: '',
  inputClass: '',
  modelValue: '',
});

const emit = defineEmits<{ (e: 'update:modelValue', v: string | number | null): void }>();
function onInput(e: Event) {
  const target = e.target as HTMLInputElement;
  emit('update:modelValue', target.type === 'number' ? (target.value === '' ? null : Number(target.value)) : target.value);
}
</script>
