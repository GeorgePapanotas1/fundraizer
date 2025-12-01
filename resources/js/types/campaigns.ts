export type CampaignStatus = 'draft' | 'pending_approval' | 'active' | 'closed' | 'cancelled';

export interface Campaign {
    id: number;
    title: string;
    slug: string;
    short_description: string | null;
    long_description: string | null; // legacy naming in types; backend may return `description`
    description?: string | null; // align with backend model field
    category_id: number | null;
    category_name?: string;
    created_by_user_id?: string | null; // ULID on backend
    goal_amount: number | null;
    cover_image_url: string | null; // legacy naming in types
    image?: string | null; // backend model field
    status: CampaignStatus; // machine enum value from backend
    status_text?: string; // human-friendly label (backend-provided)
    start_date: string | null; // legacy naming in types
    end_date: string | null; // legacy naming in types
    starts_at?: string | null; // backend model field
    ends_at?: string | null; // backend model field
    is_mine?: boolean; // backend accessor indicating ownership
    featured: boolean;
    matching: boolean;
    location: string | null;
    tags: string | null;
    created_at?: string;
    updated_at?: string;
}

export interface CampaignQuery {
    status?: CampaignStatus | '';
    campaign_category_id?: number | '';
    created_by_user_id?: number | '';
    search?: string | '';
    mine?: boolean;
    page?: number;
    per_page?: number;
}

export interface CreateCampaignDto {
    title: string;
    short_description?: string | null;
    description?: string | null; // backend field name
    campaign_category_id?: number | string | null;
    goal_amount?: number | null;
    image?: string | null; // backend field name
    currency?: string; // e.g., 'EUR'
    status?: CampaignStatus;
    starts_at?: string | null; // ISO date/datetime string
    ends_at?: string | null; // ISO date/datetime string
    // Optional presentation fields kept for forward-compat; ignored by backend
    featured?: boolean;
    matching?: boolean;
    location?: string | null;
    tags?: string | null;
}

export type UpdateCampaignDto = Partial<CreateCampaignDto>;
