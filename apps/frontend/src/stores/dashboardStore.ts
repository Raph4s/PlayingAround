import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export interface DashboardItem {
    id: number | string
    title: string
    slug: string
    description?: string
    topic: string // e.g. 'Pariwisata', 'Kesehatan', 'Ekonomi'
    isPublic: boolean
    status: 'Terbit' | 'Draf' | 'Terjadwal'
    supersetUrl?: string
}

export interface TopicMeta {
    id: string
    name: string
    description: string
    iconName?: string
}

// Master metadata deskripsi topik resmi sesuai desain
export const TOPIC_METADATA: Record<string, string> = {
    Pariwisata: 'Data dan informasi sektor pariwisata',
    Kesehatan: 'Data layanan dan indikator kesehatan',
    Pendidikan: 'Data akses dan capaian pendidikan',
    Transportasi: 'Data mobilitas dan transportasi publik',
    Kependudukan: 'Data demografi dan kependudukan',
    Ekonomi: 'Data pertumbuhan dan aktivitas ekonomi',
    Lingkungan: 'Data kualitas dan kondisi lingkungan',
    Keamanan: 'Data keamanan dan ketertiban publik',
    Infrastruktur: 'Data pembangunan dan infrastruktur',
}

export const useDashboardStore = defineStore('dashboard', () => {
    // Simulasi data dashboard yang tersimpan di database (hasil input admin)
    const rawDashboards = ref<DashboardItem[]>([
        // Pariwisata
        { id: 1, title: 'Statistik Kunjungan Wisata Yogyakarta', slug: 'pariwisata-kunjungan', topic: 'Pariwisata', isPublic: true, status: 'Terbit' },
        { id: 2, title: 'Okupansi Hotel & Penginapan', slug: 'okupansi-hotel', topic: 'Pariwisata', isPublic: true, status: 'Terbit' },
        { id: 3, title: 'Destinasi Favorit Wisatawan', slug: 'destinasi-favorit', topic: 'Pariwisata', isPublic: true, status: 'Terbit' },
        { id: 4, title: 'Laporan Pendapatan Retribusi Wisata', slug: 'retribusi-wisata', topic: 'Pariwisata', isPublic: false, status: 'Terbit' },

        // Kesehatan
        { id: 5, title: 'Layanan Kesehatan Masyarakat', slug: 'layanan-kesehatan', topic: 'Kesehatan', isPublic: true, status: 'Terbit' },
        { id: 6, title: 'Ketersediaan Bed Rumah Sakit', slug: 'bed-rs', topic: 'Kesehatan', isPublic: true, status: 'Terbit' },
        { id: 7, title: 'Cakupan Vaksinasi & Imunisasi', slug: 'vaksinasi-daerah', topic: 'Kesehatan', isPublic: false, status: 'Terbit' },

        // Pendidikan
        { id: 8, title: 'Capaian Pendidikan Daerah', slug: 'pendidikan-daerah', topic: 'Pendidikan', isPublic: true, status: 'Terbit' },
        { id: 9, title: 'Sebaran Fasilitas Sekolah', slug: 'fasilitas-sekolah', topic: 'Pendidikan', isPublic: false, status: 'Terbit' },

        // Transportasi
        { id: 10, title: 'Mobilitas dan Transportasi', slug: 'mobilitas-transportasi', topic: 'Transportasi', isPublic: true, status: 'Terbit' },
        { id: 11, title: 'Volume Kendaraan Harian', slug: 'volume-kendaraan', topic: 'Transportasi', isPublic: false, status: 'Terbit' },

        // Kependudukan
        { id: 12, title: 'Demografi & Persebaran Penduduk', slug: 'demografi-penduduk', topic: 'Kependudukan', isPublic: true, status: 'Terbit' },

        // Ekonomi
        { id: 13, title: 'Indikator Ekonomi Kota', slug: 'ekonomi-indikator', topic: 'Ekonomi', isPublic: true, status: 'Terbit' },
        { id: 14, title: 'Pertumbuhan UMKM & Investasi', slug: 'umkm-investasi', topic: 'Ekonomi', isPublic: false, status: 'Terbit' },

        // Lingkungan
        { id: 15, title: 'Kualitas Udara & Ruang Terbuka Hijau', slug: 'lingkungan-kualitas', topic: 'Lingkungan', isPublic: true, status: 'Terbit' },

        // Keamanan
        { id: 16, title: 'Keamanan & Ketertiban Publik', slug: 'keamanan-publik', topic: 'Keamanan', isPublic: true, status: 'Terbit' },

        // Infrastruktur
        { id: 17, title: 'Pembangunan & Infrastruktur Wilayah', slug: 'infrastruktur-pembangunan', topic: 'Infrastruktur', isPublic: true, status: 'Terbit' },
    ])

    // Hitung kartu topik secara dinamis berdasarkan data yang masuk
    const aggregatedTopicCards = computed(() => {
        // Ambil daftar unik topik dari metadata atau dashboard yang ada
        const allTopicNames = Object.keys(TOPIC_METADATA)

        return allTopicNames.map((topicName) => {
            const dashboardsInTopic = rawDashboards.value.filter(
                (d) => d.topic.toLowerCase() === topicName.toLowerCase()
            )

            const publicCount = dashboardsInTopic.filter((d) => d.isPublic).length
            const privateCount = dashboardsInTopic.filter((d) => !d.isPublic).length

            return {
                id: topicName.toLowerCase(),
                title: topicName,
                description: TOPIC_METADATA[topicName] || 'Data dan informasi terpadu',
                total: dashboardsInTopic.length,
                publicCount,
                privateCount,
                highlighted: topicName === 'Pariwisata', // Sesuai outline biru di Figma
            }
        })
    })

    // Action untuk menambah data (dipakai saat form Tambah Dashboard disubmit)
    function addDashboard(item: DashboardItem) {
        rawDashboards.value.push(item)
    }

    return {
        rawDashboards,
        aggregatedTopicCards,
        addDashboard,
    }
})