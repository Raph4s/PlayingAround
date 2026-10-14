import apiClient from './api'
import type { AdminUserItem, InviteUserPayload } from '@/types/user'

export const userService = {
    async getUsers(): Promise<AdminUserItem[]> {
        // Mock data matching 3 roles: Superadmin, Admin, User
        return [
            {
                id: 1,
                initials: 'AP',
                name: 'Ayu Prameswari',
                email: 'ayu.prameswari@jogjakota.go.id',
                role: 'Superadmin',
                unitKerja: 'Dinas Kominfo',
                status: 'Aktif',
                joinedDate: '12 Sep 2026',
            },
            {
                id: 2,
                initials: 'BS',
                name: 'Bima Saputra',
                email: 'bima.saputra@jogjakota.go.id',
                role: 'Admin',
                unitKerja: 'Bappeda',
                status: 'Aktif',
                joinedDate: '08 Sep 2026',
            },
            {
                id: 3,
                initials: 'CN',
                name: 'Citra Ningrum',
                email: 'citra.ningrum@jogjakota.go.id',
                role: 'User',
                unitKerja: 'Dinas Kesehatan',
                status: 'Menunggu',
                joinedDate: '03 Sep 2026',
            },
            {
                id: 4,
                initials: 'DP',
                name: 'Dimas Prabowo',
                email: 'dimas.prabowo@jogjakota.go.id',
                role: 'Admin',
                unitKerja: 'Dinas Pariwisata',
                status: 'Aktif',
                joinedDate: '28 Agu 2026',
            },
            {
                id: 5,
                initials: 'EL',
                name: 'Eka Lestari',
                email: 'eka.lestari@jogjakota.go.id',
                role: 'User',
                unitKerja: 'Sekretariat Daerah',
                status: 'Nonaktif',
                joinedDate: '19 Agu 2026',
            },
        ]
    },

    async inviteUser(payload: InviteUserPayload): Promise<{ message?: string }> {
        let inviterId = 1
        try {
            const raw = localStorage.getItem('user')
            if (raw) {
                const parsed = JSON.parse(raw)
                if (parsed.id) inviterId = Number(parsed.id)
            }
        } catch {
            // fallback
        }

        const roleNormalized = (payload.role || '').toLowerCase()
        let roleId = 3 // 3: user
        if (roleNormalized.includes('super')) {
            roleId = 1 // 1: superadmin
        } else if (roleNormalized.includes('admin')) {
            roleId = 2 // 2: admin
        } else {
            roleId = 3 // 3: user
        }

        const response = await apiClient.post<{ message: string }>('/app/user/invite', {
            email: payload.email,
            role: roleId,
            inviter: inviterId,
        })
        return response.data
    },
}
