<template>
    <aside class="space-y-6">
        <UiCard class="card-section space-y-4">
            <h3 class="h4">Donate</h3>
            <div class="grid grid-cols-3 gap-2">
                <UiButton variant="soft" @click="preset(10)" :disabled="busy">$10</UiButton>
                <UiButton variant="soft" @click="preset(25)" :disabled="busy">$25</UiButton>
                <UiButton variant="soft" @click="preset(50)" :disabled="busy">$50</UiButton>
                <UiButton variant="soft" @click="preset(100)" :disabled="busy">$100</UiButton>
                <UiButton variant="soft" @click="preset(250)" :disabled="busy">$250</UiButton>
                <UiButton variant="soft" @click="preset(500)" :disabled="busy">$500</UiButton>
            </div>
            <UiInput id="amount" type="number" label="Custom amount" placeholder="25" v-model.number="amount"/>
            <UiButton variant="accent" :full-width="true" :disabled="!canDonate || busy" @click="donate">
                <span v-if="busy">Processing…</span>
                <span v-else>Donate to cause</span>
            </UiButton>
            <p v-if="error" class="text-sm text-red-600">{{ error }}</p>
            <p v-if="reference" class="text-sm text-green-700">Thank you! Confirmation: {{ reference }}</p>
            <p class="help-text">Payments are processed securely. Your employer may match donations when enabled.</p>
        </UiCard>

        <UiCard class="card-section space-y-3 text-sm">
            <div class="flex items-center justify-between">
                <span class="text-[color:var(--fg-muted)]">Goal</span><span>{{ goalText }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-[color:var(--fg-muted)]">Raised</span><span>{{ raisedText }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-[color:var(--fg-muted)]">Donors</span><span>{{ donorsText }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-[color:var(--fg-muted)]">Days left</span><span>32</span>
            </div>
        </UiCard>

        <UiCard class="card-section space-y-3">
            <h3 class="h4">Organizer</h3>
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-[color:var(--color-primary-100)]"></div>
                <div>
                    <div class="font-medium">{{ organizerName }}</div>
                    <div class="text-sm text-[color:var(--fg-muted)]">ID {{ ownerShort }}</div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <UiButton variant="subtle" :full-width="true">Share</UiButton>
                <UiButton variant="subtle" :full-width="true">Contact</UiButton>
            </div>
        </UiCard>
    </aside>
</template>

<script setup lang="ts">
import {computed, ref} from 'vue';
import UiCard from '@/components/ui/UiCard.vue';
import UiButton from '@/components/ui/UiButton.vue';
import UiInput from '@/components/ui/UiInput.vue';
import type {Campaign} from '@/types/campaigns';
import {useAuthStore} from '@/stores/auth';
import {DonationsService} from '@/services/donations';

const props = defineProps<{ campaign?: Campaign | null }>();
const emit = defineEmits<{ (e: 'donated'): void }>();

const organizerName = computed(() => props.campaign?.organizer_name || '—');
const ownerShort = computed(() => {
    const id = props.campaign?.created_by_user_id || '';
    return id ? (id.length > 6 ? `…${id.slice(-6)}` : id) : '—';
});

function formatCurrency(n: number | null | undefined): string {
    if (n == null) return '—';
    try {
        return new Intl.NumberFormat(undefined, {
            style: 'currency',
            currency: 'USD',
            maximumFractionDigits: 0,
        }).format(n);
    } catch {
        return `$${n}`;
    }
}

const goalText = computed(() => formatCurrency(props.campaign?.goal_amount ?? null));
const raisedText = computed(() => formatCurrency(props.campaign?.raised_amount ?? null));
const donorsText = computed(() => (props.campaign?.donors_count ?? 0).toLocaleString());

const auth = useAuthStore();
const amount = ref<number | null>(25);
const busy = ref(false);
const error = ref<string | null>(null);
const reference = ref<string | null>(null);

const canDonate = computed(() => auth.isAuthenticated && (amount.value || 0) > 0);

function preset(v: number) {
    amount.value = v;
}

async function donate() {
    if (!props.campaign?.slug || !amount.value) return;
    error.value = null;
    reference.value = null;
    busy.value = true;
    try {
        const cents = Math.round((amount.value as number) * 100);
        const res = await DonationsService.create(props.campaign.slug, cents, 'EUR');
        reference.value = res.reference;
        // Notify parent so it can refresh campaign data (raised amount, donors count)
        emit('donated');
    } catch (e: any) {
        error.value = e?.response?.data?.message || 'Unable to complete donation';
    } finally {
        busy.value = false;
    }
}
</script>
