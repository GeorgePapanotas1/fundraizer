<template>
    <div class="w-full h-2 rounded-full bg-[color:var(--bg-subtle)] overflow-hidden">
        <div
            class="h-full bg-[color:var(--color-primary-500)]"
            :style="{ width: clamped + '%' }"
        />
    </div>
</template>

<script setup lang="ts">
import {computed} from 'vue';

const props = withDefaults(defineProps<{ percent?: number }>(), {percent: 0});
const clamped = computed(() => Math.max(0, Math.min(100, props.percent)));
</script>
