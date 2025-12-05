<template>
  <div class="min-h-screen bg-[color:var(--bg-app)] text-[color:var(--fg-app)]">
    <header class="border-b border-[color:var(--border)] bg-[color:var(--bg-surface)]">
      <div class="container-page h-16 flex items-center justify-between">
        <RouterLink to="/" class="text-lg font-semibold">Fundraiser</RouterLink>
        <nav class="hidden md:flex items-center gap-6 text-sm text-[color:var(--fg-muted)]">
          <RouterLink to="/campaigns" class="hover:text-[color:var(--fg-app)]">Campaigns</RouterLink>
          <a href="#about" class="hover:text-[color:var(--fg-app)]">About</a>
          <a href="#donations" class="hover:text-[color:var(--fg-app)]">My Donations</a>
        </nav>
        <div class="flex items-center gap-3">
          <template v-if="!auth.isAuthenticated">
            <RouterLink to="/login" class="btn-subtle">Sign in</RouterLink>
          </template>
          <template v-else>
            <RouterLink
              v-if="auth.hasPermission('platform.access_admin')"
              to="/admin/campaigns"
              class="btn-subtle"
            >Admin</RouterLink>
            <button class="btn-subtle" @click="auth.logout()">Sign out</button>
          </template>
          <RouterLink to="/campaigns/create" class="btn-primary">Start a Campaign</RouterLink>
        </div>
      </div>
    </header>

    <main>
      <slot />
    </main>

    <footer class="mt-20 border-t border-[color:var(--border)]">
      <div class="container-page py-10 text-sm text-[color:var(--fg-muted)] flex items-center justify-between">
        <div>© 2025 Fundraiser</div>
        <div class="flex items-center gap-4">
          <a href="#privacy" class="hover:text-[color:var(--fg-app)]">Privacy</a>
          <a href="#terms" class="hover:text-[color:var(--fg-app)]">Terms</a>
        </div>
      </div>
    </footer>
  </div>

</template>

<script setup lang="ts">
import { RouterLink } from 'vue-router';
import UiButton from '@/components/ui/UiButton.vue';
import { useAuthStore } from '@/stores/auth';

const auth = useAuthStore();
</script>
