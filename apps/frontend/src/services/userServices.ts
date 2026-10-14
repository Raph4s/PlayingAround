import apiClient from './api'
import type { AdminUserItem, InviteUserPayload } from '@/types/user'

export const userService = {
    async getUsers(): Promise<AdminUserItem[]> {
        // Mock data matching Figma "Kelola User Admin":
        return [
            {
                id: 1,
                initials: 'AP',
                name: 'Ayu Prameswari',
                email: 'ayu.prameswari@jogjakota.go.id',
                role: 'Super Admin',
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
                role: 'Editor',
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
                role: 'Viewer',
                unitKerja: 'Sekretariat Daerah',
                status: 'Nonaktif',
                joinedDate: '19 Agu 2026',
            },
        ]
    },

    async inviteUser(payload: InviteUserPayload): Promise<void> {
        // Backend endpoint: POST /api/app/user/invite
        await apiClient.post('/app/user/invite', {
            email: payload.email,
            role: payload.role === 'Superadmin' ? 1 : 2,
            inviter: 1,
        })
    },
}
