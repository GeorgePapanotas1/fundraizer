import {apiClient} from '@/composables/useApiClient';
import type {ApiResponse, Paginated, PaginationMeta, PaginationLinks} from '@/types/http';
import type {Campaign, CampaignQuery, CreateCampaignDto, UpdateCampaignDto} from '@/types/campaigns';


const {get, post, patch, del} = apiClient;

function unwrap<T>(payload: any): T {
    if (payload && typeof payload === 'object' && 'data' in payload) {
        return (payload as ApiResponse<T>).data as T;
    }
    return payload as T;
}

// Normalize various Laravel paginator shapes to our Paginated<T> type
function normalizePaginated<T>(payload: any): Paginated<T> {
    // If already in desired shape (data: T[], meta: {...})
    if (payload && Array.isArray(payload.data) && payload.meta) {
        return payload as Paginated<T>;
    }

    // If payload is a Laravel LengthAwarePaginator JSON (current_page, data[], per_page, total, etc.)
    if (payload && typeof payload === 'object' && Array.isArray(payload.data) && 'current_page' in payload) {
        const meta: PaginationMeta = {
            current_page: Number(payload.current_page) || 1,
            from: payload.from ?? null,
            last_page: Number(payload.last_page) || 1,
            path: payload.path || '',
            per_page: Number(payload.per_page) || (payload.meta?.per_page ?? 15),
            to: payload.to ?? null,
            total: Number(payload.total) || (payload.meta?.total ?? payload.data.length),
        };

        // Prefer next/prev URLs when available
        const links: PaginationLinks = {
            first: payload.first_page_url ?? null,
            last: payload.last_page_url ?? null,
            prev: payload.prev_page_url ?? null,
            next: payload.next_page_url ?? null,
        };

        return {
            data: payload.data as T[],
            meta,
            links,
        };
    }

    // Fallback: treat entire payload as data[] with a single-page meta
    const dataArr = Array.isArray(payload) ? payload : (Array.isArray(payload?.data) ? payload.data : []);
    const meta: PaginationMeta = {
        current_page: 1,
        from: dataArr.length ? 1 : 0,
        last_page: 1,
        path: '',
        per_page: dataArr.length,
        to: dataArr.length,
        total: dataArr.length,
    };
    return { data: dataArr as T[], meta };
}

export const CampaignsService = {
    async list(params: CampaignQuery = {}): Promise<Paginated<Campaign>> {
        // Ensure boolean flags are sent as 0/1 for backend expectations
        const serialized = {
            ...params,
            ...(typeof params.mine === 'boolean' ? { mine: params.mine ? 1 : 0 } : {}),
        } as any;
        const res = await get<any>('/v1/campaigns', {params: serialized});
        const unwrapped = unwrap<any>(res);
        return normalizePaginated<Campaign>(unwrapped);
    },

    async get(id: number | string): Promise<Campaign> {
        const res = await get<Campaign | ApiResponse<Campaign>>(`/v1/campaigns/${id}`);
        return unwrap<Campaign>(res);
    },

    async create(dto: CreateCampaignDto): Promise<Campaign> {
        const res = await post<Campaign | ApiResponse<Campaign>>('/v1/campaigns', dto);
        return unwrap<Campaign>(res);
    },

    async update(id: number | string, dto: UpdateCampaignDto): Promise<Campaign> {
        const res = await patch<Campaign | ApiResponse<Campaign>>(`/v1/campaigns/${id}`, dto);
        return unwrap<Campaign>(res);
    },

    async remove(id: number | string): Promise<{ success: true } | void> {
        const res = await del<{ success: true } | ApiResponse<{ success: true }>>(`/v1/campaigns/${id}`);
        return (res ? unwrap(res) : undefined) as any;
    },

    async listActive(params: CampaignQuery = {}): Promise<Paginated<Campaign>> {
        const serialized = {
            ...params,
            ...(typeof params.mine === 'boolean' ? { mine: params.mine ? 1 : 0 } : {}),
        } as any;
        const res = await get<any>('/v1/campaigns/active', {params: serialized});
        const unwrapped = unwrap<any>(res);
        return normalizePaginated<Campaign>(unwrapped);
    },

    async statuses(): Promise<{ statuses: string[] }> {
        const res = await get<{ statuses: string[] } | ApiResponse<{ statuses: string[] }>>('/v1/campaigns/statuses');
        return unwrap(res);
    },

    async statusesForCampaign(id: number | string): Promise<{ campaign_id: number; statuses: string[] }> {
        const res = await get<{ campaign_id: number; statuses: string[] } | ApiResponse<{
            campaign_id: number;
            statuses: string[]
        }>>(`/v1/campaigns/${id}/statuses`);
        return unwrap(res);
    },
};

export default CampaignsService;
