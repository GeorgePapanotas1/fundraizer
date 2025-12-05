<template>
    <AppLayout>
        <section class="min-h-[calc(100vh-8rem)] section-padding">
            <div class="container-page grid lg:grid-cols-2 gap-10 items-center">
                <!-- Brand / Hero Side -->
                <div class="hidden lg:block">
                    <div class="hero-surface rounded-2xl p-10 shadow-xl">
                        <div
                            class="h-48 rounded-xl bg-gradient-to-br from-[color:var(--color-primary-100)] to-[color:var(--color-accent-100)] pattern-grid"></div>
                        <div class="mt-6 space-y-2">
                            <h1 class="h2">Welcome back</h1>
                            <p class="text-[color:var(--fg-muted)] max-w-prose">Sign in to discover and support
                                campaigns led by colleagues across our communities.</p>
                        </div>
                        <div class="mt-8 grid grid-cols-3 gap-4 text-sm text-[color:var(--fg-muted)]">
                            <div class="card p-4 text-center">Trusted • CSR</div>
                            <div class="card p-4 text-center">Secure • SSO</div>
                            <div class="card p-4 text-center">Inclusive • Impact</div>
                        </div>
                    </div>
                </div>

                <!-- Form Side -->
                <div>
                    <UiCard class="card-section space-y-6 max-w-md mx-auto">
                        <div class="space-y-1 text-center">
                            <h2 class="h3">Sign in</h2>
                            <p class="text-[color:var(--fg-muted)]">Use your company email account</p>
                        </div>

                        <!-- SSO placeholders -->
                        <div class="grid grid-cols-2 gap-2">
                            <UiButton variant="soft" :full-width="true">SSO</UiButton>
                            <UiButton variant="soft" :full-width="true">Sign in with Microsoft</UiButton>
                        </div>

                        <div class="relative">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="w-full border-t border-[color:var(--border)]"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="bg-[color:var(--bg-surface)] px-3 text-[color:var(--fg-muted)]">or continue with email</span>
                            </div>
                        </div>

                        <form @submit.prevent="onSubmit" class="space-y-4">
                            <UiInput id="email" label="Email" type="email" v-model="form.email"
                                     placeholder="you@company.com"/>
                            <UiInput id="password" label="Password" type="password" v-model="form.password"
                                     placeholder="••••••••"/>

                            <div class="flex items-center justify-between">
                                <label class="flex items-center gap-2 text-sm">
                                    <input type="checkbox" v-model="form.remember"
                                           class="h-4 w-4 rounded border-[color:var(--border)]"/>
                                    <span>Remember me</span>
                                </label>
                                <a href="#" class="text-sm text-[color:var(--color-primary-600)] hover:underline">Forgot
                                    password?</a>
                            </div>

                            <UiButton type="submit" variant="primary" :full-width="true" :disabled="auth.loading">
                                <span v-if="auth.loading">Signing in…</span>
                                <span v-else>Sign in</span>
                            </UiButton>
                        </form>

                        <p v-if="auth.error" class="text-sm text-red-600 text-center">{{ auth.error }}</p>

                        <p class="text-xs text-[color:var(--fg-muted)] text-center">By signing in, you agree to our Code
                            of Conduct and Terms.</p>
                    </UiCard>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<script setup lang="ts">
import {computed, reactive} from 'vue';
import {useRouter} from 'vue-router';
import AppLayout from '@/components/layout/AppLayout.vue';
import UiCard from '@/components/ui/UiCard.vue';
import UiInput from '@/components/ui/UiInput.vue';
import UiButton from '@/components/ui/UiButton.vue';
import {useAuthStore} from '@/stores/auth';

const router = useRouter();
const redirectTo = computed(() => (router.currentRoute.value.query.redirect as string) || '/campaigns');
const auth = useAuthStore();

const form = reactive({email: '', password: '', remember: false});

async function onSubmit() {
    const ok = await auth.login(form.email, form.password);
    if (ok) router.push(redirectTo.value);
}
</script>
