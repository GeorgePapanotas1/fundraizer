<template>
  <div class="mt-4 flex items-center justify-between text-sm">
    <div class="text-[color:var(--fg-muted)]">{{ summaryText }}</div>
    <div class="flex items-center gap-2">
      <UiButton
        variant="subtle"
        :disabled="!meta || meta.current_page <= 1"
        @click="goTo(meta!.current_page - 1)"
      >
        Previous
      </UiButton>

      <UiButton
        v-if="showFirst"
        variant="subtle"
        :disabled="meta?.current_page === 1"
        @click="goTo(1)"
      >1</UiButton>
      <span v-if="showLeftEllipsis" class="text-[color:var(--fg-muted)]">…</span>

      <UiButton
        v-for="p in middlePages"
        :key="p"
        :variant="p === meta?.current_page ? 'soft' : 'subtle'"
        :disabled="p === meta?.current_page"
        @click="goTo(p)"
      >{{ p }}</UiButton>

      <span v-if="showRightEllipsis" class="text-[color:var(--fg-muted)]">…</span>
      <UiButton
        v-if="showLast"
        variant="subtle"
        :disabled="meta?.current_page === meta?.last_page"
        @click="goTo(meta!.last_page)"
      >{{ meta?.last_page }}</UiButton>

      <UiButton
        variant="subtle"
        :disabled="!meta || meta.current_page >= meta.last_page"
        @click="goTo(meta!.current_page + 1)"
      >
        Next
      </UiButton>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import UiButton from '@/components/ui/UiButton.vue';
import type { PaginationMeta } from '@/types/http';

const props = withDefaults(defineProps<{ meta?: PaginationMeta | null; summary?: string }>(), {
  meta: null,
  summary: undefined,
});

const emit = defineEmits<{ (e: 'change', page: number): void }>();

const summaryText = computed(() => {
  if (props.summary) return props.summary;
  const m = props.meta;
  if (!m) return '';
  const from = m.from ?? 0;
  const to = m.to ?? 0;
  return `Showing ${from}–${to} of ${m.total}`;
});

function goTo(page: number) {
  if (!props.meta) return;
  const p = Math.max(1, Math.min(props.meta.last_page, page));
  if (p !== props.meta.current_page) emit('change', p);
}

// Build a compact pager: current ±1, plus first/last with ellipsis
const middlePages = computed(() => {
  const m = props.meta;
  if (!m) return [] as number[];
  const start = Math.max(1, m.current_page - 1);
  const end = Math.min(m.last_page, m.current_page + 1);
  const pages: number[] = [];
  for (let i = start; i <= end; i++) pages.push(i);
  return pages;
});
const showFirst = computed(() => !!props.meta && (props.meta.current_page - 1) > 1);
const showLast = computed(() => !!props.meta && (props.meta.last_page - props.meta.current_page) > 1);
const showLeftEllipsis = computed(() => !!props.meta && (props.meta.current_page - 2) > 1);
const showRightEllipsis = computed(() => !!props.meta && (props.meta.last_page - (props.meta.current_page + 1)) > 1);
</script>
