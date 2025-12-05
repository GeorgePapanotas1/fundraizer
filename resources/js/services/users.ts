import {apiClient} from '@/composables/useApiClient';
import type {ApiResponse, Paginated} from '@/types/http';
import type {User, UserQuery} from '@/types/users';

const {get} = apiClient;

export const UsersService = {
    async list(params: UserQuery = {}): Promise<Paginated<User>> {
        // Backend returns Response::success($paginator) → { data: { data: [...], meta: {...} } }
        return await get<Paginated<User> | ApiResponse<Paginated<User>>>('/v1/users', {params});
    },
};

export default UsersService;
