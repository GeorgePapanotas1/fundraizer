import type {RouteRecordRaw} from 'vue-router';
import {createRouter, createWebHistory} from 'vue-router';
import {useAuthStore} from '@/stores/auth';
import {CampaignsService} from '@/services/campaigns';

const routes: RouteRecordRaw[] = [
    {
        path: '/',
        redirect: '/campaigns',
    },
    {
        path: '/404',
        name: 'not-found',
        component: () => import('../pages/errors/NotFound.vue'),
        meta: {public: true},
    },
    {
        path: '/login',
        name: 'auth.login',
        component: () => import('../pages/auth/Login.vue'),
        meta: {public: true},
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
    {
        path: '/admin/campaigns',
        name: 'admin.campaigns',
        component: () => import('../pages/admin/Campaigns.vue'),
        meta: {requiresPermission: 'platform.access_admin'},
    },
    {
        path: '/admin/categories',
        name: 'admin.categories',
        component: () => import('../pages/admin/Categories.vue'),
        meta: {requiresPermission: 'platform.access_admin'},
    },
    {
        path: '/admin/users',
        name: 'admin.users',
        component: () => import('../pages/admin/Users.vue'),
        meta: {requiresPermission: 'platform.access_admin'},
    },
    {
        path: '/:pathMatch(.*)*',
        redirect: {name: 'not-found'},
        meta: {public: true},
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior() {
        return {top: 0};
    },
});

router.beforeEach(async (to) => {
    const auth = useAuthStore();

    if (to.meta && (to.meta as any).public) {
        if (to.name === 'auth.login' && auth.isAuthenticated) {
            return (to.query?.redirect as string) || '/campaigns';
        }
        return true;
    }

    if (!auth.isAuthenticated) {
        return {
            name: 'auth.login',
            query: {redirect: to.fullPath},
        };
    }

    const required = (to.meta as any)?.requiresPermission as string | undefined;

    if (required) {
        if (!auth.me) {
            await auth.fetchMe();
        }
        if (!auth.hasPermission(required)) {
            return {name: 'not-found'};
        }
    }

    // Reusable per-route ownership/permission rules
    // How to add another page with similar logic:
    //   1) Add a new rule entry below with the route name and permissions.
    //   2) Provide a loadResource() that fetches the resource using params.
    //   3) Provide isOwner() that determines ownership from the loaded resource.
    type AccessRule = {
        routeName: string;
        anyPermission?: string; // grants unconditional access when present on user
        ownPermission?: string; // required when checking ownership
        loadResource: (to: any) => Promise<any>;
        isOwner: (resource: any) => boolean;
    };

    const accessRules: AccessRule[] = [
        {
            routeName: 'campaigns.edit',
            anyPermission: 'campaign.update_any',
            ownPermission: 'campaign.update_own',
            loadResource: async (toArg) => {
                const slug = toArg.params.slug as string;
                return await CampaignsService.get(slug);
            },
            isOwner: (res) => !!res && (res as any).is_mine === true,
        },
    ];

    const rule = accessRules.find(r => r.routeName === to.name);
    if (rule) {
        if (!auth.me) {
            await auth.fetchMe();
        }

        if (rule.anyPermission && auth.hasPermission(rule.anyPermission)) {
            return true;
        }

        if (!rule.ownPermission || !auth.hasPermission(rule.ownPermission)) {
            return {name: 'not-found'};
        }

        try {
            const resource = await rule.loadResource(to);
            if (rule.isOwner(resource)) {
                return true;
            }
            return {name: 'not-found'};
        } catch {
            return {name: 'not-found'};
        }
    }

    return true;
});

export default router;
