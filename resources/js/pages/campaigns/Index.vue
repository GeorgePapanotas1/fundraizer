<template>
    <AppLayout>
        <section class="section-padding">
            <div class="container-page flex flex-col gap-6">
                <div class="flex items-start justify-between">
                    <div class="space-y-1">
                        <h1 class="h2">Campaigns</h1>
                        <p class="text-[color:var(--fg-muted)]">Browse active initiatives and find a cause to
                            support.</p>
                    </div>
                    <div class="hidden sm:flex items-center gap-3">
                        <RouterLink to="/campaigns/create" class="btn-primary">Create</RouterLink>
                        <a href="#" class="btn-subtle">Guidelines</a>
                    </div>
                </div>

                <!-- Filters toolbar (wired) -->
                <div class="card card-section">
                    <div class="grid gap-4 md:grid-cols-4">
                        <div class="md:col-span-2">
                            <UiInput id="search" label="Search" type="search"
                                     placeholder="Search by title or description" v-model="filters.search"/>
                        </div>
                        <div>
                            <UiSelect id="category" label="Category" v-model="filters.campaign_category_id">
                                <option :value="''">All</option>
                                <option v-for="cat in categories" :key="cat?.id" :value="cat?.id">{{
                                        cat?.name
                                    }}
                                </option>
                            </UiSelect>
                        </div>
                        <div class="flex items-end">
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" v-model="filters.mine" class="h-4 w-4 rounded border-[color:var(--border)]" />
                                <span>My campaigns</span>
                            </label>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <div class="flex items-center gap-2 text-sm">
                            <span class="badge-info">Sort: Newest</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <UiButton variant="subtle" @click="clearFilters" :disabled="loading">Clear filters
                            </UiButton>
                            <UiButton variant="soft" @click="applyFilters" :disabled="loading">Apply</UiButton>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Listing grid -->
        <section class="pb-12 sm:pb-16 lg:pb-24">
            <div class="container-page">
                <div v-if="error" class="card card-section text-sm text-red-600">{{ error }}</div>
                <div v-else-if="loading" class="text-sm text-[color:var(--fg-muted)]">Loading campaigns…</div>
                <div v-else>
                    <div v-if="campaigns.length" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        <PublicCampaignCard
                            v-for="(c, idx) in campaigns"
                            :key="c.id"
                            :title="c.title"
                            :excerpt="c.short_description || ''"
                            :goal="formatCurrency(c.goal_amount)"
                            :raised="formatCurrency(c.raised_amount ?? null)"
                            :to="`/campaigns/${c.slug}`"
                            :image="c.image || null"
                            :cover="coverVariant(idx)"
                            :can-edit="!!c.is_mine"
                            :edit-to="`/campaigns/${c.slug}/edit`"
                        />
                    </div>
                    <EmptyState
                        v-else
                        title="No campaigns found"
                        message="Try adjusting your filters or search term."
                    >
                        <template #action>
                            <UiButton variant="subtle" @click="clearFilters">Clear filters</UiButton>
                        </template>
                    </EmptyState>

                    <div class="mt-8">
                        <PaginationControls :meta="meta" @change="onPageChange"/>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>

</template>

<script setup lang="ts">
import {onMounted, reactive, ref} from 'vue';
import {RouterLink} from 'vue-router';
import AppLayout from '@/components/layout/AppLayout.vue';
import UiButton from '@/components/ui/UiButton.vue';
import UiInput from '@/components/ui/UiInput.vue';
import UiSelect from '@/components/ui/UiSelect.vue';
import PublicCampaignCard from '@/components/campaign/PublicCampaignCard.vue';
import PaginationControls from '@/components/campaign/PaginationControls.vue';
import EmptyState from '@/components/common/EmptyState.vue';
import {CampaignsService} from '@/services/campaigns';
import {CampaignCategoryService} from '@/services/campaignCategories';
import type {Campaign} from '@/types/campaigns';
import type {CampaignCategory} from '@/types/categories';
import type {PaginationMeta} from '@/types/http';

const loading = ref(false);
const error = ref<string | null>(null);
const campaigns = ref<Campaign[]>([]);
const meta = ref<PaginationMeta | null>(null);
const categories = ref<CampaignCategory[]>([]);

const filters = reactive<{
    search: string | '';
    campaign_category_id: number | '';
    mine: boolean;
    page: number;
    per_page: number
}>(
    {search: '', campaign_category_id: '', mine: false, page: 1, per_page: 12},
);

function formatCurrency(n: number | null | undefined): string {
    if (n == null) return '—';
    try {
        return new Intl.NumberFormat(undefined, {
            style: 'currency',
            currency: 'USD',
            maximumFractionDigits: 0
        }).format(n);
    } catch {
        return `$${n.toLocaleString()}`;
    }
}

function coverVariant(i: number): 'primary' | 'secondary' | 'accent' | 'grid-primary' | 'grid-secondary' {
    const variants = ['primary', 'grid-secondary', 'accent', 'grid-primary', 'secondary'] as const;
    return variants[i % variants.length];
}

async function fetchCategories() {
    try {
        const res = await CampaignCategoryService.list({
            pagination: {
                perPage: 100
            }
        });
        categories.value = res.data.data;
    } catch (e: any) {
        // categories are optional for the list to work; keep silent but console in dev
        if (import.meta.env?.DEV) console.warn('Failed to load categories', e?.response?.data || e);
    }
}

async function fetchCampaigns() {
    loading.value = true;
    error.value = null;
    try {
        const {data, meta: m} = await CampaignsService.listActive({
            search: filters.search,
            campaign_category_id: filters.campaign_category_id,
            mine: filters.mine,
            pagination: {
                perPage: filters.per_page,
                page: filters.page,
            }
        });
        campaigns.value = data;
        meta.value = m;
    } catch (e: any) {
        error.value = e?.response?.data?.message || 'Unable to load campaigns';
    } finally {
        loading.value = false;
    }
}

function applyFilters() {
    filters.page = 1;
    void fetchCampaigns();
}

function clearFilters() {
    filters.search = '';
    filters.campaign_category_id = '';
    filters.mine = false;
    filters.page = 1;
    void fetchCampaigns();
}

function onPageChange(page: number) {
    filters.page = page;
    void fetchCampaigns();
}

onMounted(async () => {
    await Promise.all([fetchCategories(), fetchCampaigns()]);
});
</script>
