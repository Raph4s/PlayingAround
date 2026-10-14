import apiClient from './api'

export interface LoginPayload {
    email: string
    password: string
}

export interface LoginResponse {
    token: string
    message?: string
    user?: {
        id: number
        email: string
        fullname?: string
        roles?: string[]
    }
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
    bearerToken?: string
}

export interface AcceptInvitationPayload {
    id: string
    password: string
    fullname?: string
}

export const authService = {
    async login(payload: LoginPayload): Promise<LoginResponse> {
        const response = await apiClient.post<LoginResponse>('/auth/login', payload)
        return response.data
    },

    async logout(): Promise<void> {
        try {
            await apiClient.post('/auth/logout')
        } catch {
            // Ignore if already expired
        } finally {
            localStorage.removeItem('token')
            localStorage.removeItem('user')
        }
    },

    async requestReset(payload: ResetRequestPayload): Promise<{ token?: string; message?: string }> {
        const response = await apiClient.post('/auth/request-reset', payload)
        return response.data
    },

    async validateReset(payload: ResetValidatePayload): Promise<{ token?: string; message?: string }> {
        const response = await apiClient.post('/auth/validate-reset', payload)
        return response.data
    },

    async resetPassword(payload: ResetUserPayload): Promise<{ message: string }> {
        const headers: Record<string, string> = {}
        if (payload.bearerToken) {
            headers['Authorization'] = `Bearer ${payload.bearerToken}`
        }
        const response = await apiClient.post<{ message: string }>(
            '/auth/reset',
            {
                id: payload.id,
                password: payload.password,
            },
            { headers }
        )
        return response.data
    },

    async acceptInvitation(payload: AcceptInvitationPayload): Promise<{ message: string }> {
        const response = await apiClient.post<{ message: string }>(
            '/auth/register/invitation',
            payload
        )
        return response.data
    },
}
