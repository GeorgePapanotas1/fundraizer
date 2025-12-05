<!--
Page: Campaign Edit (Vue SFC)
Layout: AppLayout
Composition:
- Left: CampaignForm (mode=edit) with prefilled values
- Right: Meta sidebar (ID, timestamps, owner) like Blade
-->

<template>
    <AppLayout>
        <section class="section-padding">
            <div class="container-page">
                <div v-if="error" class="mb-6 card card-section text-sm text-red-600">{{ error }}</div>
                <div v-else-if="loading" class="text-sm text-[color:var(--fg-muted)]">Loading…</div>
                <div v-else class="grid lg:grid-cols-[1fr,320px] gap-8">
                    <!-- Form -->
                    <div>
                        <div class="mb-4 flex items-start justify-between">
                            <div>
                                <h1 class="h2">Edit campaign</h1>
                                <p class="text-[color:var(--fg-muted)]">Update details and save your changes.</p>
                            </div>
                        </div>

                        <CampaignForm
                            mode="edit"
                            :initial-values="prefill"
                            :categories="categoryOptions"
                            :statuses="effectiveStatusOptions"
                            :submitting="submitting"
                            @submit="handleSubmit"
                            @cancel="handleCancel"
                        />
                    </div>

                    <!-- Sidebar -->
                    <aside class="space-y-4">
                        <UiCard class="card-section space-y-2 text-sm">
                            <div class="flex items-center justify-between"><span class="text-[color:var(--fg-muted)]">Campaign ID</span><span>{{
                                    campaign?.id || '—'
                                }}</span></div>
                            <div class="flex items-center justify-between"><span class="text-[color:var(--fg-muted)]">Created</span><span>{{
                                    formatDateTime(campaign?.created_at)
                                }}</span></div>
                            <div class="flex items-center justify-between"><span class="text-[color:var(--fg-muted)]">Updated</span><span>{{
                                    formatDateTime(campaign?.updated_at)
                                }}</span></div>
                            <div class="flex items-center justify-between"><span class="text-[color:var(--fg-muted)]">Status</span><span>{{
                                    campaign?.status_text || (campaign?.status || '')
                                }}</span></div>
                        </UiCard>

                    </aside>
                </div>
            </div>
        </section>
    </AppLayout>


</template>

<script setup lang="ts">
import {computed, onMounted, ref} from 'vue';
import {useRoute, useRouter} from 'vue-router';
import AppLayout from '@/components/layout/AppLayout.vue';
import UiCard from '@/components/ui/UiCard.vue';
import CampaignForm, {type CampaignFormValues} from '@/components/campaign/CampaignForm.vue';
import {CampaignsService} from '@/services/campaigns';
import {CampaignCategoryService} from '@/services/campaignCategories';
import type {Campaign} from '@/types/campaigns';
import type {CampaignCategory} from '@/types/categories';

const route = useRoute();
const router = useRouter();

const loading = ref(true);
const submitting = ref(false);
const error = ref<string | null>(null);
const campaign = ref<Campaign | null>(null);

const categoryOptions = ref<{ id: string | number; name: string }[]>([]);
const statusOptions = ref<{ value: string; label: string }[]>([]);

// Ensure the current campaign status is selectable even if the API only returns allowed transitions
const effectiveStatusOptions = computed(() => {
    const opts = [...statusOptions.value];
    const current = campaign.value?.status;
    if (current && !opts.some(o => o.value === current)) {
        opts.unshift({value: current, label: labelizeStatus(current)});
    }
    return opts;
});

function labelizeStatus(v: string): string {
    return v.replaceAll('_', ' ').replace(/\b\w/g, (m) => m.toUpperCase());
}

// Normalize various backend date formats to the native <input type="date"> value (YYYY-MM-DD)
function toDateInput(val: string | null | undefined): string | null {
    if (!val) return null;
    // If already in YYYY-MM-DD, return as-is
    if (/^\d{4}-\d{2}-\d{2}$/.test(val)) return val;
    // If ISO with time, take the date part
    const tIdx = val.indexOf('T');
    if (tIdx > 0) {
        const d = val.slice(0, tIdx);
        if (/^\d{4}-\d{2}-\d{2}$/.test(d)) return d;
    }
    // Fallback: parse and build local date components
    const dt = new Date(val);
    if (isNaN(dt.getTime())) return null;
    const y = dt.getFullYear();
    const m = String(dt.getMonth() + 1).padStart(2, '0');
    const d = String(dt.getDate()).padStart(2, '0');
    return `${y}-${m}-${d}`;
}

function formatDateTime(iso?: string | null) {
    if (!iso) return '—';
    try {
        const d = new Date(iso);
        if (isNaN(d.getTime())) return iso;
        return d.toLocaleString();
    } catch {
        return iso;
    }
}

async function loadCampaign() {
    const slug = route.params.slug as string;
    const res = await CampaignsService.get(slug);
    campaign.value = res;
}

async function loadCategories() {
    const res = await CampaignCategoryService.list({pagination: {perPage: 100} as any} as any);
    categoryOptions.value = (res.data?.data || []).map((c: CampaignCategory) => ({id: c.id, name: c.name}));
}

async function loadStatuses() {
    const slug = route.params.slug as string;
    const res = await CampaignsService.statusesForCampaign(slug);
    statusOptions.value = (res.statuses || []).map((s: string) => ({value: s, label: labelizeStatus(s)}));
}

const prefill = computed<Partial<CampaignFormValues>>(() => {
    const c = campaign.value;
    if (!c) return {};
    const catId = (c as any).campaign_category_id ?? c.category_id ?? '';
    return {
        title: c.title,
        shortDescription: c.short_description || '',
        category: catId ? String(catId) : '',
        goal: c.goal_amount ?? '',
        startDate: toDateInput((c as any).starts_at ?? (c as any).start_date ?? null) as any,
        endDate: toDateInput((c as any).ends_at ?? (c as any).end_date ?? null) as any,
        status: (c.status as any) || '',
        coverImage: (c as any).image || '',
        longDescription: (c.description ?? c.long_description ?? '') as any,
        // Presentation-only fields are not part of backend yet
        featured: false,
        matching: false,
        location: c.location || '',
        tags: c.tags || '',
    };
});

async function handleSubmit(values: CampaignFormValues) {
    submitting.value = true;
    error.value = null;
    try {
        const slug = route.params.slug as string;
        const payload = {
            title: values.title,
            short_description: values.shortDescription || null,
            description: values.longDescription || null,
            image: values.coverImage || null,
            goal_amount: typeof values.goal === 'string' ? (values.goal === '' ? null : Number(values.goal)) : values.goal,
            currency: 'EUR',
            campaign_category_id: values.category || null,
            status: values.status || undefined,
            starts_at: values.startDate || null,
            ends_at: values.endDate || null,
        } as const;

        const updated = await CampaignsService.update(slug, payload);
        await router.push(`/campaigns/${updated.slug}`);
    } catch (e: any) {
        error.value = e?.response?.data?.message || 'Unable to save changes';
    } finally {
        submitting.value = false;
    }
}

function handleCancel() {
    const slug = route.params.slug as string;
    router.push(`/campaigns/${slug}`);
}

onMounted(async () => {
    try {
        await Promise.all([loadCampaign(), loadCategories(), loadStatuses()]);
    } catch (e: any) {
        error.value = e?.response?.data?.message || 'Unable to load campaign';
    } finally {
        loading.value = false;
    }
});
</script>
