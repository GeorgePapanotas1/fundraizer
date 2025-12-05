export interface User {
    id: string;
    name: string;
    email: string;
    roles?: string[];
    created_at?: string;
    updated_at?: string;
}

export interface UserQuery {
    search?: string | '';
    page?: number;
    per_page?: number;
}
