<template>
    <form @submit.prevent="onSubmit" class="space-y-6">
        <UiCard class="card-section space-y-4">
            <div class="grid md:grid-cols-2 gap-4">
                <UiInput id="title" label="Title" v-model="form.title" placeholder="Community Tree Planting"/>
                <UiInput id="cover_image" label="Cover image URL" v-model="form.coverImage"
                         placeholder="https://.../cover.jpg"/>
            </div>
            <UiTextarea id="short_desc" label="Short description" v-model="form.shortDescription"
                        placeholder="A one‑sentence summary shown in listings"/>
            <div class="grid md:grid-cols-3 gap-4">
                <UiSelect id="category" label="Category" v-model="form.category">
                    <option value="">Select a category</option>
                    <option v-for="c in categories" :key="c.id" :value="String(c.id)">{{ c.name }}</option>
                    <template v-if="!categories || categories.length === 0">
                        <option>Education</option>
                        <option>Environment</option>
                        <option>Health</option>
                        <option>Community</option>
                    </template>
                </UiSelect>
                <UiInput id="goal" type="number" label="Goal amount (USD)" v-model="form.goal" placeholder="50000"/>
                <UiSelect id="status" label="Status" v-model="form.status">
                    <option value="">Select status</option>
                    <option v-for="s in statuses" :key="s.value" :value="s.value">{{ s.label }}</option>
                    <!-- Fallbacks if statuses not provided -->
                    <template v-if="!statuses || statuses.length === 0">
                        <option value="draft">Draft</option>
                        <option value="pending_approval">Pending approval</option>
                    </template>
                </UiSelect>
            </div>
            <div class="grid md:grid-cols-2 gap-4">
                <UiInput id="start_date" type="date" label="Start date" v-model="form.startDate"/>
                <UiInput id="end_date" type="date" label="End date" v-model="form.endDate"/>
            </div>
            <UiTextarea id="long_desc" label="Long description" v-model="form.longDescription"
                        placeholder="Tell the full story, goals, and impact…" rows="6"/>
            <div class="grid md:grid-cols-2 gap-4">
                <UiInput id="location" label="Location" v-model="form.location" placeholder="Global / EMEA / Nairobi…"/>
                <UiInput id="tags" label="Tags (comma separated)" v-model="form.tags"
                         placeholder="trees,volunteering,environment"/>
            </div>
            <div class="grid md:grid-cols-2 gap-6 items-center">
                <ToggleSwitch v-model="form.featured" label="Feature on homepage"/>
                <ToggleSwitch v-model="form.matching" label="Enable employer matching"/>
            </div>
        </UiCard>

        <div class="flex items-center gap-3">
            <UiButton type="submit" variant="primary" :disabled="submitting">{{ submitLabel }}</UiButton>
            <UiButton type="button" variant="subtle" @click="onCancel" :disabled="submitting">Cancel</UiButton>
            <UiButton v-if="mode === 'edit'" type="button" variant="danger" :disabled="submitting">Archive campaign
            </UiButton>
        </div>
    </form>
</template>

<script setup lang="ts">
import {computed, reactive, watch} from 'vue';
import UiCard from '@/components/ui/UiCard.vue';
import UiInput from '@/components/ui/UiInput.vue';
import UiTextarea from '@/components/ui/UiTextarea.vue';
import UiSelect from '@/components/ui/UiSelect.vue';
import ToggleSwitch from '@/components/ui/ToggleSwitch.vue';
import UiButton from '@/components/ui/UiButton.vue';

type Mode = 'create' | 'edit';

type CategoryOption = { id: string | number; name: string };
type StatusOption = { value: string; label: string };

export interface CampaignFormValues {
    title: string;
    shortDescription: string;
    category: string;
    goal: string | number | null;
    startDate: string | null;
    endDate: string | null;
    status: string;
    coverImage: string;
    longDescription: string;
    featured: boolean;
    matching: boolean;
    location: string;
    tags: string;
}

const props = withDefaults(defineProps<{
    mode?: Mode;
    initialValues?: Partial<CampaignFormValues>;
    submitting?: boolean;
    categories?: CategoryOption[];
    statuses?: StatusOption[]
}>(), {
    mode: 'create',
    initialValues: () => ({}),
    submitting: false,
    categories: () => [],
    statuses: () => [],
});

const emit = defineEmits<{
    (e: 'submit', values: CampaignFormValues): void;
    (e: 'cancel'): void;
}>();

const defaults: CampaignFormValues = {
    title: '',
    shortDescription: '',
    category: '',
    goal: '',
    startDate: null,
    endDate: null,
    status: '',
    coverImage: '',
    longDescription: '',
    featured: false,
    matching: false,
    location: '',
    tags: '',
};

const form = reactive<CampaignFormValues>({...defaults, ...props.initialValues});

watch(() => props.initialValues, (v) => {
    Object.assign(form, {...defaults, ...(v || {})});
});

const submitLabel = computed(() => (props.mode === 'edit' ? 'Save changes' : 'Create campaign'));

function onSubmit() {
    emit('submit', {...form});
}

function onCancel() {
    emit('cancel');
}
</script>
