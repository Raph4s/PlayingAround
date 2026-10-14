<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import {
  FolderKanban,
  Search,
  Plus,
  Edit2,
  Trash2,
  ChevronLeft,
  ChevronRight,
  Database,
  Globe,
  Lock,
  FileText,
} from 'lucide-vue-next'

const router = useRouter()

export interface DashboardItem {
  id: number
  title: string
  kategori: string
  sumberData: string
  publikasi: 'Public' | 'Private' | 'Draft'
  createdAt: string
  updatedAt: string
}

const dashboards = ref<DashboardItem[]>([
  {
    id: 1,
    title: 'Indikator Ekonomi Kota',
    kategori: 'Ekonomi',
    sumberData: 'superset123',
    publikasi: 'Public',
    createdAt: '29 Jul 2026',
    updatedAt: 'Hari ini, 09.42',
  },
  {
    id: 2,
    title: 'Layanan Kesehatan Masyarakat',
    kategori: 'Kesehatan',
    sumberData: 'superset123',
    publikasi: 'Public',
    createdAt: '29 Jul 2026',
    updatedAt: '29 Sep 2026',
  },
  {
    id: 3,
    title: 'Statistik Kunjungan Wisatawan',
    kategori: 'Pariwisata',
    sumberData: 'superset-wisata',
    publikasi: 'Public',
    createdAt: '15 Agu 2026',
    updatedAt: '01 Okt 2026',
  },
  {
    id: 4,
    title: 'Akses & Fasilitas Sarana Pendidikan',
    kategori: 'Pendidikan',
    sumberData: 'superset123',
    publikasi: 'Draft',
    createdAt: '10 Sep 2026',
    updatedAt: '12 Okt 2026',
  },
  {
    id: 5,
    title: 'Evaluasi Internal Pimpinan OPD',
    kategori: 'Evaluasi Kinerja Khusus',
    sumberData: 'superset-internal',
    publikasi: 'Private',
    createdAt: '01 Agu 2026',
    updatedAt: '05 Okt 2026',
  },
])

const searchQuery = ref('')
const selectedPublikasi = ref('Semua')

// Stat cards metrics
const totalDashboard = computed(() => dashboards.value.length)
const totalTerbit = computed(() => dashboards.value.filter((d) => d.publikasi !== 'Draft').length)
const totalDraft = computed(() => dashboards.value.filter((d) => d.publikasi === 'Draft').length)

const filteredDashboards = computed(() => {
  return dashboards.value.filter((item) => {
    const q = searchQuery.value.trim().toLowerCase()
    const matchesSearch =
      !q ||
      item.title.toLowerCase().includes(q) ||
      item.kategori.toLowerCase().includes(q) ||
      item.sumberData.toLowerCase().includes(q)

    const matchesPub =
      selectedPublikasi.value === 'Semua' || item.publikasi === selectedPublikasi.value

    return matchesSearch && matchesPub
  })
})

function handleGoToCreate() {
  router.push('/admin/dashboards/create')
}

function handleGoToEdit(id: number) {
  router.push(`/admin/dashboards/${id}/edit`)
}

function handleDelete(id: number, title: string) {
  if (confirm(`Apakah Anda yakin ingin menghapus dashboard "${title}"?`)) {
    dashboards.value = dashboards.value.filter((d) => d.id !== id)
  }
}
</script>

<template>
  <div class="space-y-6 max-w-7xl mx-auto selection:bg-blue-100 font-sans">
    <!-- Header & Breadcrumbs (Figma: Administrasi / Dashboard) -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <nav class="text-xs text-blue-600 font-medium mb-1">
          <span>Administrasi</span>
          <span class="text-slate-400 mx-1.5">/</span>
          <span class="text-slate-600">Dashboard</span>
        </nav>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Kelola Dashboard</h1>
        <p class="text-xs text-slate-500 mt-1">
          Kelola katalog, pemilik, dan status publikasi dashboard pemerintah kota.
        </p>
      </div>

      <button
        type="button"
        @click="handleGoToCreate"
        class="inline-flex items-center gap-2 rounded-lg bg-[#2557c7] px-5 py-2.5 text-xs font-semibold text-white shadow-2xs hover:bg-blue-700 active:bg-blue-800 transition cursor-pointer self-start sm:self-center"
      >
        <Plus class="h-4 w-4" />
        <span>Tambah</span>
      </button>
    </div>

    <!-- 3 Stat Metric Cards (Figma: Total Dashboard, Sudah Terbit, Draft) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <!-- Card 1 -->
      <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs flex flex-col justify-between">
        <div>
          <span class="text-xs font-medium text-slate-500">Total dashboard</span>
          <div class="text-2xl font-bold text-slate-900 mt-1">{{ totalDashboard }}</div>
        </div>
        <p class="text-[11px] text-slate-400 mt-2">8 kategori layanan</p>
      </div>

      <!-- Card 2 -->
      <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs flex flex-col justify-between">
        <div>
          <span class="text-xs font-medium text-slate-500">Sudah terbit</span>
          <div class="text-2xl font-bold text-emerald-600 mt-1">{{ totalTerbit }}</div>
        </div>
        <p class="text-[11px] text-slate-400 mt-2">Dapat diakses public/private</p>
      </div>

      <!-- Card 3 -->
      <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs flex flex-col justify-between">
        <div>
          <span class="text-xs font-medium text-slate-500">Draft</span>
          <div class="text-2xl font-bold text-amber-600 mt-1">{{ totalDraft }}</div>
        </div>
        <p class="text-[11px] text-slate-400 mt-2">Tidak dapat diakses public/private</p>
      </div>
    </div>

    <!-- Filter Bar (Figma: Pencarian & Publikasi dropdown) -->
    <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs space-y-3 sm:space-y-0 sm:flex sm:items-center sm:gap-4">
      <!-- Search -->
      <div class="relative flex-1 w-full">
        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Pencarian</label>
        <div class="relative">
          <Search class="absolute left-3.5 top-2.5 h-4 w-4 text-slate-400" />
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Cari judul dashboard "
            class="w-full pl-9 pr-4 py-2 bg-slate-50/70 border border-slate-200 rounded-lg text-xs text-slate-800 placeholder:text-slate-400 focus:outline-none focus:border-blue-600 focus:bg-white transition"
          />
        </div>
      </div>

      <!-- Filter Publikasi -->
      <div class="w-full sm:w-56 shrink-0">
        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Publikasi</label>
        <select
          v-model="selectedPublikasi"
          class="w-full bg-slate-50/70 border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-blue-600 focus:bg-white transition cursor-pointer"
        >
          <option value="Semua">Semua publikasi</option>
          <option value="Public">Public</option>
          <option value="Private">Private</option>
          <option value="Draft">Draft</option>
        </select>
      </div>
    </div>

    <!-- Table Section (Exact Columns: ID, DASHBOARD, KATEGORI, SUMBER DATA, PUBLIKASI, CREATED AT, UPDATED AT, AKSI) -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
          <thead>
            <tr class="bg-slate-50/70 border-b border-slate-200 text-slate-400 font-semibold uppercase tracking-wider text-[11px]">
              <th class="py-3.5 px-4 w-14 text-center">ID</th>
              <th class="py-3.5 px-4 min-w-[200px]">DASHBOARD</th>
              <th class="py-3.5 px-4 w-36">KATEGORI</th>
              <th class="py-3.5 px-4 w-36">SUMBER DATA</th>
              <th class="py-3.5 px-4 w-28 text-center">PUBLIKASI</th>
              <th class="py-3.5 px-4 w-32">CREATED AT</th>
              <th class="py-3.5 px-4 w-36">UPDATED AT</th>
              <th class="py-3.5 px-4 text-center w-24">AKSI</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <tr
              v-for="item in filteredDashboards"
              :key="item.id"
              class="hover:bg-slate-50/70 transition"
            >
              <td class="py-4 px-4 text-center text-slate-400 font-mono">
                {{ item.id }}
              </td>

              <td class="py-4 px-4 font-semibold text-slate-900 leading-snug">
                {{ item.title }}
              </td>

              <td class="py-4 px-4 text-slate-700 font-medium">
                {{ item.kategori }}
              </td>

              <td class="py-4 px-4 text-slate-600 font-mono text-[11px]">
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded bg-slate-100 text-slate-700">
                  <Database class="h-3 w-3 text-slate-400" />
                  {{ item.sumberData }}
                </span>
              </td>

              <td class="py-4 px-4 text-center">
                <span
                  :class="[
                    'px-2.5 py-0.5 rounded-full text-[11px] font-semibold inline-block',
                    item.publikasi === 'Public'
                      ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                      : item.publikasi === 'Private'
                      ? 'bg-sky-50 text-sky-700 border border-sky-200'
                      : 'bg-amber-50 text-amber-700 border border-amber-200'
                  ]"
                >
                  {{ item.publikasi }}
                </span>
              </td>

              <td class="py-4 px-4 text-slate-500 text-[11px]">
                {{ item.createdAt }}
              </td>

              <td class="py-4 px-4 text-slate-500 text-[11px]">
                {{ item.updatedAt }}
              </td>

              <td class="py-4 px-4 text-center">
                <div class="inline-flex items-center gap-1.5">
                  <button
                    type="button"
                    @click="handleGoToEdit(item.id)"
                    class="p-1.5 rounded-md border border-slate-200 text-slate-500 hover:text-blue-600 hover:border-blue-300 transition cursor-pointer"
                    title="Edit Dashboard"
                  >
                    <Edit2 class="h-3.5 w-3.5" />
                  </button>
                  <button
                    type="button"
                    @click="handleDelete(item.id, item.title)"
                    class="p-1.5 rounded-md border border-slate-200 text-rose-500 hover:text-rose-700 hover:border-rose-300 transition cursor-pointer"
                    title="Hapus Dashboard"
                  >
                    <Trash2 class="h-3.5 w-3.5" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Footer -->
      <div class="flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 p-4 border-t border-slate-100 gap-3 select-none">
        <span>Menampilkan {{ filteredDashboards.length }} dari {{ dashboards.length }} dashboard</span>
        <div class="flex items-center gap-1">
          <button disabled class="p-1.5 rounded-md border border-slate-200 text-slate-400 cursor-not-allowed">
            <ChevronLeft class="h-3.5 w-3.5" />
          </button>
          <button class="w-7 h-7 rounded-md bg-[#2557c7] text-white font-medium flex items-center justify-center text-xs">
            1
          </button>
          <button disabled class="p-1.5 rounded-md border border-slate-200 text-slate-400 cursor-not-allowed">
            <ChevronRight class="h-3.5 w-3.5" />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
