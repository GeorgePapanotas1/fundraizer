<template>
  <div :class="wrapperClass">
    <label v-if="label" :for="id" class="label">{{ label }}</label>
    <textarea
      :id="id"
      v-bind="$attrs"
      :class="['textarea input', textareaClass]"
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
  help?: string;
  wrapperClass?: string;
  textareaClass?: string;
  modelValue?: string | null;
}>(), {
  id: undefined,
  label: undefined,
  help: undefined,
  wrapperClass: '',
  textareaClass: '',
  modelValue: '',
});

const emit = defineEmits<{ (e: 'update:modelValue', v: string | null): void }>();
function onInput(e: Event) {
  emit('update:modelValue', (e.target as HTMLTextAreaElement).value);
}
</script>
