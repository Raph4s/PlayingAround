<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import {
  ArrowLeft,
  Search,
  ExternalLink,
  Lock,
  Globe,
  SlidersHorizontal,
} from 'lucide-vue-next'

const route = useRoute()
const router = useRouter()

const topicId = computed(() => (route.params.topic as string) || 'pariwisata')
const topicTitle = computed(() => {
  const t = topicId.value
  return t.charAt(0).toUpperCase() + t.slice(1)
})

const searchQuery = ref('')
const filterType = ref<'all' | 'public' | 'private'>('all')

const dashboardsInTopic = ref([
  {
    id: 'wisata-kunjungan',
    title: 'Statistik Kunjungan Wisata Yogyakarta',
    description: 'Visualisasi jumlah kunjungan wisatawan nusantara dan mancanegara per destinasi unggulan bulanan.',
    isPublic: true,
    views: '12,4k',
    lastUpdated: '02 Okt 2026',
  },
  {
    id: 'hotel-okupansi',
    title: 'Okupansi Hotel & Penginapan',
    description: 'Tingkat hunian kamar hotel bintang dan non-bintang di seluruh wilayah kabupaten/kota.',
    isPublic: true,
    views: '8,2k',
    lastUpdated: '28 Sep 2026',
  },
  {
    id: 'desa-wisata',
    title: 'Perkembangan Kunjungan Desa Wisata',
    description: 'Statistik aktivitas ekonomi masyarakat dan wisatawan di desa wisata terdaftar DIY.',
    isPublic: true,
    views: '5,9k',
    lastUpdated: '25 Sep 2026',
  },
  {
    id: 'retribusi-wisata',
    title: 'Pendapatan Retribusi Objek Wisata',
    description: 'Monitoring realisasi penerimaan retribusi daerah sektor pariwisata real-time (Akses Terbatas).',
    isPublic: false,
    views: '1,1k',
    lastUpdated: '01 Okt 2026',
  },
  {
    id: 'audit-pajak-wisata',
    title: 'Audit Pajak Hiburan & Kuliner',
    description: 'Rekapitulasi ketaatan pajak usaha pariwisata dan hiburan lintas sektor.',
    isPublic: false,
    views: '430',
    lastUpdated: '19 Sep 2026',
  },
])

const filteredDashboards = computed(() => {
  return dashboardsInTopic.value.filter((d) => {
    const matchesSearch =
        d.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        d.description.toLowerCase().includes(searchQuery.value.toLowerCase())

    const matchesType =
        filterType.value === 'all' ||
        (filterType.value === 'public' && d.isPublic) ||
        (filterType.value === 'private' && !d.isPublic)

    return matchesSearch && matchesType
  })
})

function handleSelectDashboard(id: string) {
  router.push(`/dashboard/${topicId.value}/${id}`)
}
</script>

<template>
  <div class="space-y-6 max-w-7xl mx-auto selection:bg-blue-100">
    <!-- Breadcrumb & Back -->
    <div class="flex items-center gap-3">
      <button
          @click="router.push('/dashboard')"
          class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition cursor-pointer shadow-2xs"
      >
        <ArrowLeft class="h-3.5 w-3.5" />
        <span>Kembali ke Topik</span>
      </button>
      <span class="text-xs text-slate-400">/</span>
      <span class="text-xs font-semibold text-slate-800">{{ topicTitle }}</span>
    </div>

    <!-- Header Description -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">
          Sektor {{ topicTitle }}
        </h1>
        <p class="text-xs text-slate-500 mt-1">
          Daftar indikator data dan dashboard analitik yang terintegrasi di sektor {{ topicTitle.toLowerCase() }}.
        </p>
      </div>

      <!-- Quick stats chip -->
      <div class="flex items-center gap-2 self-start sm:self-center">
        <span class="px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-blue-700 text-xs font-medium">
          {{ dashboardsInTopic.length }} Total Dashboard
        </span>
      </div>
    </div>

    <!-- Search & Type Filter Toolbar -->
    <div class="flex flex-col sm:flex-row gap-3 items-center justify-between">
      <div class="relative w-full sm:max-w-md">
        <Search class="absolute left-3 top-2.5 h-4 w-4 text-slate-400" />
        <input
            v-model="searchQuery"
            type="text"
            placeholder="Cari indikator atau dashboard..."
            class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-lg text-xs text-slate-800 placeholder:text-slate-400 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition shadow-2xs"
        />
      </div>

      <div class="flex items-center gap-1.5 self-start sm:self-center text-xs">
        <SlidersHorizontal class="h-3.5 w-3.5 text-slate-400 mr-1" />
        <button
            @click="filterType = 'all'"
            :class="[
            'px-3 py-1.5 rounded-lg font-medium transition cursor-pointer',
            filterType === 'all'
              ? 'bg-[#2557c7] text-white shadow-2xs'
              : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50'
          ]"
        >
          Semua
        </button>
        <button
            @click="filterType = 'public'"
            :class="[
            'px-3 py-1.5 rounded-lg font-medium transition cursor-pointer',
            filterType === 'public'
              ? 'bg-[#2557c7] text-white shadow-2xs'
              : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50'
          ]"
        >
          Publik
        </button>
        <button
            @click="filterType = 'private'"
            :class="[
            'px-3 py-1.5 rounded-lg font-medium transition cursor-pointer',
            filterType === 'private'
              ? 'bg-[#2557c7] text-white shadow-2xs'
              : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50'
          ]"
        >
          Privat
        </button>
      </div>
    </div>

    <!-- Dashboards List Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div
          v-for="item in filteredDashboards"
          :key="item.id"
          @click="handleSelectDashboard(item.id)"
          class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs hover:shadow-xs hover:border-blue-500/70 transition flex flex-col justify-between group cursor-pointer"
      >
        <div>
          <div class="flex items-center justify-between mb-3">
            <span
                :class="[
                'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-semibold border',
                item.isPublic
                  ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                  : 'bg-amber-50 text-amber-700 border-amber-200'
              ]"
            >
              <Globe v-if="item.isPublic" class="h-3 w-3" />
              <Lock v-else class="h-3 w-3" />
              <span>{{ item.isPublic ? 'Terbuka Publik' : 'Akses Pimpinan (Privat)' }}</span>
            </span>

            <span class="text-[11px] text-slate-400">Pembaruan: {{ item.lastUpdated }}</span>
          </div>

          <h3 class="text-sm font-bold text-slate-900 group-hover:text-blue-600 transition">
            {{ item.title }}
          </h3>
          <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
            {{ item.description }}
          </p>
        </div>

        <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
          <span class="text-[11px] text-slate-400">{{ item.views }} kali diakses</span>
          <span class="inline-flex items-center gap-1 font-semibold text-blue-600 group-hover:underline">
            <span>Buka Dashboard</span>
            <ExternalLink class="h-3.5 w-3.5" />
          </span>
        </div>
      </div>
    </div>
  </div>
</template>