import {createRouter, createWebHistory} from 'vue-router';
import type { RouteRecordRaw } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const routes: RouteRecordRaw[] = [
    {
        path: '/',
        redirect: '/campaigns',
    },
    {
        path: '/login',
        name: 'auth.login',
        component: () => import('../pages/auth/Login.vue'),
        meta: { public: true },
    },
    {
        path: '/campaigns',
        name: 'campaigns.index',
        component: () => import('../pages/campaigns/Index.vue'),
    },
    {
        path: '/campaigns/create',
        name: 'campaigns.create',
        component: () => import('../pages/campaigns/Create.vue'),
    },
    {
        path: '/campaigns/:slug',
        name: 'campaigns.show',
        component: () => import('../pages/campaigns/Show.vue'),
    },
    {
        path: '/campaigns/:slug/edit',
        name: 'campaigns.edit',
        component: () => import('../pages/campaigns/Edit.vue'),
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior() {
        return {top: 0};
    },
});

// Global auth guard: protect all routes except those with meta.public
router.beforeEach((to) => {
    const auth = useAuthStore();

    // Public routes pass through
    if (to.meta && (to.meta as any).public) {
        // If already authenticated, avoid showing login; redirect to intended page
        if (to.name === 'auth.login' && auth.isAuthenticated) {
            const redirect = (to.query?.redirect as string) || '/campaigns';
            return redirect;
        }
        return true;
    }

    // For all other routes, require authentication
    if (!auth.isAuthenticated) {
        return {
            name: 'auth.login',
            query: { redirect: to.fullPath },
        };
    }

    return true;
});

export default router;
