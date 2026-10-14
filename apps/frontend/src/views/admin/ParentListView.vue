<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'
import {
  FolderTree,
  Search,
  Plus,
  Edit2,
  Trash2,
  ChevronLeft,
  ChevronRight,
  FolderOpen,
  Info,
  ChevronDown,
} from 'lucide-vue-next'

const router = useRouter()
const { canCreateParent } = useAuth()

export interface CategoryItem {
  id: number
  idLabel: string
  name: string
  description: string
  publication: 'Public' | 'Private' | 'Draft'
  createdAt: string
  updatedAt: string
}

// Exact categories from Figma 1:7457
const categories = ref<CategoryItem[]>([
  {
    id: 1,
    idLabel: '—',
    name: 'Ekonomi',
    description: 'Data pertumbuhan ekonomi, inflasi, investasi, dan ketenagakerjaan Kota Yogyakarta.',
    publication: 'Public',
    createdAt: '—',
    updatedAt: '—',
  },
  {
    id: 2,
    idLabel: '—',
    name: 'Kesehatan',
    description: 'Data layanan kesehatan dan kondisi kesehatan masyarakat Kota Yogyakarta.',
    publication: 'Private',
    createdAt: '—',
    updatedAt: '—',
  },
  {
    id: 3,
    idLabel: '—',
    name: 'Pariwisata',
    description: 'Data destinasi, kunjungan wisatawan, dan perkembangan pariwisata kota.',
    publication: 'Draft',
    createdAt: '—',
    updatedAt: '—',
  },
  {
    id: 4,
    idLabel: '—',
    name: 'Pendidikan',
    description: 'Data fasilitas pendidikan, peserta didik, dan capaian pendidikan daerah.',
    publication: 'Public',
    createdAt: '—',
    updatedAt: '—',
  },
  {
    id: 5,
    idLabel: '—',
    name: 'Transportasi',
    description: 'Data mobilitas, angkutan umum, dan infrastruktur transportasi kota.',
    publication: 'Public',
    createdAt: '—',
    updatedAt: '—',
  },
])

const searchQuery = ref('')
const selectedStatus = ref('Semua status')

const totalKategori = computed(() => categories.value.length)
const totalTerbit = computed(() => categories.value.filter((c) => c.publication !== 'Draft').length)
const totalDraft = computed(() => categories.value.filter((c) => c.publication === 'Draft').length)

const filteredCategories = computed(() => {
  return categories.value.filter((item) => {
    const q = searchQuery.value.trim().toLowerCase()
    const matchesSearch =
      !q ||
      item.name.toLowerCase().includes(q) ||
      item.description.toLowerCase().includes(q)

    const matchesStatus =
      selectedStatus.value === 'Semua status' ||
      item.publication === selectedStatus.value

    return matchesSearch && matchesStatus
  })
})

function handleGoToCreate() {
  router.push('/admin/parents/create')
}

function handleGoToEdit(id: number) {
  router.push(`/admin/parents/${id}/edit`)
}

function handleDeleteCategory(id: number, name: string) {
  if (confirm(`Apakah Anda yakin ingin menghapus kategori "${name}"?`)) {
    categories.value = categories.value.filter((p) => p.id !== id)
  }
}
</script>

<template>
  <div class="space-y-6 max-w-7xl mx-auto selection:bg-blue-100 font-sans">
    <!-- Header & Breadcrumb (Figma: Administrasi / Kategori) -->
    <div class="space-y-1">
      <nav class="text-xs text-blue-600 font-medium mb-1">
        <span>Administrasi</span>
        <span class="text-slate-400 mx-1.5">/</span>
        <span class="text-slate-600">Kategori</span>
      </nav>
      <h1 class="text-2xl font-bold tracking-tight text-slate-900">Kelola Kategori</h1>
      <p class="text-xs text-slate-500">
        Kelola nama, deskripsi, dan status kategori untuk pengelompokan dashboard pemerintah kota.
      </p>
    </div>

    <!-- 3 Metric Stat Cards (Exact Figma 1:7457) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <!-- Total kategori -->
      <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs space-y-1">
        <p class="text-xs font-medium text-slate-500">Total kategori</p>
        <p class="text-3xl font-extrabold text-slate-900">{{ totalKategori }}</p>
      </div>

      <!-- Sudah terbit -->
      <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs space-y-1">
        <p class="text-xs font-medium text-slate-500">Sudah terbit</p>
        <div class="flex items-baseline gap-2">
          <span class="text-3xl font-extrabold text-slate-900">{{ totalTerbit }}</span>
          <span class="text-xs text-slate-500">3 public · 1 private</span>
        </div>
      </div>

      <!-- Draft -->
      <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs space-y-1">
        <p class="text-xs font-medium text-slate-500">Draft</p>
        <div class="flex items-baseline gap-2">
          <span class="text-3xl font-extrabold text-slate-900">{{ totalDraft }}</span>
          <span class="text-xs text-slate-500">Belum diterbitkan</span>
        </div>
      </div>
    </div>

    <!-- Toolbar: Search, Status Filter, and Tambah Kategori Button -->
    <div class="flex flex-col sm:flex-row items-end gap-3 justify-between">
      <!-- Search Input -->
      <div class="w-full sm:flex-1">
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pencarian</label>
        <div class="relative">
          <Search class="absolute left-3 top-2.5 h-3.5 w-3.5 text-slate-400" />
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Cari nama kategori"
            class="w-full rounded-lg border border-slate-200 bg-white pl-9 pr-3.5 py-2 text-xs text-slate-800 placeholder:text-slate-400 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600 transition shadow-2xs"
          />
        </div>
      </div>

      <!-- Status Filter -->
      <div class="w-full sm:w-48">
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status</label>
        <div class="relative">
          <select
            v-model="selectedStatus"
            class="w-full appearance-none rounded-lg border border-slate-200 bg-white px-3.5 py-2 pr-8 text-xs text-slate-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600 transition shadow-2xs cursor-pointer"
          >
            <option>Semua status</option>
            <option>Public</option>
            <option>Private</option>
            <option>Draft</option>
          </select>
          <ChevronDown class="absolute right-3 top-2.5 h-3.5 w-3.5 text-slate-400 pointer-events-none" />
        </div>
      </div>

      <!-- Tambah Kategori Button (Hidden for Admin, only Superadmin can create) -->
      <button
        v-if="canCreateParent"
        type="button"
        @click="handleGoToCreate"
        class="w-full sm:w-auto rounded-lg bg-blue-600 px-5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-blue-700 transition cursor-pointer shrink-0"
      >
        Tambah Kategori
      </button>
    </div>

    <!-- Table Container (Columns: ID, NAMA, DESKRIPSI, PUBLIKASI, CREATED AT, UPDATED AT, AKSI) -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
              <th class="py-3 px-4 w-12 text-center">ID</th>
              <th class="py-3 px-4 w-44">NAMA</th>
              <th class="py-3 px-4">DESKRIPSI</th>
              <th class="py-3 px-4 w-28 text-center">PUBLIKASI</th>
              <th class="py-3 px-4 w-28 text-center">CREATED AT</th>
              <th class="py-3 px-4 w-28 text-center">UPDATED AT</th>
              <th class="py-3 px-4 text-center w-24">AKSI</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <tr
              v-for="cat in filteredCategories"
              :key="cat.id"
              class="hover:bg-slate-50/70 transition"
            >
              <td class="py-3.5 px-4 text-center text-slate-400 font-mono">
                {{ cat.idLabel }}
              </td>

              <td class="py-3.5 px-4 font-semibold text-slate-900">
                {{ cat.name }}
              </td>

              <td class="py-3.5 px-4 text-slate-600 leading-relaxed">
                {{ cat.description }}
              </td>

              <td class="py-3.5 px-4 text-center">
                <span
                  :class="[
                    'px-2.5 py-0.5 rounded-full text-[11px] font-semibold inline-block',
                    cat.publication === 'Public'
                      ? 'bg-blue-50 text-blue-700 border border-blue-100'
                      : cat.publication === 'Private'
                      ? 'bg-slate-100 text-slate-700 border border-slate-200'
                      : 'bg-amber-50 text-amber-700 border border-amber-200',
                  ]"
                >
                  {{ cat.publication }}
                </span>
              </td>

              <td class="py-3.5 px-4 text-center text-slate-400 font-mono">
                {{ cat.createdAt }}
              </td>

              <td class="py-3.5 px-4 text-center text-slate-400 font-mono">
                {{ cat.updatedAt }}
              </td>

              <td class="py-3.5 px-4 text-center">
                <div class="inline-flex items-center gap-1.5">
                  <button
                    type="button"
                    @click="handleGoToEdit(cat.id)"
                    class="p-1.5 rounded-md border border-slate-200 text-slate-500 hover:text-blue-600 hover:border-blue-300 transition cursor-pointer"
                    title="Edit Kategori"
                  >
                    <Edit2 class="h-3.5 w-3.5" />
                  </button>
                  <button
                    type="button"
                    @click="handleDeleteCategory(cat.id, cat.name)"
                    class="p-1.5 rounded-md border border-slate-200 text-rose-500 hover:text-rose-700 hover:border-rose-300 transition cursor-pointer"
                    title="Hapus Kategori"
                  >
                    <Trash2 class="h-3.5 w-3.5" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination & Helper Footer (Exact Figma note) -->
      <div class="p-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between text-xs text-slate-500 gap-3">
        <div class="flex items-center gap-2">
          <Info class="h-4 w-4 text-slate-400 shrink-0" />
          <span>Kategori berstatus draft belum diterbitkan. ID, Created at, dan Updated at dikelola otomatis oleh sistem.</span>
        </div>

        <div class="flex items-center gap-3 shrink-0">
          <span>Menampilkan 1–{{ filteredCategories.length }} dari {{ totalKategori }} kategori</span>
          <div class="flex items-center gap-1">
            <button class="w-7 h-7 rounded bg-blue-600 text-white font-semibold flex items-center justify-center text-xs">
              1
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
