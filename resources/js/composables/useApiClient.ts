import axios, { type AxiosInstance, type AxiosRequestConfig, type AxiosError } from 'axios';

// Simple, reusable Axios client composable for the SPA
// - Uses /api as base URL (Laravel default)
// - Sends X-Requested-With and CSRF token (when present)
// - Unwraps response.data by default
// - Logs errors in dev; rethrows for callers to handle (pages/services)

let singleton: AxiosInstance | null = null;
let bearerToken: string | null = null;

function createClient(): AxiosInstance {
  const meta = document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement | null;
  const csrfToken = meta?.content;

  const instance = axios.create({
    baseURL: '/api',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
      'Accept': 'application/json',
    },
    withCredentials: true,
  });

  // Attach Authorization header when token is set
  instance.interceptors.request.use((config) => {
    if (bearerToken) {
      config.headers = config.headers || {};
      (config.headers as any)['Authorization'] = `Bearer ${bearerToken}`;
    }
    return config;
  });

  // Response: return response.data; keep full error for catch blocks
  instance.interceptors.response.use(
    (response) => response.data,
    (error: AxiosError) => {
      if (import.meta.env?.DEV) {
        // eslint-disable-next-line no-console
        console.error('[API ERROR]', error.response?.status, error.response?.data || error.message);
      }
      return Promise.reject(error);
    },
  );

  return instance;
}

export function useApiClient(): {
  client: AxiosInstance;
  get<T = any>(url: string, config?: AxiosRequestConfig): Promise<T>;
  post<T = any>(url: string, data?: any, config?: AxiosRequestConfig): Promise<T>;
  patch<T = any>(url: string, data?: any, config?: AxiosRequestConfig): Promise<T>;
  del<T = any>(url: string, config?: AxiosRequestConfig): Promise<T>;
  setAuthToken(token?: string | null): void;
} {
  if (!singleton) singleton = createClient();
  const client = singleton;

  return {
    client,
    get: (url, config) => client.get(url, config),
    post: (url, data, config) => client.post(url, data, config),
    patch: (url, data, config) => client.patch(url, data, config),
    del: (url, config) => client.delete(url, config),
    setAuthToken: (token) => {
      bearerToken = token || null;
      if (bearerToken) {
        client.defaults.headers.common['Authorization'] = `Bearer ${bearerToken}`;
      } else {
        delete client.defaults.headers.common['Authorization'];
      }
    },
  };
}

// Optional: named export for convenience
export const apiClient = useApiClient();

export default useApiClient;
