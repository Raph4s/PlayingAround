<script setup lang="ts">
import { ref, computed } from 'vue'
import {
  History,
  Search,
  ChevronDown,
  ChevronLeft,
  ChevronRight,
  Eye,
  X,
  Calendar,
  ShieldCheck,
  Laptop,
  Globe,
  FileCode,
} from 'lucide-vue-next'

export interface LogItem {
  id: number
  code: string
  name: string
  email: string
  role: string
  activity: string
  module: string
  time: string
  fullTimestamp: string
  device: string
  ip: string
  beforeJson?: Record<string, any>
  afterJson?: Record<string, any>
}

const logs = ref<LogItem[]>([
  {
    id: 1,
    code: 'LOG-8841',
    name: 'Dimas Pratama',
    email: 'dimas.pratama@jogjakota.go.id',
    role: 'Admin',
    activity: 'Menguji koneksi Superset Produksi',
    module: 'Superset',
    time: '30 Sep, 10.42.18',
    fullTimestamp: '30 September 2026, 10.42.18 WIB',
    device: 'Chrome 128 • Windows 11 • Perangkat terkelola',
    ip: '10.12.8.45 • Jaringan internal Pemkot Yogyakarta',
    beforeJson: {
      host: 'https://bi.jogjakota.go.id',
      status: 'idle',
    },
    afterJson: {
      host: 'https://bi.jogjakota.go.id',
      status: 'connected',
      latency_ms: 42,
    },
  },
  {
    id: 2,
    code: 'LOG-8840',
    name: 'Sinta Maharani',
    email: 'sinta.maharani@jogjakota.go.id',
    role: 'Admin Konten',
    activity: 'Memperbarui izin Admin Konten',
    module: 'Hak Akses',
    time: '30 Sep, 10.36.02',
    fullTimestamp: '30 September 2026, 10.36.02 WIB',
    device: 'Chrome 128 • Windows 11 • Perangkat terkelola',
    ip: '10.12.8.41 • Jaringan internal Pemkot Yogyakarta',
    beforeJson: {
      role: 'Admin Konten',
      module: 'Superset',
      permissions: ['lihat', 'buat', 'ubah'],
      scope: 'workspace:pariwisata',
    },
    afterJson: {
      role: 'Admin Konten',
      module: 'Superset',
      permissions: ['lihat', 'buat', 'ubah', 'uji_koneksi'],
      scope: 'workspace:pariwisata',
    },
  },
  {
    id: 3,
    code: 'LOG-8839',
    name: 'Raka Wicaksana',
    email: 'raka.wicaksana@jogjakota.go.id',
    role: 'Analis Data',
    activity: 'Menghapus dashboard uji coba',
    module: 'Dashboard',
    time: '30 Sep, 10.21.44',
    fullTimestamp: '30 September 2026, 10.21.44 WIB',
    device: 'Firefox 130 • macOS Sonoma • Kantor Dinas',
    ip: '10.12.8.19 • Jaringan internal Pemkot Yogyakarta',
    beforeJson: {
      dashboard_id: 104,
      title: 'Uji Coba Indikator',
      status: 'Draft',
    },
    afterJson: {
      dashboard_id: 104,
      action: 'deleted',
    },
  },
  {
    id: 4,
    code: 'LOG-8838',
    name: 'api.sync',
    email: 'system@jogjakota.go.id',
    role: 'Sistem',
    activity: 'Token akses tidak valid',
    module: 'Superset',
    time: '30 Sep, 09.58.31',
    fullTimestamp: '30 September 2026, 09.58.31 WIB',
    device: 'Automated Worker • Daemon Herd',
    ip: '127.0.0.1 • Localhost Worker',
    beforeJson: {
      attempt: 1,
      target: 'superset123',
    },
    afterJson: {
      error: '401 Unauthorized',
      reason: 'Token expired',
    },
  },
  {
    id: 5,
    code: 'LOG-8837',
    name: 'Nadia Larasati',
    email: 'nadia.larasati@jogjakota.go.id',
    role: 'Editor',
    activity: 'Mengubah metadata Pariwisata',
    module: 'Preferensi',
    time: '30 Sep, 09.42.07',
    fullTimestamp: '30 September 2026, 09.42.07 WIB',
    device: 'Edge 128 • Windows 11',
    ip: '10.12.8.99 • Jaringan internal Pemkot Yogyakarta',
    beforeJson: {
      title: 'Portal Data DIY',
      keywords: 'data jogja',
    },
    afterJson: {
      title: 'Jogja Center — Portal Data Terpadu DIY',
      keywords: 'data jogja, dashboard publik, pemda diy',
    },
  },
  {
    id: 6,
    code: 'LOG-8836',
    name: 'Bayu Kurniawan',
    email: 'bayu.kurniawan@jogjakota.go.id',
    role: 'Admin',
    activity: 'Mengundang pengguna baru',
    module: 'User/Admin',
    time: '30 Sep, 09.15.55',
    fullTimestamp: '30 September 2026, 09.15.55 WIB',
    device: 'Chrome 128 • Windows 11',
    ip: '10.12.8.45 • Jaringan internal Pemkot Yogyakarta',
    beforeJson: {
      total_users: 18,
    },
    afterJson: {
      invited_user: 'dimas.prabowo@jogjakota.go.id',
      assigned_role: 'Admin',
    },
  },
])

const searchQuery = ref('')
const selectedRole = ref('Semua role')
const selectedModule = ref('Semua modul')
const selectedDateRange = ref('24–30 Sep 2026')

const activeDetailLog = ref<LogItem | null>(null)

const filteredLogs = computed(() => {
  return logs.value.filter((item) => {
    const q = searchQuery.value.trim().toLowerCase()
    const matchesSearch =
      !q ||
      item.name.toLowerCase().includes(q) ||
      item.activity.toLowerCase().includes(q) ||
      item.email.toLowerCase().includes(q)

    const matchesRole =
      selectedRole.value === 'Semua role' || item.role === selectedRole.value

    const matchesMod =
      selectedModule.value === 'Semua modul' || item.module === selectedModule.value

    return matchesSearch && matchesRole && matchesMod
  })
})

function openDetail(log: LogItem) {
  activeDetailLog.value = log
}

function closeDetail() {
  activeDetailLog.value = null
}
</script>

<template>
  <div class="space-y-6 max-w-7xl mx-auto selection:bg-blue-100 font-sans">
    <!-- Header Section (Figma: Log Aktivitas) -->
    <div>
      <nav class="text-xs text-blue-600 font-medium mb-1">
        <span>Administrasi</span>
        <span class="text-slate-400 mx-1.5">/</span>
        <span class="text-slate-600">Log Aktivitas</span>
      </nav>
      <h1 class="text-2xl font-bold tracking-tight text-slate-900">Log Aktivitas</h1>
      <p class="text-xs text-slate-500 mt-1">
        Audit perubahan, akses, dan kejadian keamanan di seluruh modul Jogja Center.
      </p>
    </div>

    <!-- Filters Toolbar (Figma: Pencarian, Role, Modul, Rentang tanggal) -->
    <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-2xs grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
      <!-- Search Input -->
      <div>
        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Pencarian</label>
        <div class="relative">
          <Search class="absolute left-3 top-2.5 h-3.5 w-3.5 text-slate-400" />
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Cari aktor atau aktivitas"
            class="w-full pl-8 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 placeholder:text-slate-400 focus:outline-none focus:border-blue-600 focus:bg-white transition"
          />
        </div>
      </div>

      <!-- Role Filter -->
      <div>
        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Role</label>
        <div class="relative">
          <select
            v-model="selectedRole"
            class="w-full h-9 pl-3 pr-8 rounded-lg border border-slate-200 bg-slate-50 text-xs text-slate-700 focus:outline-none focus:border-blue-600 focus:bg-white transition appearance-none cursor-pointer"
          >
            <option>Semua role</option>
            <option>Admin</option>
            <option>Admin Konten</option>
            <option>Analis Data</option>
            <option>Editor</option>
            <option>Sistem</option>
          </select>
          <ChevronDown class="absolute right-2.5 top-2.5 h-4 w-4 text-slate-400 pointer-events-none" />
        </div>
      </div>

      <!-- Modul Filter -->
      <div>
        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Modul</label>
        <div class="relative">
          <select
            v-model="selectedModule"
            class="w-full h-9 pl-3 pr-8 rounded-lg border border-slate-200 bg-slate-50 text-xs text-slate-700 focus:outline-none focus:border-blue-600 focus:bg-white transition appearance-none cursor-pointer"
          >
            <option>Semua modul</option>
            <option>Superset</option>
            <option>Hak Akses</option>
            <option>Dashboard</option>
            <option>Preferensi</option>
            <option>User/Admin</option>
          </select>
          <ChevronDown class="absolute right-2.5 top-2.5 h-4 w-4 text-slate-400 pointer-events-none" />
        </div>
      </div>

      <!-- Rentang Tanggal -->
      <div>
        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Rentang tanggal</label>
        <div class="relative">
          <Calendar class="absolute left-3 top-2.5 h-3.5 w-3.5 text-slate-400" />
          <input
            v-model="selectedDateRange"
            type="text"
            class="w-full pl-8 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 font-medium focus:outline-none focus:border-blue-600 focus:bg-white transition"
          />
        </div>
      </div>
    </div>

    <!-- Table Container (Exact Columns from Figma: ID, Nama Lengkap, Email, Role, Aktivitas, Fitur/Modul, Waktu, Aksi) -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold uppercase tracking-wider text-[11px]">
              <th class="py-3 px-4 w-20">ID</th>
              <th class="py-3 px-4">Nama Lengkap</th>
              <th class="py-3 px-4">Email</th>
              <th class="py-3 px-4">Role</th>
              <th class="py-3 px-4">Aktivitas</th>
              <th class="py-3 px-4">Fitur/Modul</th>
              <th class="py-3 px-4">Waktu</th>
              <th class="py-3 px-4 text-center w-24">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <tr
              v-for="log in filteredLogs"
              :key="log.id"
              class="hover:bg-slate-50/70 transition"
            >
              <td class="py-3.5 px-4 font-mono text-[11px] text-slate-500">
                {{ log.code }}
              </td>

              <td class="py-3.5 px-4 font-semibold text-slate-900">
                {{ log.name }}
              </td>

              <td class="py-3.5 px-4 text-slate-500">
                {{ log.email }}
              </td>

              <td class="py-3.5 px-4">
                <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700">
                  {{ log.role }}
                </span>
              </td>

              <td class="py-3.5 px-4 text-slate-800 font-medium">
                {{ log.activity }}
              </td>

              <td class="py-3.5 px-4">
                <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                  {{ log.module }}
                </span>
              </td>

              <td class="py-3.5 px-4 text-slate-500 font-mono text-[11px]">
                {{ log.time }}
              </td>

              <td class="py-3.5 px-4 text-center">
                <button
                  type="button"
                  @click="openDetail(log)"
                  class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-semibold text-blue-600 hover:bg-blue-50 transition cursor-pointer"
                >
                  <Eye class="h-3 w-3" />
                  <span>Detail</span>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Footer (Figma: Menampilkan 1–6 dari 2.148 aktivitas) -->
      <div class="p-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between text-xs text-slate-500 gap-3">
        <span>Menampilkan 1–{{ filteredLogs.length }} dari 2.148 aktivitas</span>

        <div class="flex items-center gap-1.5">
          <button class="px-2.5 py-1 rounded border border-slate-200 text-slate-400 cursor-not-allowed">
            <ChevronLeft class="h-3.5 w-3.5" />
          </button>
          <button class="px-3 py-1 rounded bg-blue-600 text-white font-semibold">1</button>
          <button class="px-3 py-1 rounded border border-slate-200 text-slate-700 hover:bg-slate-50">2</button>
          <button class="px-2.5 py-1 rounded border border-slate-200 text-slate-600 hover:bg-slate-50">
            <ChevronRight class="h-3.5 w-3.5" />
          </button>
        </div>
      </div>
    </div>

    <!-- DETAIL MODAL (Exact Figma: Detail Log Aktivitas - Hi-Fi v2 / 1:6123) -->
    <div
      v-if="activeDetailLog"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs animate-fade-in"
    >
      <div class="bg-white rounded-2xl border border-slate-200 shadow-xl max-w-2xl w-full overflow-hidden space-y-4">
        <!-- Modal Header -->
        <div class="p-5 border-b border-slate-200 flex items-center justify-between">
          <div>
            <h3 class="text-base font-bold text-slate-900">Detail Log Aktivitas</h3>
            <p class="text-xs text-slate-500 mt-0.5">{{ activeDetailLog.code }} • {{ activeDetailLog.activity }}</p>
          </div>
          <button
            type="button"
            @click="closeDetail"
            class="p-1 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition cursor-pointer"
          >
            <X class="h-5 w-5" />
          </button>
        </div>

        <!-- Modal Body Content -->
        <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto text-xs">
          <!-- Metadata Cards -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 space-y-0.5">
              <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Aktor</span>
              <p class="font-semibold text-slate-900">{{ activeDetailLog.name }}</p>
              <p class="text-[11px] text-slate-500">{{ activeDetailLog.email }}</p>
            </div>

            <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 space-y-0.5">
              <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Timestamp</span>
              <p class="font-semibold text-slate-900">{{ activeDetailLog.fullTimestamp }}</p>
              <p class="text-[11px] text-slate-500">Waktu Indonesia Barat (UTC+7)</p>
            </div>

            <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 space-y-0.5">
              <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Perangkat</span>
              <p class="font-semibold text-slate-800">{{ activeDetailLog.device }}</p>
            </div>

            <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 space-y-0.5">
              <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Alamat IP</span>
              <p class="font-semibold text-slate-800 font-mono text-[11px]">{{ activeDetailLog.ip }}</p>
            </div>
          </div>

          <!-- Perubahan Sebelum dan Sesudah (JSON Diff Inspector) -->
          <div class="space-y-2 pt-2 border-t border-slate-100">
            <span class="text-xs font-bold text-slate-800 block">Perubahan sebelum dan sesudah</span>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <!-- Sebelum -->
              <div class="space-y-1">
                <div class="text-[11px] font-semibold text-slate-600 flex items-center justify-between">
                  <span>Sebelum</span>
                  <span class="font-mono text-[10px] text-slate-400">JSON</span>
                </div>
                <pre class="p-3 bg-slate-900 text-slate-200 rounded-lg font-mono text-[11px] overflow-x-auto leading-relaxed">{{ JSON.stringify(activeDetailLog.beforeJson, null, 2) }}</pre>
              </div>

              <!-- Sesudah -->
              <div class="space-y-1">
                <div class="text-[11px] font-semibold text-emerald-700 flex items-center justify-between">
                  <span>Sesudah</span>
                  <span class="font-mono text-[10px] text-emerald-600">JSON</span>
                </div>
                <pre class="p-3 bg-slate-900 text-emerald-300 rounded-lg font-mono text-[11px] overflow-x-auto leading-relaxed border border-emerald-900/50">{{ JSON.stringify(activeDetailLog.afterJson, null, 2) }}</pre>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-4 bg-slate-50 border-t border-slate-200 flex justify-end">
          <button
            type="button"
            @click="closeDetail"
            class="px-4 py-2 rounded-lg bg-slate-800 text-white text-xs font-semibold hover:bg-slate-900 transition cursor-pointer"
          >
            Tutup
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
