// Campaign Categories types for the SPA client

export interface CampaignCategory {
  id: number | string;
  name: string;
  slug?: string;
  description?: string | null;
  is_active?: boolean;
  created_at?: string;
  updated_at?: string;
}

export interface CampaignCategoryQuery {
  search?: string | '';
  page?: number;
  per_page?: number;
}

export interface CreateCampaignCategoryDto {
  name: string;
  description?: string | null;
  is_active?: boolean;
}

export type UpdateCampaignCategoryDto = Partial<CreateCampaignCategoryDto>;
