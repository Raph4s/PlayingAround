import apiClient from './api'

export interface PreferenceItem {
    id: number
    key: string
    value: string
    created_at?: string
    updated_at?: string
}

export const preferenceService = {
    async getAll(): Promise<PreferenceItem[]> {
        const response = await apiClient.get<{ data: PreferenceItem[] }>('/app/setting/preference')
        return response.data?.data || []
    },

    async getById(id: number | string): Promise<PreferenceItem> {
        const response = await apiClient.get<{ data: PreferenceItem }>(`/app/setting/preference/${id}`)
        return response.data?.data
    },

    async create(payload: { key: string; value: string }): Promise<{ message: string }> {
        const response = await apiClient.post<{ message: string }>('/app/setting/preference', payload)
        return response.data
    },

    async update(payload: { id: string | number; key?: string; value?: string }): Promise<{ message: string }> {
        const response = await apiClient.put<{ message: string }>('/app/setting/preference', {
            ...payload,
            id: String(payload.id),
        })
        return response.data
    },

    async delete(id: string | number): Promise<{ message: string }> {
        const response = await apiClient.delete<{ message: string }>('/app/setting/preference', {
            data: { id: String(id) },
        })
        return response.data
    },

    /**
     * Upsert a key-value preference.
     * Looks for existing key in the current list, updates if found, otherwise creates.
     */
    async upsert(key: string, value: any, existingList?: PreferenceItem[]): Promise<void> {
        const list = existingList || (await this.getAll())
        const strValue = typeof value === 'string' ? value : JSON.stringify(value)
        const match = list.find((p) => p.key === key)

        if (match) {
            await this.update({ id: match.id, key, value: strValue })
        } else {
            await this.create({ key, value: strValue })
        }
    },
}
