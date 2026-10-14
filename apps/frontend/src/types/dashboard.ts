export type ConnectionStatus = 'Tersinkron' | 'Berjalan' | 'Perlu perhatian'
export type PublicationStatus = 'Terbit' | 'Terjadwal' | 'Draf'

export interface SupersetConnectionItem {
    id: number
    name: string
    url: string
    owner: string
    status: ConnectionStatus
    lastSync: string
}

export interface SupersetConnectionPayload {
    name: string
    url: string
    serviceAccount: string
    tokenApiKey: string
    database: string
    workspace: string
    autoSync: boolean
    frequency: string
}

export interface DashboardItem {
    id: number
    title: string
    slug: string
    category: string
    ownerInitials: string
    ownerName: string
    status: PublicationStatus
    updatedAt: string
}

export interface DashboardPayload {
    title: string
    slug: string
    description: string
    category: string
    supersetConnectionId: string
    publicationStatus: PublicationStatus
    isPublic: boolean
    thumbnailFile?: File | null
}