<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import {
  Database,
  Search,
  Plus,
  Edit2,
  Trash2,
  ChevronLeft,
  ChevronRight,
  Server,
  KeyRound,
  CheckCircle2,
  AlertCircle,
  Eye,
  EyeOff,
} from 'lucide-vue-next'

const router = useRouter()

export interface SupersetConnectionItem {
  id: number
  hostServer: string
  username: string
  passwordMasked: string
  createdAt: string
  updatedAt: string
}

const connections = ref<SupersetConnectionItem[]>([
  {
    id: 1,
    hostServer: 'bi.jogjakota.go.id',
    username: 'svc_jogjacenter_prod',
    passwordMasked: '••••••••',
    createdAt: '28 Jul 2026',
    updatedAt: 'Hari ini, 08:30',
  },
  {
    id: 2,
    hostServer: 'superset.bappeda.go.id',
    username: 'admin_bappeda',
    passwordMasked: '••••••••',
    createdAt: '01 Agu 2026',
    updatedAt: '10 Okt 2026',
  },
  {
    id: 3,
    hostServer: 'bi.dinkes.jogjakota.go.id',
    username: 'dinkes_analyst',
    passwordMasked: '••••••••',
    createdAt: '12 Agu 2026',
    updatedAt: '25 Sep 2026',
  },
  {
    id: 4,
    hostServer: 'data.pariwisata.jogjakota.go.id',
    username: 'pariwisata_bi',
    passwordMasked: '••••••••',
    createdAt: '20 Agu 2026',
    updatedAt: '02 Okt 2026',
  },
  {
    id: 5,
    hostServer: 'staging-bi.jogjakota.go.id',
    username: 'staging_user',
    passwordMasked: '••••••••',
    createdAt: '05 Sep 2026',
    updatedAt: '09 Okt 2026',
  },
])

const searchQuery = ref('')

const totalKoneksi = computed(() => 12)
const terkoneksiDenganDashboard = computed(() => 9)

const filteredConnections = computed(() => {
  return connections.value.filter((item) => {
    const q = searchQuery.value.trim().toLowerCase()
    return !q || item.hostServer.toLowerCase().includes(q) || item.username.toLowerCase().includes(q)
  })
})

function handleGoToCreate() {
  router.push('/admin/superset/create')
}

function handleGoToEdit(id: number) {
  router.push(`/admin/superset/${id}/edit`)
}

function handleDelete(id: number, host: string) {
  if (confirm(`Apakah Anda yakin ingin menghapus koneksi host "${host}"?`)) {
    connections.value = connections.value.filter((c) => c.id !== id)
  }
}
</script>

<template>
  <div class="space-y-6 max-w-7xl mx-auto selection:bg-blue-100 font-sans">
    <!-- Header (Figma: Kelola Superset) -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <nav class="text-xs text-blue-600 font-medium mb-1">
          <span>Administrasi</span>
          <span class="text-slate-400 mx-1.5">/</span>
          <span class="text-slate-600">Superset</span>
        </nav>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Kelola Superset</h1>
        <p class="text-xs text-slate-500 mt-1">
          Pantau koneksi dan sinkronisasi seluruh sumber analitik Jogja Center.
        </p>
      </div>

      <button
        type="button"
        @click="handleGoToCreate"
        class="inline-flex items-center gap-2 rounded-lg bg-[#2557c7] px-5 py-2.5 text-xs font-semibold text-white shadow-2xs hover:bg-blue-700 active:bg-blue-800 transition cursor-pointer self-start sm:self-center"
      >
        <Plus class="h-4 w-4" />
        <span>Tambah koneksi</span>
      </button>
    </div>

    <!-- 2 Metric Stat Cards (Figma: Total koneksi & Terkoneksi dengan dashboard) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <!-- Card 1 -->
      <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs">
        <span class="text-xs font-medium text-slate-500">Total koneksi</span>
        <div class="text-2xl font-bold text-slate-900 mt-1">{{ totalKoneksi }}</div>
      </div>

      <!-- Card 2 -->
      <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs">
        <span class="text-xs font-medium text-slate-500">Terkoneksi dengan dashboard</span>
        <div class="text-2xl font-bold text-slate-900 mt-1">{{ terkoneksiDenganDashboard }}</div>
      </div>
    </div>

    <!-- Search Toolbar (Figma: Cari koneksi / Nama host server atau username) -->
    <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs">
      <label class="block text-[11px] font-semibold text-slate-600 mb-1">Cari koneksi</label>
      <div class="relative">
        <Search class="absolute left-3.5 top-2.5 h-4 w-4 text-slate-400" />
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Nama host server atau username"
          class="w-full pl-9 pr-4 py-2 bg-slate-50/70 border border-slate-200 rounded-lg text-xs text-slate-800 placeholder:text-slate-400 focus:outline-none focus:border-blue-600 focus:bg-white transition"
        />
      </div>
    </div>

    <!-- Table Section (Exact Columns: ID, HOST SERVER, USERNAME, PASSWORD, CREATED AT, UPDATED AT, AKSI) -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
          <thead>
            <tr class="bg-slate-50/70 border-b border-slate-200 text-slate-400 font-semibold uppercase tracking-wider text-[11px]">
              <th class="py-3.5 px-4 w-14 text-center">ID</th>
              <th class="py-3.5 px-4 min-w-[220px]">HOST SERVER</th>
              <th class="py-3.5 px-4 min-w-[180px]">USERNAME</th>
              <th class="py-3.5 px-4 w-32">PASSWORD</th>
              <th class="py-3.5 px-4 w-36">CREATED AT</th>
              <th class="py-3.5 px-4 w-36">UPDATED AT</th>
              <th class="py-3.5 px-4 text-center w-24">AKSI</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <tr
              v-for="item in filteredConnections"
              :key="item.id"
              class="hover:bg-slate-50/70 transition"
            >
              <td class="py-4 px-4 text-center text-slate-400 font-mono">
                {{ item.id }}
              </td>

              <td class="py-4 px-4 font-semibold text-slate-900 font-mono text-xs">
                {{ item.hostServer }}
              </td>

              <td class="py-4 px-4 text-slate-700 font-mono text-xs">
                {{ item.username }}
              </td>

              <td class="py-4 px-4 text-slate-400 font-mono tracking-widest text-xs">
                {{ item.passwordMasked }}
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
                    title="Edit Koneksi"
                  >
                    <Edit2 class="h-3.5 w-3.5" />
                  </button>
                  <button
                    type="button"
                    @click="handleDelete(item.id, item.hostServer)"
                    class="p-1.5 rounded-md border border-slate-200 text-rose-500 hover:text-rose-700 hover:border-rose-300 transition cursor-pointer"
                    title="Hapus Koneksi"
                  >
                    <Trash2 class="h-3.5 w-3.5" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Footer (Figma: Menampilkan 1–5 dari 12 koneksi) -->
      <div class="flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 p-4 border-t border-slate-100 gap-3 select-none">
        <span>Menampilkan 1–{{ filteredConnections.length }} dari {{ totalKoneksi }} koneksi</span>
        <div class="flex items-center gap-1">
          <button disabled class="p-1.5 rounded-md border border-slate-200 text-slate-400 cursor-not-allowed">
            <ChevronLeft class="h-3.5 w-3.5" />
          </button>
          <button class="w-7 h-7 rounded-md bg-[#2557c7] text-white font-medium flex items-center justify-center text-xs">
            1
          </button>
          <button class="w-7 h-7 rounded-md border border-slate-200 text-slate-600 hover:bg-slate-50 font-medium flex items-center justify-center text-xs">
            2
          </button>
          <button class="p-1.5 rounded-md border border-slate-200 text-slate-600 hover:bg-slate-50 cursor-pointer">
            <ChevronRight class="h-3.5 w-3.5" />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
