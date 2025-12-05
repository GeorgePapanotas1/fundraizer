<template>
    <AppLayout>
        <section class="section-padding">
            <div class="container-page flex flex-col gap-6">
                <div class="flex items-start justify-between">
                    <div class="space-y-1">
                        <h1 class="h2">Admin · Users</h1>
                        <p class="text-[color:var(--fg-muted)]">Manage platform users and roles.</p>
                    </div>
                </div>

                <div v-if="!auth.hasPermission('platform.access_admin')" class="card card-section text-sm">
                    You are not authorized to access the Admin area.
                </div>

                <div v-else class="card card-section">
                    <div class="grid gap-4 md:grid-cols-4">
                        <div class="md:col-span-2">
                            <UiInput id="search" label="Search" type="search"
                                     placeholder="Search by name or email" v-model="filters.search"/>
                        </div>
                        <div class="flex items-end">
                            <UiButton variant="soft" :disabled="loading" @click="applyFilters">Apply</UiButton>
                            <UiButton class="ml-2" variant="subtle" :disabled="loading" @click="clearFilters">Clear</UiButton>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="pb-12 sm:pb-16 lg:pb-24">
            <div class="container-page">
                <div v-if="error" class="card card-section text-sm text-red-600">{{ error }}</div>
                <div v-else-if="loading" class="text-sm text-[color:var(--fg-muted)]">Loading users…</div>
                <div v-else class="card overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left text-[color:var(--fg-muted)]">
                        <tr>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Roles</th>
                            <th class="px-4 py-3">Created</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="u in users" :key="u.id" class="border-t border-[color:var(--border)]">
                            <td class="px-4 py-3">{{ u.name }}</td>
                            <td class="px-4 py-3">{{ u.email }}</td>
                            <td class="px-4 py-3">{{ (u.roles || []).join(', ') || '—' }}</td>
                            <td class="px-4 py-3">{{ formatDate(u.created_at) }}</td>
                        </tr>
                        <tr v-if="!users.length">
                            <td colspan="4" class="px-4 py-6 text-center text-[color:var(--fg-muted)]">No users found.</td>
                        </tr>
                        </tbody>
                    </table>

                    <div class="p-4">
                        <PaginationControls :meta="meta" @change="onPageChange"/>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<script setup lang="ts">
import {onMounted, reactive, ref} from 'vue';
import AppLayout from '@/components/layout/AppLayout.vue';
import UiInput from '@/components/ui/UiInput.vue';
import UiButton from '@/components/ui/UiButton.vue';
import PaginationControls from '@/components/campaign/PaginationControls.vue';
import {useAuthStore} from '@/stores/auth';
import {UsersService} from '@/services/users';
import type {PaginationMeta} from '@/types/http';
import type {User} from '@/types/users';

const auth = useAuthStore();

const loading = ref(false);
const error = ref<string | null>(null);
const users = ref<User[]>([]);
const meta = ref<PaginationMeta | null>(null);

const filters = reactive({
    search: '' as string | '',
    page: 1,
    per_page: 12,
});

function formatDate(iso?: string) {
    if (!iso) return '—';
    try {
        return new Date(iso).toLocaleDateString();
    } catch {
        return iso;
    }
}

async function load() {
    loading.value = true;
    error.value = null;
    try {
        const res = await UsersService.list({
            search: filters.search || undefined as any,
            page: filters.page,
            per_page: filters.per_page,
        } as any);
        const payload: any = res as any;
        const pg = payload?.data;
        users.value = (pg?.data || []) as User[];
        meta.value = pg?.meta || null;
    } catch (e: any) {
        error.value = e?.response?.data?.message || 'Failed to load users';
    } finally {
        loading.value = false;
    }
}

function applyFilters() {
    filters.page = 1;
    void load();
}

function clearFilters() {
    filters.search = '';
    filters.page = 1;
    void load();
}

function onPageChange(page: number) {
    filters.page = page;
    void load();
}

onMounted(async () => {
    await load();
});
</script>

<style scoped>
</style>
