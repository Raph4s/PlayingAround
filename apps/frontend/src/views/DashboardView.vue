<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import {
  ChevronDown,
  Check,
  Search,
  Compass,
  HeartPulse,
  GraduationCap,
  Bus,
  Users,
  TrendingUp,
  Trees,
  ShieldCheck,
  Building2,
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

const TOPIC_CONFIG: Record<
  string,
  { description: string; icon: any; total: number; publicCount: number; privateCount: number }
> = {
  Pariwisata: {
    description: 'Data dan informasi sektor pariwisata',
    icon: Compass,
    total: 12,
    publicCount: 8,
    privateCount: 4,
  },
  Kesehatan: {
    description: 'Data layanan dan indikator kesehatan',
    icon: HeartPulse,
    total: 12,
    publicCount: 8,
    privateCount: 4,
  },
  Pendidikan: {
    description: 'Data akses dan capaian pendidikan',
    icon: GraduationCap,
    total: 12,
    publicCount: 8,
    privateCount: 4,
  },
  Transportasi: {
    description: 'Data mobilitas dan transportasi publik',
    icon: Bus,
    total: 12,
    publicCount: 8,
    privateCount: 4,
  },
  Kependudukan: {
    description: 'Data demografi dan kependudukan',
    icon: Users,
    total: 12,
    publicCount: 8,
    privateCount: 4,
  },
  Ekonomi: {
    description: 'Data pertumbuhan dan aktivitas ekonomi',
    icon: TrendingUp,
    total: 12,
    publicCount: 8,
    privateCount: 4,
  },
  Lingkungan: {
    description: 'Data kualitas dan kondisi lingkungan',
    icon: Trees,
    total: 12,
    publicCount: 8,
    privateCount: 4,
  },
  Keamanan: {
    description: 'Data keamanan dan ketertiban publik',
    icon: ShieldCheck,
    total: 12,
    publicCount: 8,
    privateCount: 4,
  },
  Infrastruktur: {
    description: 'Data pembangunan dan infrastruktur',
    icon: Building2,
    total: 12,
    publicCount: 8,
    privateCount: 4,
  },
}

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

const topicCards = computed(() => {
  return Object.entries(TOPIC_CONFIG).map(([topicName, cfg]) => {
    return {
      id: topicName.toLowerCase(),
      title: topicName,
      description: cfg.description,
      icon: cfg.icon,
      total: cfg.total,
      publicCount: cfg.publicCount,
      privateCount: cfg.privateCount,
    }
  })
})

const filteredCards = computed(() => {
  return topicCards.value.filter((card) => {
    const q = searchQuery.value.trim().toLowerCase()
    const matchesSearch =
      !q ||
      card.title.toLowerCase().includes(q) ||
      card.description.toLowerCase().includes(q)

    let matchesStatus = true
    if (selectedStatus.value === 'public') {
      matchesStatus = card.publicCount > 0
    } else if (selectedStatus.value === 'private') {
      matchesStatus = card.privateCount > 0
    }

    return matchesSearch && matchesStatus
  })
})

function resetSearch() {
  searchQuery.value = ''
  selectedStatus.value = 'all'
}

function handleViewDetail(id: string) {
  router.push(`/dashboard/${id}`)
}
</script>

<template>
  <div class="space-y-6 max-w-7xl mx-auto selection:bg-blue-100">
    <!-- Page Heading matching Figma Node 1:5910 -->
    <div>
      <h1 class="text-[28px] font-semibold leading-[36px] tracking-tight text-slate-900">
        Dashboard
      </h1>
      <p class="text-[14px] leading-[22px] text-slate-500 mt-1">
        Pantau data dan informasi berdasarkan kategori
      </p>
    </div>

    <!-- Search Controls matching Figma Node 1:5917 -->
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
      v-if="filteredCards.length === 0"
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

    <!-- Topic Cards 3-Column Grid matching Figma Node 1:5920 -->
    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="card in filteredCards"
        :key="card.id"
        @click="handleViewDetail(card.id)"
        class="bg-white border border-slate-200 rounded-xl p-5 shadow-2xs hover:shadow-md hover:border-blue-400 transition-all duration-200 min-h-[140px] flex flex-col justify-between cursor-pointer group"
      >
        <!-- Card Header: Circle Icon + Title & Description -->
        <div class="flex items-center gap-3">
          <div
            class="size-10 rounded-full bg-[#d6e9fd] text-[#0775c5] flex items-center justify-center shrink-0 group-hover:bg-[#0775c5] group-hover:text-white transition duration-200"
          >
            <component :is="card.icon" class="h-5 w-5" />
          </div>
          <div class="min-w-0 flex-1">
            <h2 class="text-base font-semibold text-slate-900 truncate">
              {{ card.title }}
            </h2>
            <p class="text-xs text-slate-500 truncate mt-0.5">
              {{ card.description }}
            </p>
          </div>
        </div>

        <!-- Card Footer: Counts & Badges + "Lihat" -->
        <div class="flex items-center justify-between pt-3 border-t border-slate-100">
          <div class="flex items-center gap-2 text-xs">
            <span class="text-slate-600 font-normal">
              {{ card.total }} dashboard
            </span>
            <span class="bg-[#d6e9fd] text-[#0775c5] px-2 py-0.5 rounded-full font-normal">
              {{ card.publicCount }} Publik
            </span>
            <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full font-normal">
              {{ card.privateCount }} Privat
            </span>
          </div>
          <span
            class="text-sm font-medium text-[#0775c5] group-hover:text-blue-700 flex items-center gap-1 group-hover:translate-x-0.5 transition"
          >
            Lihat
          </span>
        </div>
      </div>
    </div>
  </div>
</template>
