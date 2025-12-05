import {apiClient} from '@/composables/useApiClient';
import type {ApiResponse} from '@/types/http';

const {post} = apiClient;

function unwrap<T>(payload: any): T {
    if (payload && typeof payload === 'object' && 'data' in payload) {
        return (payload as ApiResponse<T>).data as T;
    }
    return payload as T;
}

export type DonationResult = {
    reference: string;
    message?: string;
};

export const DonationsService = {
    async create(campaignSlug: string | number, amountCents: number, currency: string = 'EUR'): Promise<DonationResult> {
        const res = await post<DonationResult | ApiResponse<DonationResult>>(`/v1/campaigns/${campaignSlug}/donations`, {
            amount_cents: amountCents,
            currency,
        });
        return unwrap<DonationResult>(res);
    },
};

export default DonationsService;
