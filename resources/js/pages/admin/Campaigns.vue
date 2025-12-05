<template>
    <AppLayout>
        <section class="section-padding">
            <div class="container-page flex flex-col gap-6">
                <div class="flex items-start justify-between">
                    <div class="space-y-1">
                        <h1 class="h2">Admin · Campaigns</h1>
                        <p class="text-[color:var(--fg-muted)]">Manage campaigns across the platform.</p>
                    </div>
                    <div class="hidden sm:flex items-center gap-3">
                        <RouterLink to="/campaigns/create" class="btn-primary">Create</RouterLink>
                    </div>
                </div>

                <div v-if="!auth.hasPermission('platform.access_admin')" class="card card-section text-sm">
                    You are not authorized to access the Admin area.
                </div>

                <div v-else class="card card-section">
                    <!-- Filters -->
                    <div class="grid gap-4 md:grid-cols-4">
                        <div class="md:col-span-2">
                            <UiInput id="search" label="Search" type="search"
                                     placeholder="Search by title or description" v-model="filters.search"/>
                        </div>
                        <div>
                            <UiSelect id="status" label="Status" v-model="filters.status">
                                <option :value="''">All</option>
                                <option v-for="s in statuses" :key="s" :value="s">{{ toLabel(s) }}</option>
                            </UiSelect>
                        </div>
                        <div class="flex items-end">
                            <UiButton variant="soft" :disabled="loading" @click="applyFilters">Apply</UiButton>
                            <UiButton class="ml-2" variant="subtle" :disabled="loading" @click="clearFilters">Clear
                            </UiButton>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="pb-12 sm:pb-16 lg:pb-24">
            <div class="container-page">
                <div v-if="error" class="card card-section text-sm text-red-600">{{ error }}</div>
                <div v-else-if="loading" class="text-sm text-[color:var(--fg-muted)]">Loading campaigns…</div>
                <div v-else class="card overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left text-[color:var(--fg-muted)]">
                        <tr>
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Owner</th>
                            <th class="px-4 py-3">Created</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="c in campaigns" :key="c.id" class="border-t border-[color:var(--border)]">
                            <td class="px-4 py-3">
                                <RouterLink class="font-medium hover:underline" :to="`/campaigns/${c.slug}`">{{
                                        c.title
                                    }}
                                </RouterLink>
                                <div v-if="c.short_description"
                                     class="text-[color:var(--fg-muted)] truncate max-w-[520px]">{{
                                        c.short_description
                                    }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="badge-info">{{ toLabel(c.status) }}</span>
                            </td>
                            <td class="px-4 py-3">{{
                                    c.created_by_user_id ? hashShort(c.created_by_user_id) : '—'
                                }}
                            </td>
                            <td class="px-4 py-3">{{ formatDate(c.created_at) }}</td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <RouterLink class="btn-subtle" :to="`/campaigns/${c.slug}/edit`">Edit</RouterLink>
                                <template v-if="canModerate && c.status === 'pending_approval'">
                                    <UiButton size="sm" variant="primary" :disabled="rowBusy === c.id"
                                              @click="onApprove(c)">
                                        <span v-if="rowBusy === c.id">Approving…</span>
                                        <span v-else>Approve</span>
                                    </UiButton>
                                    <UiButton size="sm" variant="danger" :disabled="rowBusy === c.id"
                                              @click="onReject(c)">
                                        <span v-if="rowBusy === c.id">Rejecting…</span>
                                        <span v-else>Reject</span>
                                    </UiButton>
                                </template>
                            </td>
                        </tr>
                        <tr v-if="!campaigns.length">
                            <td colspan="5" class="px-4 py-6 text-center text-[color:var(--fg-muted)]">No campaigns
                                found.
                            </td>
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
import {computed, onMounted, reactive, ref} from 'vue';
import {RouterLink} from 'vue-router';
import AppLayout from '@/components/layout/AppLayout.vue';
import UiInput from '@/components/ui/UiInput.vue';
import UiSelect from '@/components/ui/UiSelect.vue';
import UiButton from '@/components/ui/UiButton.vue';
import PaginationControls from '@/components/campaign/PaginationControls.vue';
import {CampaignsService} from '@/services/campaigns';
import type {Campaign, CampaignStatus} from '@/types/campaigns';
import type {PaginationMeta} from '@/types/http';
import {useAuthStore} from '@/stores/auth';

const auth = useAuthStore();

const loading = ref(false);
const error = ref<string | null>(null);
const statuses = ref<CampaignStatus[]>([] as CampaignStatus[]);
const campaigns = ref<Campaign[]>([]);
const meta = ref<PaginationMeta | null>(null);
const rowBusy = ref<number | string | null>(null);

const filters = reactive({
    search: '' as string | '',
    status: '' as CampaignStatus | '',
    page: 1,
    per_page: 12,
});

function toLabel(s: string | null | undefined) {
    return (s || '')
        .replace(/_/g, ' ')
        .replace(/\b\w/g, (m) => m.toUpperCase());
}

function hashShort(id: string) {
    // Show a short stable suffix of the ULID/UUID for compactness
    return id.length > 6 ? `…${id.slice(-6)}` : id;
}

function formatDate(iso?: string) {
    if (!iso) return '—';
    try {
        return new Date(iso).toLocaleDateString();
    } catch {
        return iso;
    }
}

async function loadStatuses() {
    try {
        const res = await CampaignsService.statuses();
        statuses.value = (res.statuses || []) as CampaignStatus[];
    } catch {
    }
}

async function load() {
    loading.value = true;
    error.value = null;
    try {
        const {data, meta: m} = await CampaignsService.list({
            search: filters.search,
            status: filters.status || undefined,
            pagination: {
                page: filters.page,
                perPage: filters.per_page,
            }
        });
        campaigns.value = data;
        meta.value = m;
    } catch (e: any) {
        error.value = e?.response?.data?.message || 'Failed to load campaigns';
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
    filters.status = '';
    filters.page = 1;
    void load();
}

function onPageChange(page: number) {
    filters.page = page;
    void load();
}

const canModerate = computed(() => auth.hasPermission('campaign.moderate'));

async function onApprove(c: Campaign) {
    if (rowBusy.value) return;
    rowBusy.value = c.id;
    try {
        await CampaignsService.approve(c.slug);
        await load();
    } catch (e: any) {
        error.value = e?.response?.data?.message || 'Failed to approve campaign';
    } finally {
        rowBusy.value = null;
    }
}

async function onReject(c: Campaign) {
    if (rowBusy.value) return;
    rowBusy.value = c.id;
    try {
        await CampaignsService.reject(c.slug);
        await load();
    } catch (e: any) {
        error.value = e?.response?.data?.message || 'Failed to reject campaign';
    } finally {
        rowBusy.value = null;
    }
}

onMounted(async () => {
    await Promise.all([loadStatuses(), load()]);
});
</script>

<style scoped>
/* Use project-wide badge styles; keep local minimal to avoid unresolved CSS vars */
.badge-info {
    display: inline-flex;
    align-items: center;
    padding: 0.125rem 0.5rem;
    border-radius: 9999px;
    font-weight: 500;
}
</style>
