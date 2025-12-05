<template>
    <AppLayout>
        <section class="relative overflow-hidden">
            <div v-if="campaign?.image" class="h-44 md:h-56 bg-center bg-cover"
                 :style="{ backgroundImage: `url(${campaign.image})` }"></div>
            <div v-else
                 class="h-44 md:h-56 bg-gradient-to-br from-[color:var(--color-primary-100)] to-[color:var(--color-accent-100)]"></div>
        </section>

        <section class="section-padding">
            <div class="container-page">
                <div v-if="error" class="card card-section text-sm text-red-600">{{ error }}</div>
                <div v-else-if="loading" class="text-sm text-[color:var(--fg-muted)]">Loading campaign…</div>
                <div v-else-if="campaign" class="grid lg:grid-cols-[1fr,320px] gap-8">
                    <article class="space-y-6">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h1 class="h2">{{ campaign.title }}</h1>
                                <div class="mt-1 flex items-center gap-2 text-sm text-[color:var(--fg-muted)]">
                                    <UiBadge v-if="campaign.category_name">{{ campaign.category_name }}</UiBadge>
                                    <span v-if="campaign.category_name">•</span>
                                    <span>{{ campaign.short_description }}</span>
                                </div>
                            </div>
                            <div class="hidden md:flex items-center gap-2">
                                <UiButton variant="subtle">Share</UiButton>
                                <UiButton variant="subtle">Follow</UiButton>
                                <RouterLink
                                    v-if="campaign.is_mine"
                                    :to="`/campaigns/${campaign.slug}/edit`"
                                    class="btn-soft"
                                >Edit
                                </RouterLink>
                            </div>
                        </div>

                        <UiCard class="card-section space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-[color:var(--fg-muted)]">Goal
                                    {{ formatCurrency(campaign.goal_amount) }}
                                </div>
                                <div class="text-sm">
                                    {{ campaign.status_text || (campaign.status || '').replaceAll('_', ' ') }}
                                </div>
                            </div>
                            <ProgressBar :percent="0"/>
                            <div class="flex items-center gap-4 text-sm text-[color:var(--fg-muted)]">
                                <span>Donations coming soon</span>
                            </div>
                        </UiCard>

                        <UiCard class="card-section space-y-3">
                            <h3 class="h4">About this campaign</h3>
                            <p v-if="description" class="whitespace-pre-line">{{ description }}</p>
                            <p v-else class="text-[color:var(--fg-muted)]">No description available.</p>
                        </UiCard>

                        <div class="grid md:grid-cols-3 gap-4">
                            <UiCard class="p-4">
                                <div class="text-sm text-[color:var(--fg-muted)]">Category</div>
                                <div class="mt-1 font-medium">{{ campaign.category_name || '—' }}</div>
                            </UiCard>
                            <UiCard class="p-4">
                                <div class="text-sm text-[color:var(--fg-muted)]">Status</div>
                                <div class="mt-1 font-medium">
                                    {{ campaign.status_text || (campaign.status || '').replaceAll('_', ' ') }}
                                </div>
                            </UiCard>
                            <UiCard class="p-4">
                                <div class="text-sm text-[color:var(--fg-muted)]">Goal</div>
                                <div class="mt-1 font-medium">{{ formatCurrency(campaign.goal_amount) }}</div>
                            </UiCard>
                        </div>

                        <UiCard class="card-section space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="h4">Updates</h3>
                                <UiButton variant="subtle">View all</UiButton>
                            </div>
                            <div class="space-y-3 text-sm">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <div class="font-medium">Welcome</div>
                                        <div class="text-[color:var(--fg-muted)]">Stay tuned for updates</div>
                                    </div>
                                    <div class="text-[color:var(--fg-muted)]">—</div>
                                </div>
                            </div>
                        </UiCard>
                    </article>

                    <DonateSidebar :campaign="campaign" @donated="fetchCampaign"/>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<script setup lang="ts">
import {computed, onMounted, ref} from 'vue';
import {RouterLink, useRoute} from 'vue-router';
import AppLayout from '@/components/layout/AppLayout.vue';
import UiButton from '@/components/ui/UiButton.vue';
import UiCard from '@/components/ui/UiCard.vue';
import UiBadge from '@/components/ui/UiBadge.vue';
import ProgressBar from '@/components/campaign/ProgressBar.vue';
import DonateSidebar from '@/components/campaign/DonateSidebar.vue';
import {CampaignsService} from '@/services/campaigns';
import type {Campaign} from '@/types/campaigns';

const route = useRoute();
const loading = ref(false);
const error = ref<string | null>(null);
const campaign = ref<Campaign | null>(null);

const description = computed(() => campaign.value?.description || campaign.value?.long_description || '');

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

async function fetchCampaign() {
    loading.value = true;
    error.value = null;
    try {
        const slug = route.params.slug as string;
        const res = await CampaignsService.get(slug);
        campaign.value = res;
    } catch (e: any) {
        error.value = e?.response?.data?.message || 'Unable to load campaign';
    } finally {
        loading.value = false;
    }
}

onMounted(() => {
    void fetchCampaign();
});
</script>
