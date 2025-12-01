import {defineStore} from 'pinia';
import {computed, ref} from 'vue';
import {AuthService} from '@/services/auth';
import {apiClient} from '@/composables/useApiClient';
import type {OAuthTokenResponse} from '@/types/auth';

type StoredTokens = {
    access_token: string;
    refresh_token: string;
    expires_at: number;
};

const STORAGE_KEY = 'fr.auth.tokens';

export const useAuthStore = defineStore('auth', () => {
    const accessToken = ref<string | null>(null);
    const refreshToken = ref<string | null>(null);
    const expiresAt = ref<number | null>(null);
    const loading = ref(false);
    const error = ref<string | null>(null);
    let refreshTimer: number | null = null;

    const isAuthenticated = computed(() => !!accessToken.value && !!expiresAt.value && Date.now() < (expiresAt.value || 0));

    function persist(tokens: StoredTokens) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(tokens));
    }

    function restore() {
        const raw = localStorage.getItem(STORAGE_KEY);
        if (!raw) return;
        try {
            const data = JSON.parse(raw) as StoredTokens;
            accessToken.value = data.access_token;
            refreshToken.value = data.refresh_token;
            expiresAt.value = data.expires_at;
            apiClient.setAuthToken(accessToken.value);
            scheduleRefresh();
        } catch {
        }
    }

    function clear() {
        localStorage.removeItem(STORAGE_KEY);
        accessToken.value = null;
        refreshToken.value = null;
        expiresAt.value = null;
        apiClient.setAuthToken(null);
        if (refreshTimer) {
            window.clearTimeout(refreshTimer);
            refreshTimer = null;
        }
        window.location.reload();

    }

    function scheduleRefresh() {
        if (!expiresAt.value || !refreshToken.value) return;
        if (refreshTimer) {
            window.clearTimeout(refreshTimer);
            refreshTimer = null;
        }
        // refresh 30 seconds before expiry
        const msUntilRefresh = Math.max(1000, expiresAt.value - Date.now() - 30_000);
        refreshTimer = window.setTimeout(() => {
            void refresh().catch(() => {
            });
        }, msUntilRefresh);
    }

    function handleTokenResponse(res: OAuthTokenResponse) {
        accessToken.value = res.access_token;
        refreshToken.value = res.refresh_token;
        expiresAt.value = Date.now() + res.expires_in * 1000;
        apiClient.setAuthToken(accessToken.value);
        persist({access_token: res.access_token, refresh_token: res.refresh_token, expires_at: expiresAt.value});
        scheduleRefresh();
    }

    async function login(username: string, password: string) {
        loading.value = true;
        error.value = null;
        try {
            const res = await AuthService.loginWithPassword({username, password});
            handleTokenResponse(res);
            return true;
        } catch (e: any) {
            error.value = e?.response?.data?.message || 'Unable to sign in';
            clear();
            return false;
        } finally {
            loading.value = false;
        }
    }

    async function refresh() {
        if (!refreshToken.value) throw new Error('No refresh token');
        try {
            const res = await AuthService.refreshToken(refreshToken.value);
            handleTokenResponse(res);
            return true;
        } catch (e) {
            clear();
            return false;
        }
    }

    function logout() {
        clear();
    }

    restore();

    return {
        // state
        accessToken, refreshToken, expiresAt, loading, error,
        // getters
        isAuthenticated,
        // actions
        login, refresh, logout, restore,
    };
});

export default useAuthStore;
