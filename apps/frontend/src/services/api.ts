import axios, { type AxiosInstance, type AxiosResponse } from 'axios'

const apiClient: AxiosInstance = axios.create({
    baseURL: import.meta.env.VITE_API_BASE_URL || 'http://localhost:8000/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
    timeout: 10000,
})

// Request Interceptor: Attach Auth Token from localStorage if not already provided
apiClient.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('token')
        if (token && config.headers && !config.headers.Authorization) {
            config.headers.Authorization = `Bearer ${token}`
        }
        return config
    },
    (error) => Promise.reject(error)
)

// Response Interceptor: Catch 401 Unauthorized for protected pages
apiClient.interceptors.response.use(
    (response: AxiosResponse) => response,
    (error) => {
        if (error.response?.status === 401) {
            const url = error.config?.url || ''
            const isAuthRoute =
                url.includes('/auth/login') ||
                url.includes('/auth/request-reset') ||
                url.includes('/auth/validate-reset') ||
                url.includes('/auth/reset') ||
                url.includes('/auth/register/invitation')

            if (!isAuthRoute) {
                localStorage.removeItem('token')
                localStorage.removeItem('user')
                if (window.location.pathname.startsWith('/admin')) {
                    window.location.href = '/login'
                }
            }
        }
        return Promise.reject(error)
    }
)

export default apiClient
