import {apiClient} from '@/composables/useApiClient';
import type {ApiResponse, Paginated} from '@/types/http';
import type {
    CampaignCategory,
    CampaignCategoryQuery,
    CreateCampaignCategoryDto,
    UpdateCampaignCategoryDto,
} from '@/types/categories';

const {get, post, patch} = apiClient;

function unwrap<T>(payload: any): T {
    if (payload && typeof payload === 'object' && 'data' in payload) {
        return (payload as ApiResponse<T>).data as T;
    }
    return payload as T;
}

export const CampaignCategoryService = {
    async list(params: CampaignCategoryQuery = {}): Promise<Paginated<CampaignCategory>> {
        return await get<Paginated<CampaignCategory>>('/v1/campaign-categories', {params});
    },

    async get(id: number | string): Promise<CampaignCategory> {
        const res = await get<CampaignCategory | ApiResponse<CampaignCategory>>(`/v1/campaign-categories/${id}`);
        return unwrap<CampaignCategory>(res);
    },

    async create(dto: CreateCampaignCategoryDto): Promise<CampaignCategory> {
        const res = await post<CampaignCategory | ApiResponse<CampaignCategory>>('/v1/campaign-categories', dto);
        return unwrap<CampaignCategory>(res);
    },

    async update(id: number | string, dto: UpdateCampaignCategoryDto): Promise<CampaignCategory> {
        const res = await patch<CampaignCategory | ApiResponse<CampaignCategory>>(`/v1/campaign-categories/${id}`, dto);
        return unwrap<CampaignCategory>(res);
    },
};

export default CampaignCategoryService;
