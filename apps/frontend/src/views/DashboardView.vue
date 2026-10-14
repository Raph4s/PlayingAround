<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import {
  ChevronDown,
  LayoutGrid,
  Check,
  FolderOpen,
} from 'lucide-vue-next'

const router = useRouter()

export interface DashboardItem {
  id: number | string
  title: string
  slug: string
  topic: string
  isPublic: boolean
  status: 'Terbit' | 'Draf' | 'Terjadwal'
}

const TOPIC_DESCRIPTIONS: Record<string, string> = {
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

// Mock dataset mirroring the Figma screens
const dashboards = ref<DashboardItem[]>([
  // Pariwisata
  { id: 1, title: 'Statistik Kunjungan Wisata Yogyakarta', slug: 'wisata-kunjungan', topic: 'Pariwisata', isPublic: true, status: 'Terbit' },
  { id: 2, title: 'Okupansi Hotel & Penginapan', slug: 'hotel-okupansi', topic: 'Pariwisata', isPublic: true, status: 'Terbit' },
  { id: 3, title: 'Sebaran Objek Wisata', slug: 'objek-wisata', topic: 'Pariwisata', isPublic: true, status: 'Terbit' },
  { id: 4, title: 'Profil Wisatawan Mancanegara', slug: 'profil-wisman', topic: 'Pariwisata', isPublic: true, status: 'Terbit' },
  { id: 5, title: 'Kalender Event Wisata', slug: 'kalender-event', topic: 'Pariwisata', isPublic: true, status: 'Terbit' },
  { id: 6, title: 'Pengeluaran Rata-rata Wisatawan', slug: 'pengeluaran-wisatawan', topic: 'Pariwisata', isPublic: true, status: 'Terbit' },
  { id: 7, title: 'Kunjungan Desa Wisata', slug: 'desa-wisata', topic: 'Pariwisata', isPublic: true, status: 'Terbit' },
  { id: 8, title: 'Kawasan Malioboro Terpadu', slug: 'malioboro-terpadu', topic: 'Pariwisata', isPublic: true, status: 'Terbit' },
  { id: 9, title: 'Pendapatan Retribusi Wisata', slug: 'retribusi-wisata', topic: 'Pariwisata', isPublic: false, status: 'Terbit' },
  { id: 10, title: 'Audit Pajak Hiburan Wisata', slug: 'audit-pajak-wisata', topic: 'Pariwisata', isPublic: false, status: 'Terbit' },
  { id: 11, title: 'Analisis Evaluasi Kerjasama Swasta', slug: 'evaluasi-swasta', topic: 'Pariwisata', isPublic: false, status: 'Terbit' },
  { id: 12, title: 'Laporan Internal Dinas Pariwisata', slug: 'internal-pariwisata', topic: 'Pariwisata', isPublic: false, status: 'Terbit' },

  // Kesehatan
  { id: 13, title: 'Layanan Kesehatan Masyarakat', slug: 'layanan-kesehatan', topic: 'Kesehatan', isPublic: true, status: 'Terbit' },
  { id: 14, title: 'Ketersediaan Bed Rumah Sakit', slug: 'bed-rs', topic: 'Kesehatan', isPublic: true, status: 'Terbit' },
  { id: 15, title: 'Cakupan Imunisasi Anak', slug: 'imunisasi-anak', topic: 'Kesehatan', isPublic: true, status: 'Terbit' },
  { id: 16, title: 'Sebaran Fasilitas Puskesmas', slug: 'sebaran-puskesmas', topic: 'Kesehatan', isPublic: true, status: 'Terbit' },
  { id: 17, title: 'Data Balita Stunting Terintegrasi', slug: 'stunting-balita', topic: 'Kesehatan', isPublic: true, status: 'Terbit' },
  { id: 18, title: 'Tren Penyakit Menular', slug: 'penyakit-menular', topic: 'Kesehatan', isPublic: true, status: 'Terbit' },
  { id: 19, title: 'Layanan Ambulans Gawat Darurat', slug: 'ambulans-darurat', topic: 'Kesehatan', isPublic: true, status: 'Terbit' },
  { id: 20, title: 'Ketersediaan Obat Daerah', slug: 'ketersediaan-obat', topic: 'Kesehatan', isPublic: true, status: 'Terbit' },
  { id: 21, title: 'Anggaran Pengadaan Farmasi', slug: 'anggaran-farmasi', topic: 'Kesehatan', isPublic: false, status: 'Terbit' },
  { id: 22, title: 'Evaluasi Mutu Nakes', slug: 'evaluasi-nakes', topic: 'Kesehatan', isPublic: false, status: 'Terbit' },
  { id: 23, title: 'Data Klaim Jaminan Kesehatan Daerah', slug: 'klaim-jamkesda', topic: 'Kesehatan', isPublic: false, status: 'Terbit' },
  { id: 24, title: 'Audit Rekam Medis Rumah Sakit Daerah', slug: 'audit-rekam-medis', topic: 'Kesehatan', isPublic: false, status: 'Terbit' },

  // Additional topics (Pendidikan, Transportasi, Kependudukan, Ekonomi, Lingkungan, Keamanan, Infrastruktur)
  { id: 25, title: 'Capaian Pendidikan Daerah', slug: 'pendidikan-daerah', topic: 'Pendidikan', isPublic: true, status: 'Terbit' },
  { id: 26, title: 'Mobilitas dan Transportasi Publik', slug: 'mobilitas-transportasi', topic: 'Transportasi', isPublic: true, status: 'Terbit' },
  { id: 27, title: 'Demografi dan Kependudukan', slug: 'demografi-kependudukan', topic: 'Kependudukan', isPublic: true, status: 'Terbit' },
  { id: 28, title: 'Pertumbuhan dan Aktivitas Ekonomi', slug: 'ekonomi-pertumbuhan', topic: 'Ekonomi', isPublic: true, status: 'Terbit' },
  { id: 29, title: 'Kualitas dan Kondisi Lingkungan', slug: 'lingkungan-kualitas', topic: 'Lingkungan', isPublic: true, status: 'Terbit' },
  { id: 30, title: 'Keamanan dan Ketertiban Publik', slug: 'keamanan-ketertiban', topic: 'Keamanan', isPublic: true, status: 'Terbit' },
  { id: 31, title: 'Pembangunan dan Infrastruktur', slug: 'infrastruktur-pembangunan', topic: 'Infrastruktur', isPublic: true, status: 'Terbit' },
])

const searchQuery = ref('')
const selectedTopic = ref('Semua topik')
const showPublic = ref(true)
const showPrivate = ref(true)
const isTopicDropdownOpen = ref(false)

const topicCards = computed(() => {
  const topicList = Object.keys(TOPIC_DESCRIPTIONS)

  return topicList.map((topicName) => {
    const topicDashboards = dashboards.value.filter(
        (d) => d.topic.toLowerCase() === topicName.toLowerCase()
    )

    const publicCount = topicDashboards.filter((d) => d.isPublic).length
    const privateCount = topicDashboards.filter((d) => !d.isPublic).length

    return {
      id: topicName.toLowerCase(),
      title: topicName,
      description: TOPIC_DESCRIPTIONS[topicName] || 'Data dan informasi terpadu',
      total: topicDashboards.length > 0 ? topicDashboards.length : 12,
      publicCount: publicCount > 0 ? publicCount : 8,
      privateCount: privateCount > 0 ? privateCount : 4,
    }
  })
})

const topics = computed(() => [
  'Semua topik',
  ...Object.keys(TOPIC_DESCRIPTIONS),
])

const filteredCards = computed(() => {
  return topicCards.value.filter((card) => {
    const q = searchQuery.value.trim().toLowerCase()
    const matchesSearch =
        !q ||
        card.title.toLowerCase().includes(q) ||
        card.description.toLowerCase().includes(q)

    const matchesTopic =
        selectedTopic.value === 'Semua topik' || card.title === selectedTopic.value

    let matchesStatus = true
    if (showPublic.value && !showPrivate.value) {
      matchesStatus = card.publicCount > 0
    } else if (!showPublic.value && showPrivate.value) {
      matchesStatus = card.privateCount > 0
    } else if (!showPublic.value && !showPrivate.value) {
      matchesStatus = false
    }

    return matchesSearch && matchesTopic && matchesStatus
  })
})

function resetFilter() {
  searchQuery.value = ''
  selectedTopic.value = 'Semua topik'
  showPublic.value = true
  showPrivate.value = true
}

function handleViewDetail(id: string) {
  router.push(`/dashboard/${id}`)
}
</script>

<template>
  <div class="space-y-6 max-w-7xl mx-auto selection:bg-blue-100">
    <!-- Header Title -->
    <div>
      <h1 class="text-2xl font-bold tracking-tight text-slate-900">Dashboard</h1>
      <p class="text-xs text-slate-500 mt-1">
        Pantau data dan informasi berdasarkan topik
      </p>
    </div>

    <!-- Search Bar & Filters -->
    <div class="space-y-4">
      <!-- Search Input with "Cari" Button -->
      <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pencarian</label>
        <div class="flex gap-2.5">
          <div class="relative flex-1">
            <input
                v-model="searchQuery"
                type="text"
                placeholder="Cari topik atau dashboard"
                class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs text-slate-800 placeholder:text-slate-400 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600 transition shadow-2xs"
            />
          </div>
          <button
              type="button"
              class="rounded-lg bg-[#2557c7] px-6 py-2 text-xs font-medium text-white hover:bg-blue-700 active:bg-blue-800 transition shadow-2xs cursor-pointer"
          >
            Cari
          </button>
        </div>
      </div>

      <!-- Filter Controls Row -->
      <div class="flex flex-wrap items-center justify-between gap-3 text-xs">
        <!-- Topic Filter Buttons & Dropdown -->
        <div class="flex items-center gap-2">
          <button
              @click="selectedTopic = 'Semua topik'"
              :class="[
              'px-3 py-1.5 rounded-lg text-xs font-medium transition cursor-pointer',
              selectedTopic === 'Semua topik'
                ? 'bg-[#2557c7] text-white shadow-2xs'
                : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50'
            ]"
          >
            Semua topik
          </button>

          <div class="relative">
            <button
                @click="isTopicDropdownOpen = !isTopicDropdownOpen"
                class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-medium cursor-pointer shadow-2xs"
            >
              <span>{{ selectedTopic === 'Semua topik' ? 'Topik' : selectedTopic }}</span>
              <ChevronDown class="h-3.5 w-3.5 text-slate-400" />
            </button>

            <div
                v-if="isTopicDropdownOpen"
                class="absolute left-0 mt-1 w-44 rounded-lg border border-slate-200 bg-white py-1 shadow-lg z-20 text-xs"
            >
              <button
                  v-for="topic in topics"
                  :key="topic"
                  @click="selectedTopic = topic; isTopicDropdownOpen = false"
                  class="w-full px-3 py-1.5 text-left text-slate-700 hover:bg-slate-50 cursor-pointer flex items-center justify-between"
              >
                <span>{{ topic }}</span>
                <Check v-if="selectedTopic === topic" class="h-3.5 w-3.5 text-blue-600" />
              </button>
            </div>
          </div>
        </div>

        <!-- Status Checkboxes & Reset -->
        <div class="flex items-center gap-4 text-xs text-slate-600">
          <span class="text-slate-400">Status: Semua</span>

          <label class="flex items-center gap-1.5 cursor-pointer select-none">
            <input
                type="checkbox"
                v-model="showPublic"
                class="h-3.5 w-3.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
            />
            <span class="text-slate-700">Publik</span>
          </label>

          <label class="flex items-center gap-1.5 cursor-pointer select-none">
            <input
                type="checkbox"
                v-model="showPrivate"
                class="h-3.5 w-3.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
            />
            <span class="text-slate-700">Privat</span>
          </label>

          <button
              @click="resetFilter"
              class="text-slate-500 hover:text-slate-800 text-xs font-medium cursor-pointer ml-1"
          >
            Reset filter
          </button>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div
        v-if="filteredCards.length === 0"
        class="bg-white rounded-xl border border-slate-200 p-14 text-center flex flex-col items-center justify-center shadow-2xs"
    >
      <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
        <FolderOpen class="h-6 w-6" />
      </div>
      <h3 class="text-sm font-bold text-slate-800">Tidak ada topik dashboard ditemukan</h3>
      <p class="text-xs text-slate-400 mt-1 max-w-sm leading-relaxed">
        Tidak ada data yang cocok dengan kriteria pencarian atau status filter yang Anda pilih.
      </p>
      <button
          @click="resetFilter"
          class="mt-4 px-3.5 py-1.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 text-xs font-semibold cursor-pointer"
      >
        Reset Filter
      </button>
    </div>

    <!-- Topic Cards 3-Column Grid -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div
          v-for="card in filteredCards"
          :key="card.id"
          @click="handleViewDetail(card.id)"
          class="rounded-xl bg-white p-5 border border-slate-200 hover:border-blue-500 hover:ring-1 hover:ring-blue-500/20 transition-all duration-200 flex flex-col justify-between shadow-2xs hover:shadow-xs min-h-[145px] cursor-pointer group"
      >
        <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 group-hover:bg-blue-600 group-hover:text-white transition">
            <LayoutGrid class="h-4 w-4" />
          </div>
          <div>
            <h3 class="text-xs font-bold text-slate-900 leading-snug group-hover:text-blue-600 transition">
              {{ card.title }}
            </h3>
            <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">
              {{ card.description }}
            </p>
          </div>
        </div>

        <div class="mt-5 flex items-center justify-between pt-1">
          <div class="flex items-center gap-1.5 text-[11px] text-slate-500">
            <span>{{ card.total }} dashboard</span>
            <span class="px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 font-semibold text-[10px]">
              {{ card.publicCount }} Publik
            </span>
            <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 font-semibold text-[10px]">
              {{ card.privateCount }} Privat
            </span>
          </div>

          <button
              type="button"
              class="text-xs font-medium text-blue-600 group-hover:text-blue-700 cursor-pointer"
          >
            Lihat
          </button>
        </div>
      </div>
    </div>

    <!-- Pagination Footer -->
    <div v-if="filteredCards.length > 0" class="flex items-center justify-between text-xs text-slate-500 pt-2 select-none">
      <span>Menampilkan 1-{{ filteredCards.length }} dari {{ filteredCards.length }} topik</span>

      <div class="flex items-center gap-1.5">
        <button
            disabled
            class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-400 cursor-not-allowed"
        >
          Sebelumnya
        </button>
        <button
            class="w-7 h-7 rounded-lg bg-[#2557c7] text-white font-medium flex items-center justify-center text-xs shadow-2xs"
        >
          1
        </button>
        <button
            disabled
            class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-400 cursor-not-allowed"
        >
          Berikutnya
        </button>
      </div>
    </div>
  </div>
</template>