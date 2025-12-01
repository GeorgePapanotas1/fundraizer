// Shared HTTP response types for the SPA

export type ApiResponse<T> = {
  data: T;
  message?: string;
};

export type PaginationMeta = {
  current_page: number;
  from: number | null;
  last_page: number;
  path: string;
  per_page: number;
  to: number | null;
  total: number;
};

export type PaginationLinks = {
  first?: string | null;
  last?: string | null;
  prev?: string | null;
  next?: string | null;
};

export type Paginated<T> = {
  data: T[];
  meta: PaginationMeta;
  links?: PaginationLinks;
};
