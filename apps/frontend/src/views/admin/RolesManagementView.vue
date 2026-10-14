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

// Predefined Roles
const roles = [
  { id: 'admin', name: 'Admin', count: 2 },
  { id: 'admin_konten', name: 'Admin Konten', count: 5 },
  { id: 'analis_data', name: 'Analis Data', count: 4 },
  { id: 'viewer', name: 'Viewer', count: 6 },
  { id: 'auditor', name: 'Auditor', count: 1 },
]

// Predefined Users
const users = [
  { id: 1, name: 'Ayu Prameswari', email: 'ayu@jogjakota.go.id', roleId: 'admin_konten', roleName: 'Admin Konten' },
  { id: 2, name: 'Bima Saputra', email: 'bima@jogjakota.go.id', roleId: 'analis_data', roleName: 'Analis Data' },
  { id: 3, name: 'Citra Ningrum', email: 'citra@jogjakota.go.id', roleId: 'viewer', roleName: 'Viewer' },
  { id: 4, name: 'Dimas Prabowo', email: 'dimas@jogjakota.go.id', roleId: 'admin', roleName: 'Admin' },
]

const selectedRoleId = ref('admin')
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
  admin: [
    { key: 'dashboard', name: 'Dashboard', note: 'Dashboard publik dan internal', lihat: true, buat: true, ubah: true, hapus: true },
    { key: 'superset', name: 'Superset', note: 'Koneksi, sinkronisasi, workspace', lihat: true, buat: true, ubah: true, hapus: true },
    { key: 'user', name: 'User/Admin', note: 'Akun pengguna dan undangan', lihat: true, buat: true, ubah: true, hapus: true },
    { key: 'preferensi', name: 'Preferensi', note: 'Identitas, warna, banner, footer', lihat: true, buat: true, ubah: true, hapus: true },
    { key: 'log', name: 'Log Aktivitas', note: 'Audit event dan ekspor log', lihat: true, buat: false, ubah: false, hapus: false, hasBuat: false, hasUbah: false, hasHapus: false },
  ],
  admin_konten: [
    { key: 'dashboard', name: 'Dashboard', note: 'Dashboard publik dan internal', lihat: true, buat: true, ubah: true, hapus: false },
    { key: 'superset', name: 'Superset', note: 'Koneksi, sinkronisasi, workspace', lihat: true, buat: false, ubah: false, hapus: false },
    { key: 'user', name: 'User/Admin', note: 'Akun pengguna dan undangan', lihat: false, buat: false, ubah: false, hapus: false },
    { key: 'preferensi', name: 'Preferensi', note: 'Identitas, warna, banner, footer', lihat: true, buat: false, ubah: true, hapus: false },
    { key: 'log', name: 'Log Aktivitas', note: 'Audit event dan ekspor log', lihat: true, buat: false, ubah: false, hapus: false, hasBuat: false, hasUbah: false, hasHapus: false },
  ],
  analis_data: [
    { key: 'dashboard', name: 'Dashboard', note: 'Dashboard publik dan internal', lihat: true, buat: true, ubah: false, hapus: false },
    { key: 'superset', name: 'Superset', note: 'Koneksi, sinkronisasi, workspace', lihat: true, buat: false, ubah: false, hapus: false },
    { key: 'user', name: 'User/Admin', note: 'Akun pengguna dan undangan', lihat: false, buat: false, ubah: false, hapus: false },
    { key: 'preferensi', name: 'Preferensi', note: 'Identitas, warna, banner, footer', lihat: false, buat: false, ubah: false, hapus: false },
    { key: 'log', name: 'Log Aktivitas', note: 'Audit event dan ekspor log', lihat: false, buat: false, ubah: false, hapus: false, hasBuat: false, hasUbah: false, hasHapus: false },
  ],
  viewer: [
    { key: 'dashboard', name: 'Dashboard', note: 'Dashboard publik dan internal', lihat: true, buat: false, ubah: false, hapus: false },
    { key: 'superset', name: 'Superset', note: 'Koneksi, sinkronisasi, workspace', lihat: false, buat: false, ubah: false, hapus: false },
    { key: 'user', name: 'User/Admin', note: 'Akun pengguna dan undangan', lihat: false, buat: false, ubah: false, hapus: false },
    { key: 'preferensi', name: 'Preferensi', note: 'Identitas, warna, banner, footer', lihat: false, buat: false, ubah: false, hapus: false },
    { key: 'log', name: 'Log Aktivitas', note: 'Audit event dan ekspor log', lihat: false, buat: false, ubah: false, hapus: false, hasBuat: false, hasUbah: false, hasHapus: false },
  ],
  auditor: [
    { key: 'dashboard', name: 'Dashboard', note: 'Dashboard publik dan internal', lihat: true, buat: false, ubah: false, hapus: false },
    { key: 'superset', name: 'Superset', note: 'Koneksi, sinkronisasi, workspace', lihat: true, buat: false, ubah: false, hapus: false },
    { key: 'user', name: 'User/Admin', note: 'Akun pengguna dan undangan', lihat: true, buat: false, ubah: false, hapus: false },
    { key: 'preferensi', name: 'Preferensi', note: 'Identitas, warna, banner, footer', lihat: true, buat: false, ubah: false, hapus: false },
    { key: 'log', name: 'Log Aktivitas', note: 'Audit event dan ekspor log', lihat: true, buat: false, ubah: false, hapus: false, hasBuat: false, hasUbah: false, hasHapus: false },
  ],
})

// Current user custom permissions (if any)
const userPermissions = ref<Record<number, ModulePermission[]>>({
  1: JSON.parse(JSON.stringify(rolePermissions.value.admin_konten)),
  2: JSON.parse(JSON.stringify(rolePermissions.value.analis_data)),
  3: JSON.parse(JSON.stringify(rolePermissions.value.viewer)),
  4: JSON.parse(JSON.stringify(rolePermissions.value.admin)),
})

// Active matrix data
const currentMatrix = computed(() => {
  if (basis.value === 'role') {
    return rolePermissions.value[selectedRoleId.value] || rolePermissions.value.admin
  } else {
    if (!selectedUserId.value) return null
    return userPermissions.value[selectedUserId.value] || null
  }
})

const selectedRole = computed(() => roles.find((r) => r.id === selectedRoleId.value))
const selectedUser = computed(() => users.find((u) => u.id === selectedUserId.value))

const matrixTitle = computed(() => {
  if (basis.value === 'role') {
    return `Matriks izin • ${selectedRole.value?.name || 'Admin'}`
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
                  'px-2 py-0.5 rounded text-[11px] font-medium transition cursor-pointer border',
                  selectedFilterUser === u.name
                    ? 'bg-blue-50 border-blue-200 text-blue-700 font-semibold'
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
            <label class="block text-xs font-medium text-slate-600 mb-1.5">Pilih user</label>
            <div class="relative">
              <select
                v-model="selectedUserId"
                class="w-full h-10 pl-3 pr-9 rounded-lg border border-slate-200 text-xs text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition appearance-none cursor-pointer"
              >
                <option :value="null">Pilih user</option>
                <option v-for="u in users" :key="u.id" :value="u.id">
                  {{ u.name }} ({{ u.roleName }})
                </option>
              </select>
              <ChevronDown class="absolute right-3 top-3 h-4 w-4 text-slate-400 pointer-events-none" />
            </div>
          </div>

          <!-- Role Info & Chips -->
          <div>
            <div class="flex items-center justify-between mb-1.5">
              <label class="block text-xs font-medium text-slate-600">Pilih role</label>
              <span class="inline-flex items-center gap-1.5 text-[11px] text-slate-500">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span>18 pengguna</span>
              </span>
            </div>
            <div class="relative">
              <input
                type="text"
                readonly
                :value="selectedUser ? selectedUser.roleName : 'Admin Konten'"
                class="w-full h-10 pl-3 pr-3 rounded-lg border border-slate-200 text-xs text-slate-700 bg-slate-50/70"
              />
            </div>

            <!-- Peran Tersedia Chips (Exact Figma 1:6388) -->
            <div class="mt-2.5 flex items-center gap-2 flex-wrap">
              <span class="text-[11px] font-medium text-slate-400">Peran tersedia:</span>
              <span
                v-for="r in roles.slice(1)"
                :key="r.id"
                class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-50 border border-slate-200 text-slate-600"
              >
                {{ r.name }}
              </span>
            </div>
          </div>
        </div>

        <!-- Helper Text Row (Figma: Subjek harus dipilih terlebih dahulu) -->
        <div class="flex items-center gap-2 text-xs text-slate-500 bg-slate-50/70 p-2.5 rounded-lg border border-slate-150">
          <Info class="h-4 w-4 text-slate-400 shrink-0" />
          <span>Subjek harus dipilih terlebih dahulu sebelum mengisi matriks izin per modul.</span>
        </div>
      </div>

      <!-- Row 3: Action Row (Langkah pengaturan + Simpan Perubahan Button) -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-4 border-t border-slate-100">
        <div class="text-xs text-slate-500">
          <span class="font-semibold text-slate-700">Langkah pengaturan:</span>
          <span class="ml-1">1. Pilih basis pengaturan, 2. pilih subjek, 3. isi matriks izin, lalu simpan perubahan.</span>
        </div>

        <button
          type="button"
          @click="handleSave"
          :disabled="isSaving || (basis === 'user' && !selectedUserId)"
          class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 text-white text-xs font-semibold hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-2xs cursor-pointer shrink-0"
        >
          <Save class="h-3.5 w-3.5" />
          <span>{{ isSaving ? 'Menyimpan...' : 'Simpan Perubahan' }}</span>
        </button>
      </div>
    </div>

    <!-- Permission Matrix Card (Figma: Permission matrix) -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
      <!-- Matrix Header Bar -->
      <div class="p-5 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
          <h2 class="text-sm font-bold text-slate-900 tracking-tight">
            {{ matrixTitle }}
          </h2>
          <p class="text-xs text-slate-500 mt-0.5">
            <template v-if="basis === 'user' && !selectedUserId">
              Pilih user terlebih dahulu untuk menampilkan dan mengatur izin.
            </template>
            <template v-else>
              Centang izin yang diperlukan untuk setiap modul.
            </template>
          </p>
        </div>

        <span class="inline-flex items-center gap-1.5 text-xs text-slate-400 font-medium">
          <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
          <span>Perubahan otomatis dicatat di Log Aktivitas</span>
        </span>
      </div>

      <!-- Matrix Table -->
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
              <th class="py-3 px-5 w-1/3">MODUL</th>
              <th class="py-3 px-5 text-center w-1/6">LIHAT</th>
              <th class="py-3 px-5 text-center w-1/6">BUAT</th>
              <th class="py-3 px-5 text-center w-1/6">UBAH</th>
              <th class="py-3 px-5 text-center w-1/6">HAPUS</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-xs">
            <!-- Empty state when By User & No user selected -->
            <tr v-if="basis === 'user' && !selectedUserId">
              <td colspan="5" class="py-12 text-center text-slate-400">
                <Users class="h-8 w-8 mx-auto text-slate-300 mb-2" />
                <p class="font-medium">Belum ada user yang dipilih</p>
                <p class="text-[11px] mt-0.5">Pilih user pada menu di atas untuk mengatur matriks kewenangan.</p>
              </td>
            </tr>

            <!-- Permission rows -->
            <tr
              v-else-if="currentMatrix"
              v-for="mod in currentMatrix"
              :key="mod.key"
              class="hover:bg-slate-50/60 transition"
            >
              <!-- Modul Info -->
              <td class="py-4 px-5">
                <div class="font-semibold text-slate-900">{{ mod.name }}</div>
                <div class="text-[11px] text-slate-500 mt-0.5">{{ mod.note }}</div>
              </td>

              <!-- LIHAT -->
              <td class="py-4 px-5 text-center">
                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                  <span
                    :class="[
                      'w-5 h-5 rounded flex items-center justify-center border transition',
                      mod.lihat
                        ? 'bg-blue-600 border-blue-600 text-white'
                        : 'bg-white border-slate-300 text-transparent hover:border-slate-400',
                    ]"
                    @click="mod.lihat = !mod.lihat"
                  >
                    <Check class="h-3.5 w-3.5 stroke-[2.5]" />
                  </span>
                  <span class="text-xs text-slate-700 font-medium">Aktif</span>
                </label>
              </td>

              <!-- BUAT -->
              <td class="py-4 px-5 text-center">
                <template v-if="mod.hasBuat !== false">
                  <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <span
                      :class="[
                        'w-5 h-5 rounded flex items-center justify-center border transition',
                        mod.buat
                          ? 'bg-blue-600 border-blue-600 text-white'
                          : 'bg-white border-slate-300 text-transparent hover:border-slate-400',
                      ]"
                      @click="mod.buat = !mod.buat"
                    >
                      <Check class="h-3.5 w-3.5 stroke-[2.5]" />
                    </span>
                    <span class="text-xs text-slate-700 font-medium">Aktif</span>
                  </label>
                </template>
                <template v-else>
                  <span class="text-slate-300 font-mono">—</span>
                </template>
              </td>

              <!-- UBAH -->
              <td class="py-4 px-5 text-center">
                <template v-if="mod.hasUbah !== false">
                  <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <span
                      :class="[
                        'w-5 h-5 rounded flex items-center justify-center border transition',
                        mod.ubah
                          ? 'bg-blue-600 border-blue-600 text-white'
                          : 'bg-white border-slate-300 text-transparent hover:border-slate-400',
                      ]"
                      @click="mod.ubah = !mod.ubah"
                    >
                      <Check class="h-3.5 w-3.5 stroke-[2.5]" />
                    </span>
                    <span class="text-xs text-slate-700 font-medium">Aktif</span>
                  </label>
                </template>
                <template v-else>
                  <span class="text-slate-300 font-mono">—</span>
                </template>
              </td>

              <!-- HAPUS -->
              <td class="py-4 px-5 text-center">
                <template v-if="mod.hasHapus !== false">
                  <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <span
                      :class="[
                        'w-5 h-5 rounded flex items-center justify-center border transition',
                        mod.hapus
                          ? 'bg-blue-600 border-blue-600 text-white'
                          : 'bg-white border-slate-300 text-transparent hover:border-slate-400',
                      ]"
                      @click="mod.hapus = !mod.hapus"
                    >
                      <Check class="h-3.5 w-3.5 stroke-[2.5]" />
                    </span>
                    <span class="text-xs text-slate-700 font-medium">Aktif</span>
                  </label>
                </template>
                <template v-else>
                  <span class="text-slate-300 font-mono">—</span>
                </template>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Security Note Footer (Exact Figma: Security note) -->
      <div class="p-4 bg-slate-50 border-t border-slate-200 flex items-start sm:items-center gap-3">
        <ShieldAlert class="h-4 w-4 text-amber-600 shrink-0 mt-0.5 sm:mt-0" />
        <div class="text-xs text-slate-600">
          <span class="font-semibold text-slate-800">Catatan keamanan:</span>
          <span class="ml-1">Tinjau izin Lihat, Buat, Ubah, dan Hapus untuk setiap modul sebelum menyimpan perubahan.</span>
        </div>
      </div>
    </div>
  </div>
</template>
