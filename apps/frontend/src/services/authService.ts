import apiClient from './api'

export interface LoginPayload {
    email: string
    password: string
}

export interface LoginResponse {
    token: string
    message?: string
}

export interface ResetRequestPayload {
    email: string
    id?: string
}

export interface ResetValidatePayload {
    id: string
    code: string
}

export interface ResetUserPayload {
    id: string
    password: string
}

export const authService = {
    async login(payload: LoginPayload): Promise<LoginResponse> {
        const response = await apiClient.post<LoginResponse>('/auth/login', payload)
        return response.data
    },

    async logout(): Promise<void> {
        await apiClient.post('/auth/logout')
        localStorage.removeItem('token')
        localStorage.removeItem('user')
    },

    async requestReset(payload: ResetRequestPayload) {
        const response = await apiClient.post('/auth/request-reset', payload)
        return response.data
    },

    async validateReset(payload: ResetValidatePayload) {
        const response = await apiClient.post('/auth/validate-reset', payload)
        return response.data
    },

    async resetPassword(payload: ResetUserPayload) {
        const response = await apiClient.post('/auth/reset', payload)
        return response.data
    },
}
