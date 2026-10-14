<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import logoUrl from '@/assets/logo.svg'
import { authService } from '@/services/authService'
import { useAuth } from '@/composables/useAuth'
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

const {
  currentUser,
  isAuthenticated,
  isSuperadmin,
  isAdmin,
  canViewAdministration,
  canManagePreferences,
  syncUserFromStorage,
  clearAuth,
} = useAuth()

// Automatically synchronize auth state on route changes
watch(
  () => route.path,
  () => {
    syncUserFromStorage()
  },
  { immediate: true }
)

const isUserMenuOpen = ref(false)

// Top navigation items (Menu Utama - visible to all: Guests, Users, Admins, Superadmins)
const generalNav = [
  { name: 'Homepage', icon: Home, path: '/' },
  { name: 'Dashboard', icon: LayoutDashboard, path: '/dashboard' },
]

// Administrasi group filtered strictly by permissions:
// 1. Not logged in: Empty (section hidden)
// 2. User: Empty (section hidden)
// 3. Admin: Operational features only (Kelola Kategori, Kelola Dashboard, Superset, Log Aktivitas)
// 4. Superadmin: Full access (All 7 items)
const visibleAdminNav = computed(() => {
  if (!isAuthenticated.value) return []

  if (isSuperadmin.value) {
    return [
      { name: 'User/Admin', icon: Users, path: '/admin/users' },
      { name: 'Kelola Kategori', icon: FolderTree, path: '/admin/parents' },
      { name: 'Kelola Dashboard', icon: FolderKanban, path: '/admin/dashboards' },
      { name: 'Superset', icon: Database, path: '/admin/superset' },
      { name: 'Preferensi', icon: Sliders, path: '/admin/preferences' },
      { name: 'Hak Akses', icon: ShieldCheck, path: '/admin/roles' },
      { name: 'Log Aktivitas', icon: History, path: '/admin/logs' },
    ]
  }

  if (isAdmin.value) {
    return [
      { name: 'Kelola Kategori', icon: FolderTree, path: '/admin/parents' },
      { name: 'Kelola Dashboard', icon: FolderKanban, path: '/admin/dashboards' },
      { name: 'Superset', icon: Database, path: '/admin/superset' },
      { name: 'Log Aktivitas', icon: History, path: '/admin/logs' },
    ]
  }

  // Regular user has no admin nav
  return []
})

function isCurrentRoute(path: string): boolean {
  if (path === '/') return route.path === '/'
  if (path === '/dashboard') return route.path === '/dashboard' || route.path.startsWith('/dashboard/')
  return route.path.startsWith(path)
}

async function handleLogout() {
  await authService.logout()
  clearAuth()
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

      <!-- Right: User Avatar Chip (Logged In) OR Login Button (Not Logged In) -->
      <div v-if="isAuthenticated && currentUser" class="relative">
        <button
            type="button"
            @click="isUserMenuOpen = !isUserMenuOpen"
            class="flex items-center gap-2.5 px-3 py-1.5 rounded-full bg-white/10 hover:bg-white/15 transition border border-white/10 text-xs cursor-pointer focus:outline-none"
        >
          <!-- Initials Bubble -->
          <div class="w-6 h-6 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-[10px] tracking-wider shrink-0 shadow-2xs">
            {{ currentUser.initials }}
          </div>
          <span class="font-semibold text-white tracking-tight">{{ currentUser.name }}</span>
          <span class="text-[10px] bg-blue-500/30 text-blue-200 px-2 py-0.5 rounded-full font-medium border border-blue-400/20">
            {{ currentUser.role }}
          </span>
          <ChevronDown class="w-3.5 h-3.5 text-slate-300" />
        </button>

        <!-- Dropdown Menu -->
        <div
            v-if="isUserMenuOpen"
            @click="isUserMenuOpen = false"
            class="fixed inset-0 z-40"
        ></div>
        <div
            v-if="isUserMenuOpen"
            class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-200/80 py-1.5 z-50 animate-in fade-in zoom-in-95 duration-100"
        >
          <div class="px-4 py-2 border-b border-slate-100">
            <p class="text-xs font-semibold text-slate-800">{{ currentUser.name }}</p>
            <p class="text-[10px] text-slate-400">Masuk sebagai {{ currentUser.role }}</p>
          </div>
          <!-- Pengaturan Akun: Superadmin only -->
          <button
              v-if="canManagePreferences"
              @click="router.push('/admin/preferences')"
              class="w-full text-left px-4 py-2 text-xs text-slate-600 hover:bg-slate-50 flex items-center gap-2 cursor-pointer transition"
          >
            <UserIcon class="w-3.5 h-3.5 text-slate-400" />
            <span>Pengaturan Akun</span>
          </button>
          <button
              @click="handleLogout"
              class="w-full text-left px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 flex items-center gap-2 cursor-pointer transition border-t border-slate-100 mt-1"
          >
            <LogOut class="w-3.5 h-3.5 text-rose-500" />
            <span>Keluar Sesi</span>
          </button>
        </div>
      </div>

      <!-- Guest / Not Logged In: Show Login Button -->
      <div v-else class="flex items-center gap-3">
        <button
            type="button"
            @click="router.push('/login')"
            class="rounded-lg bg-[#2557c7] px-5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-blue-600 active:bg-blue-700 transition cursor-pointer"
        >
          Login
        </button>
      </div>
    </header>

    <!-- Main Workspace Area -->
    <div class="flex-1 flex overflow-hidden">
      <!-- Admin Sidebar (Off-white / light slate) -->
      <aside class="w-64 bg-white border-r border-slate-200 flex flex-col shrink-0">
        <div class="p-4 flex-1 overflow-y-auto space-y-6">
          <!-- General Navigation Section (Homepage & Dashboard for all roles and guests) -->
          <div>
            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider px-3 mb-2">
              Menu Utama
            </div>
            <nav class="space-y-1">
              <router-link
                  v-for="item in generalNav"
                  :key="item.path"
                  :to="item.path"
                  :class="[
                  isCurrentRoute(item.path)
                    ? 'bg-blue-50 text-blue-700 font-semibold'
                    : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium',
                  'flex items-center gap-3 px-3 py-2 rounded-lg text-xs transition cursor-pointer'
                ]"
              >
                <component :is="item.icon" class="h-4 w-4 shrink-0" :class="isCurrentRoute(item.path) ? 'text-blue-600' : 'text-slate-400'" />
                <span>{{ item.name }}</span>
              </router-link>
            </nav>
          </div>

          <!-- Administrasi Section:
               - Hidden when NOT logged in
               - Hidden for User role
               - For Admin: Kelola Kategori, Kelola Dashboard, Superset, Log Aktivitas
               - For Superadmin: All 7 items
          -->
          <div v-if="canViewAdministration && visibleAdminNav.length > 0">
            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider px-3 mb-2">
              Administrasi
            </div>
            <nav class="space-y-1">
              <router-link
                  v-for="item in visibleAdminNav"
                  :key="item.path"
                  :to="item.path"
                  :class="[
                  isCurrentRoute(item.path)
                    ? 'bg-blue-50 text-blue-700 font-semibold'
                    : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium',
                  'flex items-center gap-3 px-3 py-2 rounded-lg text-xs transition cursor-pointer'
                ]"
              >
                <component :is="item.icon" class="h-4 w-4 shrink-0" :class="isCurrentRoute(item.path) ? 'text-blue-600' : 'text-slate-400'" />
                <span>{{ item.name }}</span>
              </router-link>
            </nav>
          </div>
        </div>

        <!-- Sidebar Bottom Footer Info -->
        <div class="p-4 border-t border-slate-100 text-[11px] text-slate-400 flex items-center justify-between">
          <span>Jogja Center v1.0</span>
          <span class="inline-flex items-center gap-1 text-emerald-600 font-medium">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
            Online
          </span>
        </div>
      </aside>

      <!-- Active Content View Port -->
      <main class="flex-1 overflow-y-auto p-8 max-w-7xl mx-auto w-full">
        <router-view />
      </main>
    </div>
  </div>
</template>
