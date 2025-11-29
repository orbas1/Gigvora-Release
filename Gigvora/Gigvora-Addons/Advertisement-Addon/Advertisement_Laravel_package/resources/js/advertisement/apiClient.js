import axios from 'axios';

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
const apiBase = document.querySelector('meta[name="gigvora-ads-api-base"]')?.getAttribute('content') || '/api/advertisement';

const apiClient = axios.create({
    baseURL: apiBase,
    withCredentials: true,
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
    },
});

apiClient.interceptors.response.use(
    (response) => response,
    (error) => {
        console.error('Ads API error', error);
        throw error;
    }
);

export const get = (url, params = {}) => apiClient.get(url, { params });
export const post = (url, data = {}) => apiClient.post(url, data);
export const put = (url, data = {}) => apiClient.put(url, data);
export const del = (url) => apiClient.delete(url);

export default apiClient;
