import apiClient from './api'

export interface SupersetItem {
    id: number
    host: string
    username: string
    password?: string
    created_at?: string
    updated_at?: string
}

export interface SupersetCreatePayload {
    host: string
    username: string
    password: string
}

export interface SupersetUpdatePayload {
    id: number | string
    host?: string
    username?: string
    password?: string
}

export const supersetService = {
    async getAll(): Promise<SupersetItem[]> {
        const response = await apiClient.get<{ data: SupersetItem[] }>('/app/setting/superset')
        return response.data?.data || []
    },

    async getById(id: number | string): Promise<SupersetItem> {
        const response = await apiClient.get<{ data: SupersetItem }>(`/app/setting/superset/${id}`)
        return response.data?.data
    },

    async create(payload: SupersetCreatePayload): Promise<{ message: string }> {
        const response = await apiClient.post<{ message: string }>('/app/setting/superset', payload)
        return response.data
    },

    async update(payload: SupersetUpdatePayload): Promise<{ message: string }> {
        const response = await apiClient.put<{ message: string }>('/app/setting/superset', {
            ...payload,
            id: String(payload.id),
        })
        return response.data
    },

    async delete(id: number | string): Promise<{ message: string }> {
        const response = await apiClient.delete<{ message: string }>('/app/setting/superset', {
            data: { id: String(id) },
        })
        return response.data
    },
}
