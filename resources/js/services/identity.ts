import { apiClient } from '@/composables/useApiClient';
import type { ApiResponse } from '@/types/http';
import type { MeResponse } from '@/types/identity';

const { get } = apiClient;

function unwrap<T>(payload: any): T {
  if (payload && typeof payload === 'object' && 'data' in payload) {
    return (payload as ApiResponse<T>).data as T;
  }
  return payload as T;
}

export const IdentityService = {
  async me(): Promise<MeResponse> {
    const res = await get<MeResponse | ApiResponse<MeResponse>>('/v1/me');
    return unwrap<MeResponse>(res);
  },
};

export default IdentityService;
