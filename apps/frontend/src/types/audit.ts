export type AuditStatus = 'Berhasil' | 'Ditolak' | 'Gagal'

export interface AuditLogItem {
    id: string
    timestamp: string
    actor: string
    activity: string
    module: 'Superset' | 'Hak Akses' | 'Dashboard' | 'Preferensi' | 'User/Admin'
    ipAddress: string
    status: AuditStatus
}

export interface AuditLogDetail extends AuditLogItem {
    actorEmail: string
    device: string
    changesBefore: Record<string, any>
    changesAfter: Record<string, any>
}