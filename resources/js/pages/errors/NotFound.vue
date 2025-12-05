<template>
  <AppLayout>
    <section class="section-padding">
      <div class="container-page">
        <div class="card card-section text-center">
          <h1 class="h2 mb-2">404 — Page not found</h1>
          <p class="text-[color:var(--fg-muted)] mb-6">
            The page you’re looking for doesn’t exist or you don’t have permission to view it.
          </p>
          <RouterLink to="/" class="btn-primary">Go back home</RouterLink>
        </div>
      </div>
    </section>
  </AppLayout>
</template>

<script setup lang="ts">
import { RouterLink } from 'vue-router';
import AppLayout from '@/components/layout/AppLayout.vue';
</script>

<style scoped>
</style>
