<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import {
  Users,
  UserCheck,
  Mail,
  Edit2,
  Trash2,
  RotateCw,
  ChevronLeft,
  ChevronRight,
  ChevronDown,
} from 'lucide-vue-next'

const router = useRouter()

export interface UserAdminItem {
  id: number
  idLabel: string
  name: string
  username: string
  email: string
  role: string
  statusInvite: 'Aktif' | 'Belum Aktif' | 'Expired'
  isExample?: boolean
}

// 3 Roles: Superadmin, Admin, User
const users = ref<UserAdminItem[]>([
  {
    id: 1,
    idLabel: '—',
    name: 'Ayu Prameswari',
    username: '—',
    email: 'ayu.prameswari@jogjakota.go.id',
    role: 'Superadmin',
    statusInvite: 'Aktif',
  },
  {
    id: 2,
    idLabel: '—',
    name: 'Bima Saputra',
    username: '—',
    email: 'bima.saputra@jogjakota.go.id',
    role: 'Admin',
    statusInvite: 'Aktif',
  },
  {
    id: 3,
    idLabel: '—',
    name: 'Citra Ningrum',
    username: '—',
    email: 'citra.ningrum@jogjakota.go.id',
    role: 'User',
    statusInvite: 'Belum Aktif',
  },
  {
    id: 4,
    idLabel: '—',
    name: 'Dimas Prabowo',
    username: '—',
    email: 'dimas.prabowo@jogjakota.go.id',
    role: 'Admin',
    statusInvite: 'Aktif',
  },
  {
    id: 5,
    idLabel: '—',
    name: 'Eka Lestari',
    username: '—',
    email: 'eka.lestari@jogjakota.go.id',
    role: 'User',
    statusInvite: 'Belum Aktif',
  },
  {
    id: 6,
    idLabel: '—',
    name: 'Contoh undangan expired',
    username: '—',
    email: '—',
    role: '—',
    statusInvite: 'Expired',
    isExample: true,
  },
])

// Filter & Pencarian
const searchQuery = ref('')
const selectedRole = ref('Semua role')

const filteredUsers = computed(() => {
  return users.value.filter((user) => {
    const q = searchQuery.value.trim().toLowerCase()
    const matchesSearch =
        !q ||
        user.name.toLowerCase().includes(q) ||
        user.email.toLowerCase().includes(q)

    const matchesRole =
        selectedRole.value === 'Semua role' || user.role === selectedRole.value

    return matchesSearch && matchesRole
  })
})

function handleGoToInvite() {
  router.push('/admin/users/invite')
}

function handleGoToEdit(id: number) {
  router.push(`/admin/users/${id}/edit`)
}

function handleDeleteUser(id: number) {
  users.value = users.value.filter((u) => u.id !== id)
}

function handleResendInvite(email: string) {
  alert(`Undangan aktivasi telah dikirim ulang ke: ${email}`)
}
</script>

<template>
  <div class="space-y-6 max-w-7xl mx-auto selection:bg-blue-100">
    <!-- Header & Breadcrumb -->
    <div>
      <nav class="text-xs text-blue-600 font-medium mb-1">
        <span>Administrasi</span>
        <span class="text-slate-400 mx-1.5">/</span>
        <span class="text-slate-600">User Admin</span>
      </nav>
      <h1 class="text-2xl font-bold tracking-tight text-slate-900">Kelola User Admin</h1>
      <p class="text-xs text-slate-500 mt-1">
        Atur akses, peran, dan status pengguna pada portal Jogja Center.
      </p>
    </div>

    <!-- 3 Metrik Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <!-- Card 1: Total Pengguna -->
      <div class="bg-white rounded-xl border-2 border-amber-300/80 p-5 shadow-2xs flex items-center justify-between">
        <div>
          <p class="text-xs text-slate-500">Total pengguna</p>
          <p class="text-2xl font-bold text-slate-900 mt-1">28</p>
        </div>
        <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center">
          <Users class="h-5 w-5" />
        </div>
      </div>

      <!-- Card 2: Pengguna Aktif -->
      <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs flex items-center justify-between">
        <div>
          <p class="text-xs text-slate-500">Pengguna aktif</p>
          <div class="flex items-baseline gap-2 mt-1">
            <span class="text-2xl font-bold text-slate-900">24</span>
            <span class="text-[11px] text-emerald-600 font-medium">85% dari total</span>
          </div>
        </div>
        <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
          <UserCheck class="h-5 w-5" />
        </div>
      </div>

      <!-- Card 3: Undangan Pending -->
      <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs flex items-center justify-between">
        <div>
          <p class="text-xs text-slate-500">Undangan pending</p>
          <div class="flex items-baseline gap-2 mt-1">
            <span class="text-2xl font-bold text-slate-900">4</span>
            <span class="text-[11px] text-slate-400 font-normal">Perlu aktivasi email</span>
          </div>
        </div>
        <div class="w-10 h-10 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center">
          <Mail class="h-5 w-5" />
        </div>
      </div>
    </div>

    <!-- Filter Toolbar -->
    <div class="flex flex-col sm:flex-row items-end gap-3 justify-between">
      <!-- Pencarian -->
      <div class="w-full sm:flex-1">
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pencarian</label>
        <input
            v-model="searchQuery"
            type="text"
            placeholder="Cari nama atau email pengguna"
            class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-xs text-slate-800 placeholder:text-slate-400 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600 transition shadow-2xs"
        />
      </div>

      <!-- Filter Role (3 Roles: Superadmin, Admin, User) -->
      <div class="w-full sm:w-48">
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Role</label>
        <div class="relative">
          <select
              v-model="selectedRole"
              class="w-full appearance-none rounded-lg border border-slate-200 bg-white px-3.5 py-2 pr-8 text-xs text-slate-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600 transition shadow-2xs cursor-pointer"
          >
            <option value="Semua role">Semua role</option>
            <option value="Superadmin">Superadmin</option>
            <option value="Admin">Admin</option>
            <option value="User">User</option>
          </select>
          <ChevronDown class="absolute right-3 top-2.5 h-3.5 w-3.5 text-slate-400 pointer-events-none" />
        </div>
      </div>

      <!-- Tombol Invite User -->
      <button
          type="button"
          @click="handleGoToInvite"
          class="w-full sm:w-auto rounded-lg bg-[#2557c7] px-5 py-2 text-xs font-semibold text-white shadow-2xs hover:bg-blue-700 active:bg-blue-800 transition cursor-pointer shrink-0"
      >
        Invite User
      </button>
    </div>

    <!-- Tabel Pengguna -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-2xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
          <thead>
          <tr class="bg-slate-50/60 border-b border-slate-200 text-slate-400 font-semibold uppercase tracking-wider text-[11px]">
            <th class="py-3.5 px-4 w-12 text-center">ID</th>
            <th class="py-3.5 px-4">NAMA LENGKAP</th>
            <th class="py-3.5 px-4">USERNAME</th>
            <th class="py-3.5 px-4">EMAIL</th>
            <th class="py-3.5 px-4">ROLE</th>
            <th class="py-3.5 px-4">STATUS INVITE</th>
            <th class="py-3.5 px-4 text-center w-28">AKSI</th>
          </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
          <tr
              v-for="user in filteredUsers"
              :key="user.id"
              class="hover:bg-slate-50/70 transition"
          >
            <!-- ID -->
            <td class="py-4 px-4 text-center text-slate-400 font-mono">
              {{ user.idLabel }}
            </td>

            <!-- NAMA LENGKAP -->
            <td class="py-4 px-4 font-semibold text-slate-900">
                <span :class="{ 'text-slate-800 font-medium': user.isExample }">
                  {{ user.name }}
                </span>
              <span v-if="user.isExample" class="block text-[10px] text-slate-400 font-normal mt-0.5">
                  (demonstrasi)
                </span>
            </td>

            <!-- USERNAME -->
            <td class="py-4 px-4 text-slate-400">
              {{ user.username }}
            </td>

            <!-- EMAIL -->
            <td class="py-4 px-4 text-slate-600">
              {{ user.email }}
            </td>

            <!-- ROLE -->
            <td class="py-4 px-4 text-slate-700 font-medium">
              {{ user.role }}
            </td>

            <!-- STATUS INVITE -->
            <td class="py-4 px-4">
                <span
                    :class="[
                    'px-3 py-1 rounded-full text-[11px] font-semibold inline-block',
                    user.statusInvite === 'Aktif'
                      ? 'bg-sky-100 text-sky-700'
                      : user.statusInvite === 'Belum Aktif'
                      ? 'bg-slate-100 text-slate-600'
                      : 'bg-rose-100 text-rose-700'
                  ]"
                >
                  {{ user.statusInvite }}
                </span>
            </td>

            <!-- AKSI -->
            <td class="py-4 px-4 text-center">
              <div class="inline-flex items-center gap-1.5">
                <!-- Tombol Edit -->
                <button
                    type="button"
                    @click="handleGoToEdit(user.id)"
                    class="p-1.5 rounded-md border border-slate-200 text-slate-500 hover:text-blue-600 hover:border-blue-300 transition cursor-pointer"
                    title="Edit User"
                >
                  <Edit2 class="h-3.5 w-3.5" />
                </button>

                <!-- Tombol Delete -->
                <button
                    type="button"
                    @click="handleDeleteUser(user.id)"
                    class="p-1.5 rounded-md border border-slate-200 text-rose-500 hover:text-rose-700 hover:border-rose-300 transition cursor-pointer"
                    title="Hapus User"
                >
                  <Trash2 class="h-3.5 w-3.5" />
                </button>

                <!-- Tombol Resend Invitation khusus baris expired -->
                <button
                    v-if="user.statusInvite === 'Expired'"
                    type="button"
                    @click="handleResendInvite(user.email)"
                    class="p-1.5 rounded-md border border-slate-200 text-slate-500 hover:text-blue-600 hover:border-blue-300 transition cursor-pointer"
                    title="Kirim ulang undangan email"
                >
                  <Mail class="h-3.5 w-3.5" />
                </button>
              </div>
            </td>
          </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Footer -->
      <div class="p-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
        <span>Menampilkan 1–6 dari 28 pengguna</span>
        <div class="flex items-center gap-1">
          <button
              type="button"
              class="p-1.5 rounded-md border border-slate-200 text-slate-400 hover:bg-slate-50 cursor-pointer disabled:opacity-40"
              disabled
          >
            <ChevronLeft class="h-3.5 w-3.5" />
          </button>
          <button
              type="button"
              class="w-7 h-7 rounded-md bg-blue-600 text-white font-semibold flex items-center justify-center text-xs"
          >
            1
          </button>
          <button
              type="button"
              class="w-7 h-7 rounded-md text-slate-600 hover:bg-slate-100 font-semibold flex items-center justify-center text-xs cursor-pointer"
          >
            2
          </button>
          <button
              type="button"
              class="w-7 h-7 rounded-md text-slate-600 hover:bg-slate-100 font-semibold flex items-center justify-center text-xs cursor-pointer"
          >
            3
          </button>
          <button
              type="button"
              class="p-1.5 rounded-md border border-slate-200 text-slate-600 hover:bg-slate-50 cursor-pointer"
          >
            <ChevronRight class="h-3.5 w-3.5" />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
