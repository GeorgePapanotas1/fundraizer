<template>
    <AppLayout>
        <section class="section-padding">


            <div class="container-page">
                <div v-if="error" class="mb-12 card card-section text-sm text-red-600">
                    {{ error }}
                </div>

                <div>
                    <div class="mb-4">
                        <h1 class="h2">Create campaign</h1>
                        <p class="text-[color:var(--fg-muted)]">Provide details about your initiative. You can save as
                            draft and publish later.</p>
                    </div>

                    <CampaignForm
                        mode="create"
                        :categories="categoryOptions"
                        :statuses="statusOptions"
                        :submitting="submitting"
                        @submit="handleSubmit"
                        @cancel="handleCancel"
                    />

                    <aside class="space-y-4 mt-12">
                        <UiCard class="card-section space-y-3">
                            <h3 class="h4">Guidelines</h3>
                            <ul class="list-disc pl-5 text-sm space-y-2 text-[color:var(--fg-muted)]">
                                <li>Be specific about impact and timeline.</li>
                                <li>Use a clear, high‑quality cover image.</li>
                                <li>Pick the most relevant category and tags.</li>
                                <li>Funding goals should be realistic and transparent.</li>
                            </ul>
                        </UiCard>
                    </aside>
                </div>

            </div>
        </section>
    </AppLayout>
</template>

<script setup lang="ts">
import {onMounted, ref} from 'vue';
import {useRouter} from 'vue-router';
import AppLayout from '@/components/layout/AppLayout.vue';
import UiCard from '@/components/ui/UiCard.vue';
import CampaignForm, {type CampaignFormValues} from '@/components/campaign/CampaignForm.vue';
import {CampaignsService} from '@/services/campaigns';
import {CampaignCategoryService} from '@/services/campaignCategories';
import type {Campaign} from '@/types/campaigns';
import type {CampaignCategory} from '@/types/categories';

const router = useRouter();
const submitting = ref(false);
const error = ref<string | null>(null);
const categoryOptions = ref<{ id: string | number; name: string }[]>([]);
const statusOptions = ref<{ value: string; label: string }[]>([]);

function labelizeStatus(v: string): string {
    return v.replaceAll('_', ' ').replace(/\b\w/g, (m) => m.toUpperCase());
}

async function loadCategories() {
    try {
        const res = await CampaignCategoryService.list({pagination: {perPage: 100} as any} as any);
        // API returns { data: { data: [...], meta: {...} } }
        categoryOptions.value = (res.data?.data || []).map((c: CampaignCategory) => ({id: c.id, name: c.name}));
    } catch (e: any) {
        if (import.meta.env?.DEV) console.warn('Failed to load categories', e?.response?.data || e);
    }
}

async function loadStatuses() {
    try {
        const res = await CampaignsService.statuses();
        statusOptions.value = (res.statuses || []).map((s: string) => ({value: s, label: labelizeStatus(s)}));
    } catch (e: any) {
    }
}

async function handleSubmit(values: CampaignFormValues) {
    submitting.value = true;
    error.value = null;
    try {
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

        const created: Campaign = await CampaignsService.create(payload);
        await router.push(`/campaigns/${created.slug}`);
    } catch (e: any) {
        error.value = e?.response?.data?.message || 'Unable to create campaign';
    } finally {
        submitting.value = false;
    }
}

function handleCancel() {
    router.push('/campaigns');
}

onMounted(async () => {
    await Promise.all([loadCategories(), loadStatuses()]);
});
</script>
