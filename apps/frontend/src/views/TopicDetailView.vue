<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import {
  ChevronDown,
  Check,
  Search,
  Lock,
  Clock3,
} from 'lucide-vue-next'

const route = useRoute()
const router = useRouter()

const topicId = computed(() => (route.params.topic as string) || 'pariwisata')
const topicTitle = computed(() => {
  const t = topicId.value
  return t.charAt(0).toUpperCase() + t.slice(1)
})

const searchQuery = ref('')
const selectedStatus = ref<'all' | 'public' | 'private'>('all')
const isStatusDropdownOpen = ref(false)
const statusDropdownRef = ref<HTMLElement | null>(null)

const statusLabels: Record<'all' | 'public' | 'private', string> = {
  all: 'Status: Semua',
  public: 'Status: Publik',
  private: 'Status: Privat',
}

const currentStatusLabel = computed(() => statusLabels[selectedStatus.value])

function setStatus(status: 'all' | 'public' | 'private') {
  selectedStatus.value = status
  isStatusDropdownOpen.value = false
}

function handleClickOutside(event: MouseEvent) {
  if (statusDropdownRef.value && !statusDropdownRef.value.contains(event.target as Node)) {
    isStatusDropdownOpen.value = false
  }
}

onMounted(() => {
  window.addEventListener('click', handleClickOutside)
})

onUnmounted(() => {
  window.removeEventListener('click', handleClickOutside)
})

// Mock datasets mirroring the 12 dashboards in Figma Node 1:5942
const allDashboards = ref([
  // Pariwisata
  {
    id: 'kunjungan-wisatawan',
    topic: 'pariwisata',
    title: 'Kunjungan Wisatawan',
    description: 'Tren kunjungan wisatawan nusantara dan mancanegara',
    isPublic: true,
    updated: '2 jam lalu',
  },
  {
    id: 'okupansi-hotel',
    topic: 'pariwisata',
    title: 'Okupansi Hotel',
    description: 'Tingkat hunian hotel dan akomodasi per bulan',
    isPublic: false,
    updated: '2 jam lalu',
  },
  {
    id: 'sebaran-destinasi',
    topic: 'pariwisata',
    title: 'Sebaran Destinasi',
    description: 'Pemetaan lokasi dan daya tarik wisata daerah',
    isPublic: true,
    updated: '2 jam lalu',
  },
  {
    id: 'profil-wisatawan',
    topic: 'pariwisata',
    title: 'Profil Wisatawan',
    description: 'Karakteristik demografi dan preferensi wisman',
    isPublic: true,
    updated: '2 jam lalu',
  },
  {
    id: 'kalender-event',
    topic: 'pariwisata',
    title: 'Kalender Event Wisata',
    description: 'Agenda kegiatan pariwisata dan festival tahunan',
    isPublic: true,
    updated: '3 jam lalu',
  },
  {
    id: 'pengeluaran-wisatawan',
    topic: 'pariwisata',
    title: 'Pengeluaran Wisatawan',
    description: 'Rata-rata pengeluaran per kunjungan pelancong',
    isPublic: true,
    updated: '3 jam lalu',
  },
  {
    id: 'desa-wisata',
    topic: 'pariwisata',
    title: 'Kunjungan Desa Wisata',
    description: 'Statistik dan aktivitas ekonomi desa wisata',
    isPublic: true,
    updated: '3 jam lalu',
  },
  {
    id: 'malioboro-terpadu',
    topic: 'pariwisata',
    title: 'Kawasan Malioboro Terpadu',
    description: 'Monitoring mobilitas dan kepadatan Malioboro',
    isPublic: true,
    updated: '4 jam lalu',
  },
  {
    id: 'retribusi-wisata',
    topic: 'pariwisata',
    title: 'Pendapatan Retribusi Wisata',
    description: 'Realisasi target penerimaan PAD sektor wisata',
    isPublic: false,
    updated: '4 jam lalu',
  },
  {
    id: 'audit-pajak-wisata',
    topic: 'pariwisata',
    title: 'Audit Pajak Hiburan Wisata',
    description: 'Monitoring ketaatan pajak hotel dan restoran',
    isPublic: false,
    updated: '5 jam lalu',
  },
  {
    id: 'kerjasama-swasta',
    topic: 'pariwisata',
    title: 'Analisis Kerjasama Swasta',
    description: 'Evaluasi kemitraan investasi pariwisata daerah',
    isPublic: false,
    updated: '5 jam lalu',
  },
  {
    id: 'internal-dinas',
    topic: 'pariwisata',
    title: 'Laporan Internal Dinas',
    description: 'Laporan berkala kinerja sektor pariwisata DIY',
    isPublic: false,
    updated: '6 jam lalu',
  },

  // Generics for other topics
  {
    id: 'indikator-layanan',
    topic: 'kesehatan',
    title: 'Layanan Kesehatan Masyarakat',
    description: 'Cakupan dan aksesibilitas faskes primer daerah',
    isPublic: true,
    updated: '1 jam lalu',
  },
  {
    id: 'ketersediaan-bed',
    topic: 'kesehatan',
    title: 'Ketersediaan Bed Rumah Sakit',
    description: 'Monitoring kapasitas rawat inap rumah sakit rujukan',
    isPublic: true,
    updated: '2 jam lalu',
  },
  {
    id: 'farmasi-obat',
    topic: 'kesehatan',
    title: 'Logistik Farmasi & Obat',
    description: 'Stok ketersediaan obat esensial puskesmas daerah',
    isPublic: false,
    updated: '3 jam lalu',
  },
])

const dashboardsInTopic = computed(() => {
  const t = topicId.value.toLowerCase()
  const matching = allDashboards.value.filter((d) => d.topic.toLowerCase() === t)
  if (matching.length > 0) return matching

  // Generic fallback if user opened another topic
  return [
    {
      id: `${t}-overview`,
      topic: t,
      title: `Indikator Utama ${topicTitle.value}`,
      description: `Data konsolidasi agregat sektor ${topicTitle.value.toLowerCase()}`,
      isPublic: true,
      updated: '2 jam lalu',
    },
    {
      id: `${t}-distribusi`,
      topic: t,
      title: `Sebaran Wilayah ${topicTitle.value}`,
      description: `Analisis spasial per kabupaten/kota di DIY`,
      isPublic: true,
      updated: '3 jam lalu',
    },
    {
      id: `${t}-anggaran`,
      topic: t,
      title: `Evaluasi Anggaran ${topicTitle.value}`,
      description: `Realisasi dan efektivitas belanja sektor`,
      isPublic: false,
      updated: '5 jam lalu',
    },
  ]
})

const filteredDashboards = computed(() => {
  return dashboardsInTopic.value.filter((d) => {
    const q = searchQuery.value.trim().toLowerCase()
    const matchesSearch =
      !q ||
      d.title.toLowerCase().includes(q) ||
      d.description.toLowerCase().includes(q)

    let matchesStatus = true
    if (selectedStatus.value === 'public') {
      matchesStatus = d.isPublic
    } else if (selectedStatus.value === 'private') {
      matchesStatus = !d.isPublic
    }

    return matchesSearch && matchesStatus
  })
})

function resetSearch() {
  searchQuery.value = ''
  selectedStatus.value = 'all'
}

function handleSelectDashboard(id: string) {
  router.push(`/dashboard/${topicId.value}/${id}`)
}
</script>

<template>
  <div class="space-y-6 max-w-7xl mx-auto selection:bg-blue-100">
    <!-- Breadcrumb matching Figma Node 1:5945 -->
    <div class="flex items-center gap-2 text-sm font-medium">
      <router-link
        to="/dashboard"
        class="text-[#0775c5] hover:underline"
      >
        Dashboard
      </router-link>
      <span class="text-slate-400">/</span>
      <span class="text-slate-500">{{ topicTitle }}</span>
    </div>

    <!-- Page Heading matching Figma Node 1:5951 -->
    <div>
      <h1 class="text-[28px] font-semibold leading-[36px] tracking-tight text-slate-900">
        {{ topicTitle }}
      </h1>
      <p class="text-[14px] leading-[22px] text-slate-500 mt-1">
        Pantau data dan informasi berdasarkan kategori
      </p>
    </div>

    <!-- Search Controls matching Figma Node 1:5955 -->
    <div class="space-y-3">
      <div>
        <label class="block text-[14px] font-medium leading-[20px] text-slate-900 mb-1.5">
          Pencarian
        </label>
        <div class="flex gap-3">
          <div class="relative flex-1">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Cari kategori atau dashboard"
              @keydown.enter.prevent
              class="w-full h-12 rounded-lg border border-slate-200 bg-white px-4 text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600 transition shadow-2xs"
            />
          </div>
          <button
            type="button"
            class="h-12 w-28 rounded-lg bg-[#2952cc] text-sm font-medium text-white hover:bg-blue-700 active:bg-blue-800 transition shadow-2xs flex items-center justify-center cursor-pointer shrink-0"
          >
            Cari
          </button>
        </div>
      </div>

      <!-- Public Filters: Filter Status Dropdown matching Figma Node 1:5959 -->
      <div class="h-12 flex items-center">
        <div ref="statusDropdownRef" class="relative">
          <button
            type="button"
            @click.stop="isStatusDropdownOpen = !isStatusDropdownOpen"
            class="w-[180px] h-[44px] bg-white border border-slate-200 rounded-lg px-3 flex items-center justify-between text-slate-900 text-sm font-medium shadow-2xs hover:border-slate-300 transition cursor-pointer"
          >
            <span>{{ currentStatusLabel }}</span>
            <ChevronDown
              class="h-4 w-4 text-slate-700 transition-transform duration-200"
              :class="{ 'rotate-180': isStatusDropdownOpen }"
            />
          </button>

          <!-- Dropdown Card -->
          <div
            v-if="isStatusDropdownOpen"
            class="absolute left-0 mt-1.5 w-[180px] rounded-lg border border-slate-200 bg-white py-1 shadow-lg z-30 text-sm"
          >
            <button
              type="button"
              @click="setStatus('all')"
              class="w-full px-3 py-2 text-left flex items-center justify-between text-slate-900 hover:bg-slate-50 transition cursor-pointer"
              :class="{ 'font-semibold bg-blue-50/70 text-blue-700': selectedStatus === 'all' }"
            >
              <span>Status: Semua</span>
              <Check v-if="selectedStatus === 'all'" class="h-4 w-4 text-blue-600" />
            </button>
            <button
              type="button"
              @click="setStatus('public')"
              class="w-full px-3 py-2 text-left flex items-center justify-between text-slate-900 hover:bg-slate-50 transition cursor-pointer"
              :class="{ 'font-semibold bg-blue-50/70 text-blue-700': selectedStatus === 'public' }"
            >
              <span>Status: Publik</span>
              <Check v-if="selectedStatus === 'public'" class="h-4 w-4 text-blue-600" />
            </button>
            <button
              type="button"
              @click="setStatus('private')"
              class="w-full px-3 py-2 text-left flex items-center justify-between text-slate-900 hover:bg-slate-50 transition cursor-pointer"
              :class="{ 'font-semibold bg-blue-50/70 text-blue-700': selectedStatus === 'private' }"
            >
              <span>Status: Privat</span>
              <Check v-if="selectedStatus === 'private'" class="h-4 w-4 text-blue-600" />
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State matching Figma Node 1:5892 (Dashboard - Hasil Kosong) -->
    <div
      v-if="filteredDashboards.length === 0"
      class="bg-white rounded-xl border border-slate-200 h-[480px] flex flex-col items-center justify-center gap-4 text-center shadow-2xs"
    >
      <div class="size-[72px] rounded-full bg-[#d6e9fd] flex items-center justify-center text-[#0775c5]">
        <Search class="h-8 w-8 stroke-[2.2]" />
      </div>
      <div class="space-y-1">
        <h3 class="text-base font-semibold text-slate-900">
          Hasil tidak ditemukan
        </h3>
        <p class="text-sm text-slate-500">
          Coba kata kunci lain
        </p>
      </div>
      <button
        @click="resetSearch"
        type="button"
        class="mt-1 text-xs font-semibold text-[#0775c5] hover:underline cursor-pointer"
      >
        Reset pencarian
      </button>
    </div>

    <!-- Dashboard 4-Column Grid matching Figma Node 1:5960 -->
    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <div
        v-for="item in filteredDashboards"
        :key="item.id"
        @click="handleSelectDashboard(item.id)"
        class="bg-white border border-slate-200 rounded-xl p-4 shadow-2xs hover:shadow-md hover:border-blue-400 transition-all duration-200 h-[142px] min-h-[142px] flex flex-col justify-between cursor-pointer group"
      >
        <!-- Card Heading: Title, Description, and Status Badge -->
        <div class="flex items-start justify-between gap-2">
          <div class="min-w-0 flex-1">
            <h2 class="text-base font-semibold text-slate-900 truncate">
              {{ item.title }}
            </h2>
            <p class="text-xs text-slate-500 truncate mt-0.5">
              {{ item.description }}
            </p>
          </div>

          <!-- Status badge -->
          <div class="shrink-0">
            <span
              v-if="item.isPublic"
              class="bg-[#d6e9fd] text-[#0775c5] text-xs px-2 py-0.5 rounded-full font-normal whitespace-nowrap"
            >
              Publik
            </span>
            <span
              v-else
              class="bg-[#f1f5f9] text-[#475569] text-xs px-2 py-0.5 rounded-full font-normal inline-flex items-center gap-1 whitespace-nowrap"
            >
              <Lock class="size-3" />
              Privat
            </span>
          </div>
        </div>

        <!-- Card Footer: Clock icon and Update Metadata -->
        <div class="flex items-center gap-1.5 text-xs text-slate-500">
          <Clock3 class="size-3.5 text-slate-400 shrink-0" />
          <span>Diperbarui {{ item.updated }}</span>
        </div>
      </div>
    </div>
  </div>
</template>
