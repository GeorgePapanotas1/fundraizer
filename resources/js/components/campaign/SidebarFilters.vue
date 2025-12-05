<template>
  <aside class="md:w-1/4 card p-4 space-y-4">
    <UiInput id="q" label="Search" placeholder="Find campaigns..." />
    <UiSelect id="status" label="Status">
      <option>Pending approval</option>
      <option>Rejected</option>
      <option>Approved</option>
    </UiSelect>
    <UiSelect id="category" label="Category">
      <option>All</option>
      <option>Environment</option>
      <option>Education</option>
      <option>Community</option>
    </UiSelect>
    <p class="help-text">Reads must be read-only in Core. Writes (approve/reject) go through ModelCrudService.</p>
  </aside>
</template>

<script setup lang="ts">
import UiInput from '@/components/ui/UiInput.vue';
import UiSelect from '@/components/ui/UiSelect.vue';
</script>
