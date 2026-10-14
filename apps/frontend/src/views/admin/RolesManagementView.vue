<script setup lang="ts">
import { ref, computed } from 'vue'
import {
  ShieldAlert,
  Info,
  ChevronDown,
  Check,
  Save,
  Users,
  CheckCircle2,
} from 'lucide-vue-next'

type SettingBasis = 'role' | 'user'

const basis = ref<SettingBasis>('role')

// 3 Predefined Roles: Superadmin, Admin, User
const roles = [
  { id: 'superadmin', name: 'Superadmin', count: 1 },
  { id: 'admin', name: 'Admin', count: 2 },
  { id: 'user', name: 'User', count: 3 },
]

// Predefined Users with 3 Roles
const users = [
  { id: 1, name: 'Ayu Prameswari', email: 'ayu@jogjakota.go.id', roleId: 'superadmin', roleName: 'Superadmin' },
  { id: 2, name: 'Bima Saputra', email: 'bima@jogjakota.go.id', roleId: 'admin', roleName: 'Admin' },
  { id: 3, name: 'Citra Ningrum', email: 'citra@jogjakota.go.id', roleId: 'user', roleName: 'User' },
  { id: 4, name: 'Dimas Prabowo', email: 'dimas@jogjakota.go.id', roleId: 'admin', roleName: 'Admin' },
]

const selectedRoleId = ref('superadmin')
const selectedUserId = ref<number | null>(null)
const selectedFilterUser = ref('')

// Matrix permissions per module
interface ModulePermission {
  key: string
  name: string
  note: string
  lihat: boolean
  buat: boolean
  ubah: boolean
  hapus: boolean
  hasBuat?: boolean
  hasUbah?: boolean
  hasHapus?: boolean
}

// Initial default permissions per role
const rolePermissions = ref<Record<string, ModulePermission[]>>({
  superadmin: [
    { key: 'dashboard', name: 'Dashboard', note: 'Dashboard publik dan internal', lihat: true, buat: true, ubah: true, hapus: true },
    { key: 'superset', name: 'Superset', note: 'Koneksi, sinkronisasi, workspace', lihat: true, buat: true, ubah: true, hapus: true },
    { key: 'user', name: 'User/Admin', note: 'Akun pengguna dan undangan', lihat: true, buat: true, ubah: true, hapus: true },
    { key: 'preferensi', name: 'Preferensi', note: 'Identitas, warna, banner, footer', lihat: true, buat: true, ubah: true, hapus: true },
    { key: 'log', name: 'Log Aktivitas', note: 'Audit event dan ekspor log', lihat: true, buat: true, ubah: true, hapus: true },
  ],
  admin: [
    { key: 'dashboard', name: 'Dashboard', note: 'Dashboard publik dan internal', lihat: true, buat: true, ubah: true, hapus: true },
    { key: 'superset', name: 'Superset', note: 'Koneksi, sinkronisasi, workspace', lihat: true, buat: true, ubah: true, hapus: true },
    { key: 'user', name: 'User/Admin', note: 'Akun pengguna dan undangan', lihat: true, buat: true, ubah: true, hapus: true },
    { key: 'preferensi', name: 'Preferensi', note: 'Identitas, warna, banner, footer', lihat: true, buat: true, ubah: true, hapus: true },
    { key: 'log', name: 'Log Aktivitas', note: 'Audit event dan ekspor log', lihat: true, buat: false, ubah: false, hapus: false, hasBuat: false, hasUbah: false, hasHapus: false },
  ],
  user: [
    { key: 'dashboard', name: 'Dashboard', note: 'Dashboard publik dan internal', lihat: true, buat: false, ubah: false, hapus: false },
    { key: 'superset', name: 'Superset', note: 'Koneksi, sinkronisasi, workspace', lihat: false, buat: false, ubah: false, hapus: false },
    { key: 'user', name: 'User/Admin', note: 'Akun pengguna dan undangan', lihat: false, buat: false, ubah: false, hapus: false },
    { key: 'preferensi', name: 'Preferensi', note: 'Identitas, warna, banner, footer', lihat: false, buat: false, ubah: false, hapus: false },
    { key: 'log', name: 'Log Aktivitas', note: 'Audit event dan ekspor log', lihat: false, buat: false, ubah: false, hapus: false, hasBuat: false, hasUbah: false, hasHapus: false },
  ],
})

// Current user custom permissions
const userPermissions = ref<Record<number, ModulePermission[]>>({
  1: JSON.parse(JSON.stringify(rolePermissions.value.superadmin)),
  2: JSON.parse(JSON.stringify(rolePermissions.value.admin)),
  3: JSON.parse(JSON.stringify(rolePermissions.value.user)),
  4: JSON.parse(JSON.stringify(rolePermissions.value.admin)),
})

// Active matrix data
const currentMatrix = computed(() => {
  if (basis.value === 'role') {
    return rolePermissions.value[selectedRoleId.value] || rolePermissions.value.superadmin
  } else {
    if (!selectedUserId.value) return null
    return userPermissions.value[selectedUserId.value] || null
  }
})

const selectedRole = computed(() => roles.find((r) => r.id === selectedRoleId.value))
const selectedUser = computed(() => users.find((u) => u.id === selectedUserId.value))

const matrixTitle = computed(() => {
  if (basis.value === 'role') {
    return `Matriks izin • ${selectedRole.value?.name || 'Superadmin'}`
  } else {
    if (!selectedUser.value) return 'Matriks izin • Belum dipilih'
    return `Matriks izin • ${selectedUser.value.name} (${selectedUser.value.roleName})`
  }
})

const isSaving = ref(false)
const saveSuccess = ref(false)

function handleSave() {
  isSaving.value = true
  setTimeout(() => {
    isSaving.value = false
    saveSuccess.value = true
    setTimeout(() => {
      saveSuccess.value = false
    }, 2500)
  }, 400)
}

function handleSelectUserChip(userName: string) {
  const found = users.find((u) => u.name === userName)
  if (found) {
    if (basis.value === 'role') {
      selectedFilterUser.value = userName
    } else {
      selectedUserId.value = found.id
    }
  }
}
</script>

<template>
  <div class="space-y-6 max-w-7xl mx-auto selection:bg-blue-100 font-sans">
    <!-- Header Section (Figma: Kelola Hak Akses) -->
    <div class="space-y-1">
      <nav class="text-xs text-blue-600 font-medium mb-1">
        <span>Administrasi</span>
        <span class="text-slate-400 mx-1.5">/</span>
        <span class="text-slate-600">Hak Akses</span>
      </nav>
      <h1 class="text-2xl font-bold tracking-tight text-slate-900">Kelola Hak Akses</h1>
      <p class="text-xs text-slate-500">
        Pilih role atau user, lalu atur matriks izin untuk modul yang diizinkan.
      </p>
    </div>

    <!-- Configuration Control Panel (Figma: Page header) -->
    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs space-y-5">
      <!-- Row 1: Basis Pengaturan (By Role / By User Segmented Tabs) -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
        <div>
          <span class="text-xs font-semibold text-slate-700 block mb-1.5">Basis pengaturan</span>
          <div class="inline-flex p-1 bg-slate-100 rounded-lg border border-slate-200">
            <button
              type="button"
              @click="basis = 'role'"
              :class="[
                'px-4 py-1.5 rounded-md text-xs font-semibold transition cursor-pointer',
                basis === 'role'
                  ? 'bg-white text-blue-700 shadow-2xs'
                  : 'text-slate-600 hover:text-slate-900',
              ]"
            >
              By Role
            </button>
            <button
              type="button"
              @click="basis = 'user'"
              :class="[
                'px-4 py-1.5 rounded-md text-xs font-semibold transition cursor-pointer',
                basis === 'user'
                  ? 'bg-white text-blue-700 shadow-2xs'
                  : 'text-slate-600 hover:text-slate-900',
              ]"
            >
              By User
            </button>
          </div>
        </div>

        <!-- Notification Banner if Saved -->
        <div
          v-if="saveSuccess"
          class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-medium animate-fade-in"
        >
          <CheckCircle2 class="h-4 w-4 shrink-0 text-emerald-600" />
          <span>Perubahan hak akses berhasil disimpan & dicatat ke Log Aktivitas.</span>
        </div>
      </div>

      <!-- Row 2: Subjek Pengaturan (Role Selection or User Selection) -->
      <div class="space-y-4">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-slate-700">Subjek pengaturan</span>
        </div>

        <!-- Mode A: By Role -->
        <div v-if="basis === 'role'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Select Role -->
          <div>
            <label class="block text-xs font-medium text-slate-600 mb-1.5">Pilih role</label>
            <div class="relative">
              <select
                v-model="selectedRoleId"
                class="w-full h-10 pl-3 pr-9 rounded-lg border border-slate-200 text-xs text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition appearance-none cursor-pointer"
              >
                <option v-for="r in roles" :key="r.id" :value="r.id">
                  {{ r.name }} ({{ r.count }} user)
                </option>
              </select>
              <ChevronDown class="absolute right-3 top-3 h-4 w-4 text-slate-400 pointer-events-none" />
            </div>
          </div>

          <!-- Filter Pengguna -->
          <div>
            <div class="flex items-center justify-between mb-1.5">
              <label class="block text-xs font-medium text-slate-600">Filter pengguna</label>
              <span class="inline-flex items-center gap-1.5 text-[11px] text-slate-500">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                <span>Belum dipilih</span>
              </span>
            </div>
            <div class="relative">
              <select
                v-model="selectedFilterUser"
                class="w-full h-10 pl-3 pr-9 rounded-lg border border-slate-200 text-xs text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition appearance-none cursor-pointer"
              >
                <option value="">Pilih pengguna (Semua)</option>
                <option v-for="u in users" :key="u.id" :value="u.name">
                  {{ u.name }} — {{ u.email }}
                </option>
              </select>
              <ChevronDown class="absolute right-3 top-3 h-4 w-4 text-slate-400 pointer-events-none" />
            </div>

            <!-- Pengguna Tersedia Chips (Exact Figma 1:5088) -->
            <div class="mt-2.5 flex items-center gap-2 flex-wrap">
              <span class="text-[11px] font-medium text-slate-400">Pengguna tersedia:</span>
              <button
                v-for="u in users"
                :key="u.id"
                type="button"
                @click="handleSelectUserChip(u.name)"
                :class="[
                  'px-2.5 py-1 rounded-full text-[11px] font-medium border transition cursor-pointer',
                  selectedFilterUser === u.name
                    ? 'bg-blue-50 border-blue-300 text-blue-700'
                    : 'bg-slate-50 border-slate-200 text-slate-600 hover:bg-slate-100',
                ]"
              >
                {{ u.name }}
              </button>
            </div>
          </div>
        </div>

        <!-- Mode B: By User -->
        <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Select User -->
          <div>
            <label class="block text-xs font-medium text-slate-600 mb-1.5">Pilih pengguna spesifik</label>
            <div class="relative">
              <select
                v-model="selectedUserId"
                class="w-full h-10 pl-3 pr-9 rounded-lg border border-slate-200 text-xs text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition appearance-none cursor-pointer"
              >
                <option :value="null">Pilih nama pengguna...</option>
                <option v-for="u in users" :key="u.id" :value="u.id">
                  {{ u.name }} ({{ u.roleName }})
                </option>
              </select>
              <ChevronDown class="absolute right-3 top-3 h-4 w-4 text-slate-400 pointer-events-none" />
            </div>
          </div>

          <!-- User Info Summary Card -->
          <div class="flex items-center gap-3 p-3 rounded-lg bg-slate-50 border border-slate-200 text-xs">
            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-[11px] shrink-0">
              {{ selectedUser ? selectedUser.name.slice(0, 2).toUpperCase() : '--' }}
            </div>
            <div class="overflow-hidden">
              <p class="font-semibold text-slate-800 truncate">
                {{ selectedUser ? selectedUser.name : 'Belum ada pengguna dipilih' }}
              </p>
              <p class="text-[11px] text-slate-500 truncate">
                {{ selectedUser ? `${selectedUser.email} • Peran bawaan: ${selectedUser.roleName}` : 'Pilih dari dropdown di samping' }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Permission Matrix Table (Figma: Matrix card) -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
      <!-- Matrix Header Row -->
      <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
        <div>
          <h2 class="text-sm font-bold text-slate-900">{{ matrixTitle }}</h2>
          <p class="text-xs text-slate-500 mt-0.5">
            Tentukan aksi yang diizinkan untuk setiap modul sistem.
          </p>
        </div>

        <button
          type="button"
          @click="handleSave"
          :disabled="isSaving || (!currentMatrix && basis === 'user')"
          class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[#2557c7] text-white text-xs font-semibold hover:bg-blue-700 active:bg-blue-800 transition shadow-2xs cursor-pointer disabled:opacity-50"
        >
          <Save class="h-3.5 w-3.5" />
          <span>{{ isSaving ? 'Menyimpan...' : 'Simpan Izin' }}</span>
        </button>
      </div>

      <!-- Empty State for User Not Selected -->
      <div
        v-if="!currentMatrix && basis === 'user'"
        class="p-12 text-center text-slate-400 space-y-2"
      >
        <Users class="h-8 w-8 mx-auto text-slate-300 stroke-1" />
        <p class="text-xs font-medium text-slate-600">Pilih salah satu pengguna terlebih dahulu</p>
        <p class="text-[11px] text-slate-400">
          Matriks izin spesifik pengguna akan tampil setelah pengguna dipilih.
        </p>
      </div>

      <!-- Matrix Table -->
      <div v-else class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider text-[11px]">
              <th class="py-3.5 px-6">Modul Sistem</th>
              <th class="py-3.5 px-4 text-center w-24">Lihat</th>
              <th class="py-3.5 px-4 text-center w-24">Buat</th>
              <th class="py-3.5 px-4 text-center w-24">Ubah</th>
              <th class="py-3.5 px-4 text-center w-24">Hapus</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <tr
              v-for="item in currentMatrix"
              :key="item.key"
              class="hover:bg-slate-50/70 transition"
            >
              <!-- Module Description -->
              <td class="py-4 px-6">
                <span class="font-bold text-slate-900 block text-xs">{{ item.name }}</span>
                <span class="text-[11px] text-slate-400 mt-0.5 block">{{ item.note }}</span>
              </td>

              <!-- Checkbox: Lihat -->
              <td class="py-4 px-4 text-center">
                <label class="inline-flex items-center justify-center cursor-pointer">
                  <input
                    type="checkbox"
                    v-model="item.lihat"
                    class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
                  />
                </label>
              </td>

              <!-- Checkbox: Buat -->
              <td class="py-4 px-4 text-center">
                <label
                  v-if="item.hasBuat !== false"
                  class="inline-flex items-center justify-center cursor-pointer"
                >
                  <input
                    type="checkbox"
                    v-model="item.buat"
                    class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
                  />
                </label>
                <span v-else class="text-slate-300 select-none">—</span>
              </td>

              <!-- Checkbox: Ubah -->
              <td class="py-4 px-4 text-center">
                <label
                  v-if="item.hasUbah !== false"
                  class="inline-flex items-center justify-center cursor-pointer"
                >
                  <input
                    type="checkbox"
                    v-model="item.ubah"
                    class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
                  />
                </label>
                <span v-else class="text-slate-300 select-none">—</span>
              </td>

              <!-- Checkbox: Hapus -->
              <td class="py-4 px-4 text-center">
                <label
                  v-if="item.hasHapus !== false"
                  class="inline-flex items-center justify-center cursor-pointer"
                >
                  <input
                    type="checkbox"
                    v-model="item.hapus"
                    class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
                  />
                </label>
                <span v-else class="text-slate-300 select-none">—</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
