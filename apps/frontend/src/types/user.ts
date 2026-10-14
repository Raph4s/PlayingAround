export type UserStatus = 'Aktif' | 'Menunggu' | 'Nonaktif'

export interface AdminUserItem {
    id: number
    initials: string
    name: string
    email: string
    role: string
    unitKerja: string
    status: UserStatus
    joinedDate: string
}

export interface InviteUserPayload {
    name: string
    email: string
    role: string
    unitKerja: string
    invitationMessage?: string
    autoActivate: boolean
}

export interface EditUserPayload {
    name: string
    email: string
    role: string
    unitKerja: string
    isActive: boolean
}