<template>
    <AppLayout>
        <section class="section-padding">
            <div class="container-page flex flex-col gap-6">
                <div class="flex items-start justify-between">
                    <div class="space-y-1">
                        <h1 class="h2">Admin · Categories</h1>
                        <p class="text-[color:var(--fg-muted)]">Manage campaign categories.</p>
                    </div>
                </div>

                <div v-if="!auth.hasPermission('platform.access_admin')" class="card card-section text-sm">
                    You are not authorized to access the Admin area.
                </div>

                <div v-else class="card card-section space-y-6">
                    <!-- Filters -->
                    <div class="grid gap-4 md:grid-cols-4">
                        <div class="md:col-span-2">
                            <UiInput id="search" label="Search" type="search"
                                     placeholder="Search by name" v-model="filters.search"/>
                        </div>
                        <div class="flex items-end">
                            <UiButton variant="soft" :disabled="loading" @click="applyFilters">Apply</UiButton>
                            <UiButton class="ml-2" variant="subtle" :disabled="loading" @click="clearFilters">Clear
                            </UiButton>
                        </div>
                    </div>

                    <!-- Create / Edit form (admin) -->
                    <div class="grid md:grid-cols-5 gap-4 items-end">
                        <div class="md:col-span-2">
                            <UiInput id="cat_name" label="Name" v-model="form.name" placeholder="Environment"/>
                        </div>
                        <div class="md:col-span-2">
                            <UiInput id="cat_desc" label="Description (optional)" v-model="form.description"
                                     placeholder="Sustainability, climate, nature"/>
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="text-sm flex items-center gap-2">
                                <input type="checkbox" class="h-4 w-4 rounded border-[color:var(--border)]"
                                       v-model="form.is_active"/>
                                <span>Active</span>
                            </label>
                        </div>
                        <div class="flex items-center gap-2">
                            <UiButton :disabled="saving || !form.name.trim()" variant="primary" @click="onSave">
                                <span v-if="saving">Saving…</span>
                                <span v-else>{{ isEdit ? 'Save changes' : 'Create category' }}</span>
                            </UiButton>
                            <UiButton v-if="isEdit" variant="subtle" :disabled="saving" @click="resetForm">Cancel
                            </UiButton>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="pb-12 sm:pb-16 lg:pb-24">
            <div class="container-page">
                <div v-if="error" class="card card-section text-sm text-red-600">{{ error }}</div>
                <div v-else-if="loading" class="text-sm text-[color:var(--fg-muted)]">Loading categories…</div>
                <div v-else class="card overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left text-[color:var(--fg-muted)]">
                        <tr>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Active</th>
                            <th class="px-4 py-3">Updated</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="c in categories" :key="c.id" class="border-t border-[color:var(--border)]">
                            <td class="px-4 py-3">{{ c.name }}</td>
                            <td class="px-4 py-3">
                                <span class="badge-info">{{ c.is_active === false ? 'No' : 'Yes' }}</span>
                            </td>
                            <td class="px-4 py-3">{{ formatDate(c.updated_at) }}</td>
                            <td class="px-4 py-3 text-right">
                                <UiButton size="sm" variant="subtle" @click="startEdit(c)" :disabled="saving">Edit
                                </UiButton>
                            </td>
                        </tr>
                        <tr v-if="!categories.length">
                            <td colspan="4" class="px-4 py-6 text-center text-[color:var(--fg-muted)]">No categories
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
import AppLayout from '@/components/layout/AppLayout.vue';
import UiInput from '@/components/ui/UiInput.vue';
import UiButton from '@/components/ui/UiButton.vue';
import PaginationControls from '@/components/campaign/PaginationControls.vue';
import {CampaignCategoryService} from '@/services/campaignCategories';
import type {CampaignCategory} from '@/types/categories';
import type {PaginationMeta} from '@/types/http';
import {useAuthStore} from '@/stores/auth';

const auth = useAuthStore();

const loading = ref(false);
const error = ref<string | null>(null);
const categories = ref<CampaignCategory[]>([]);
const meta = ref<PaginationMeta | null>(null);
const saving = ref(false);

const filters = reactive({
    search: '' as string | '',
    page: 1,
    per_page: 12,
});

const form = reactive<{ id: string | number | null; name: string; description: string; is_active: boolean }>(
    {id: null, name: '', description: '', is_active: true},
);

const isEdit = computed(() => form.id !== null);

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
        const res = await CampaignCategoryService.list({
            search: filters.search || undefined as any,
            pagination: {
                page: filters.page,
                perPage: filters.per_page,
            }
        } as any);
        // API returns Response::success({ paginator })
        const payload: any = res as any;
        const pg = payload?.data;
        categories.value = (pg?.data || []) as CampaignCategory[];
        meta.value = pg?.meta || null;
    } catch (e: any) {
        error.value = e?.response?.data?.message || 'Failed to load categories';
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

function resetForm() {
    form.id = null;
    form.name = '';
    form.description = '';
    form.is_active = true;
}

function startEdit(c: CampaignCategory) {
    form.id = c.id;
    form.name = c.name || '';
    form.description = (c as any).description || '';
    form.is_active = (c as any).is_active !== false;
}

async function onSave() {
    if (!form.name.trim()) return;
    saving.value = true;
    error.value = null;
    try {
        if (isEdit.value && form.id != null) {
            await CampaignCategoryService.update(form.id, {
                name: form.name,
                description: form.description || undefined,
                is_active: form.is_active,
            });
        } else {
            await CampaignCategoryService.create({
                name: form.name,
                description: form.description || undefined,
                is_active: form.is_active,
            });
        }
        resetForm();
        await load();
    } catch (e: any) {
        error.value = e?.response?.data?.message || 'Failed to save category';
    } finally {
        saving.value = false;
    }
}

onMounted(async () => {
    await load();
});
</script>

<style scoped>
.badge-info {
    display: inline-flex;
    align-items: center;
    padding: 0.125rem 0.5rem;
    border-radius: 9999px;
    font-weight: 500;
}
</style>
