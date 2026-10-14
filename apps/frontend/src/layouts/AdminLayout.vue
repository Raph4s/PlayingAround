<script setup lang="ts">
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import logoUrl from '@/assets/logo.svg'
import {
  Home,
  LayoutDashboard,
  Users,
  FolderTree,
  FolderKanban,
  Database,
  Sliders,
  ShieldCheck,
  History,
  ChevronDown,
  LogOut,
  User as UserIcon,
} from 'lucide-vue-next'

const router = useRouter()
const route = useRoute()

// Active user state (Superadmin pill on Figma)
const currentUser = ref({
  name: 'Superadmin123',
  initials: 'SA',
  role: 'Superadmin',
})

const isUserMenuOpen = ref(false)

// Top navigation items
const generalNav = [
  { name: 'Homepage', icon: Home, path: '/' },
  { name: 'Dashboard', icon: LayoutDashboard, path: '/dashboard' },
]

// Administrasi group
const adminNav = [
  { name: 'User/Admin', icon: Users, path: '/admin/users' },
  { name: 'Kelola Kategori', icon: FolderTree, path: '/admin/parents' },
  { name: 'Kelola Dashboard', icon: FolderKanban, path: '/admin/dashboards' },
  { name: 'Superset', icon: Database, path: '/admin/superset' },
  { name: 'Preferensi', icon: Sliders, path: '/admin/preferences' },
  { name: 'Hak Akses', icon: ShieldCheck, path: '/admin/roles' },
  { name: 'Log Aktivitas', icon: History, path: '/admin/logs' },
]

function isCurrentRoute(path: string): boolean {
  if (path === '/') return route.path === '/'
  if (path === '/dashboard') return route.path === '/dashboard' || route.path.startsWith('/dashboard/')
  return route.path.startsWith(path)
}

function handleLogout() {
  localStorage.removeItem('token')
  localStorage.removeItem('user')
  router.push('/login')
}
</script>

<template>
  <div class="min-h-screen flex flex-col bg-[#f8fafc] text-slate-800 antialiased font-sans">
    <!-- Topbar (Dark Navy #0e214d) -->
    <header class="h-14 bg-[#0e214d] text-white flex items-center justify-between px-6 sticky top-0 z-40 select-none border-b border-blue-950">
      <!-- Left: Logo & Brand -->
      <div
          @click="router.push('/dashboard')"
          class="flex items-center gap-3 cursor-pointer hover:opacity-90 transition"
      >
        <img :src="logoUrl" alt="Jogja Center" class="h-7 w-7 object-contain" />
        <span class="font-bold text-sm tracking-wide text-white">Jogja Center</span>
      </div>

      <!-- Right: User Avatar Chip & Dropdown -->
      <div class="relative">
        <button
            type="button"
            @click="isUserMenuOpen = !isUserMenuOpen"
            class="flex items-center gap-2 cursor-pointer bg-white/10 hover:bg-white/15 active:bg-white/20 py-1 px-2.5 rounded-full transition text-xs font-medium"
        >
          <div class="w-6 h-6 rounded-full bg-[#fef3c7] text-[#92400e] flex items-center justify-center font-bold text-[10px]">
            {{ currentUser.initials }}
          </div>
          <span>{{ currentUser.name }}</span>
          <ChevronDown class="h-3.5 w-3.5 text-white/70" />
        </button>

        <!-- Profile Dropdown Menu -->
        <div
            v-if="isUserMenuOpen"
            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-200 py-1.5 text-slate-700 z-50 text-xs"
            @click="isUserMenuOpen = false"
        >
          <div class="px-3.5 py-2 border-b border-slate-100">
            <p class="font-bold text-slate-900">{{ currentUser.name }}</p>
            <p class="text-[11px] text-slate-400 capitalize">{{ currentUser.role }}</p>
          </div>
          <button
              type="button"
              @click="router.push('/admin/preferences')"
              class="w-full flex items-center gap-2 px-3.5 py-2 hover:bg-slate-50 text-slate-700 text-left cursor-pointer"
          >
            <UserIcon class="h-3.5 w-3.5" />
            <span>Pengaturan Akun</span>
          </button>
          <button
              type="button"
              @click="handleLogout"
              class="w-full flex items-center gap-2 px-3.5 py-2 hover:bg-rose-50 text-rose-600 text-left cursor-pointer"
          >
            <LogOut class="h-3.5 w-3.5" />
            <span>Keluar</span>
          </button>
        </div>
      </div>
    </header>

    <!-- Shell: Sidebar + Routed Viewport -->
    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <aside class="w-56 bg-white border-r border-slate-200 flex flex-col justify-between shrink-0 py-4 select-none">
        <div class="space-y-6">
          <!-- General Navigation -->
          <div class="px-3 space-y-1">
            <router-link
                v-for="item in generalNav"
                :key="item.name"
                :to="item.path"
                :class="[
                'w-full flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition cursor-pointer text-left',
                isCurrentRoute(item.path)
                  ? 'bg-[#fef6e7] text-[#925509] font-semibold'
                  : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'
              ]"
            >
              <component :is="item.icon" class="h-4 w-4 shrink-0" />
              <span>{{ item.name }}</span>
            </router-link>
          </div>

          <!-- Administrasi Group -->
          <div>
            <div class="px-5 text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">
              Administrasi
            </div>
            <div class="px-3 space-y-1">
              <router-link
                  v-for="item in adminNav"
                  :key="item.name"
                  :to="item.path"
                  :class="[
                  'w-full flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium transition cursor-pointer text-left',
                  isCurrentRoute(item.path)
                    ? 'bg-[#fef6e7] text-[#925509] font-semibold'
                    : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900'
                ]"
              >
                <component :is="item.icon" class="h-4 w-4 shrink-0" />
                <span>{{ item.name }}</span>
              </router-link>
            </div>
          </div>
        </div>

        <!-- System Version -->
        <div class="px-5 text-[11px] text-slate-400">
          v1.0.0 (Superadmin)
        </div>
      </aside>

      <!-- Main Viewport -->
      <main class="flex-1 overflow-y-auto p-6 lg:p-8 bg-[#f8fafc]">
        <router-view />
      </main>
    </div>
  </div>
</template>
