import axios from 'axios';

const apiClient = axios.create({
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
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
