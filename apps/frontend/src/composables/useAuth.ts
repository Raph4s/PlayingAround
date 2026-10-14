import { ref, computed } from 'vue'

export type UserRole = 'Superadmin' | 'Admin' | 'User'

export interface AuthUser {
  id?: number | string
  email?: string
  name: string
  initials: string
  role: UserRole
}

const currentUser = ref<AuthUser | null>(null)
const token = ref<string | null>(null)

export function normalizeRole(raw?: string): UserRole {
  const r = (raw || '').toLowerCase().trim()
  if (r.includes('super')) return 'Superadmin'
  if (r.includes('admin')) return 'Admin'
  return 'User'
}

export function syncUserFromStorage(): AuthUser | null {
  try {
    const rawToken = localStorage.getItem('token')
    const rawUser = localStorage.getItem('user')
    token.value = rawToken

    // A user is ONLY authenticated if both token and user exist in storage
    if (rawToken && rawUser) {
      const parsed = JSON.parse(rawUser)
      const role = normalizeRole(parsed.role)
      const name = parsed.name || parsed.fullname || 'User'
      const initials = parsed.initials || (name ? name.substring(0, 2).toUpperCase() : 'U')

      currentUser.value = {
        id: parsed.id,
        email: parsed.email,
        name,
        initials,
        role,
      }
    } else {
      currentUser.value = null
    }
  } catch {
    currentUser.value = null
  }
  return currentUser.value
}

// Initial sync on module evaluation
syncUserFromStorage()

export function useAuth() {
  // Sync on composable invocation to ensure fresh state
  syncUserFromStorage()

  const isAuthenticated = computed(() => !!token.value && !!currentUser.value)
  const isSuperadmin = computed(() => isAuthenticated.value && currentUser.value?.role === 'Superadmin')
  const isAdmin = computed(() => isAuthenticated.value && currentUser.value?.role === 'Admin')
  const isUser = computed(() => isAuthenticated.value && currentUser.value?.role === 'User')

  // Superadmin-only features
  const canManageUsers = computed(() => isSuperadmin.value)
  const canManageRoles = computed(() => isSuperadmin.value)
  const canManagePreferences = computed(() => isSuperadmin.value)
  const canCreateParent = computed(() => isSuperadmin.value) // Hidden for Admin and User

  // Operational features: Superadmin & Admin
  const canViewParents = computed(() => isSuperadmin.value || isAdmin.value)
  const canEditParent = computed(() => isSuperadmin.value || isAdmin.value)
  const canDeleteParent = computed(() => isSuperadmin.value || isAdmin.value)
  const canManageDashboards = computed(() => isSuperadmin.value || isAdmin.value)
  const canViewLogs = computed(() => isSuperadmin.value || isAdmin.value)
  const canManageSuperset = computed(() => isSuperadmin.value || isAdmin.value)

  // Public/all users
  const canViewDashboardCatalog = computed(() => true)

  // Administration section in sidebar:
  // ONLY visible for Superadmin and Admin.
  // HIDDEN when not logged in, HIDDEN for regular User.
  const canViewAdministration = computed(() => isSuperadmin.value || isAdmin.value)

  function setCurrentUser(user: AuthUser | null, userToken?: string | null) {
    currentUser.value = user
    token.value = userToken || null
    if (user && userToken) {
      localStorage.setItem('token', userToken)
      localStorage.setItem('user', JSON.stringify(user))
    } else {
      localStorage.removeItem('token')
      localStorage.removeItem('user')
    }
  }

  function clearAuth() {
    currentUser.value = null
    token.value = null
    localStorage.removeItem('token')
    localStorage.removeItem('user')
  }

  return {
    currentUser,
    isAuthenticated,
    isSuperadmin,
    isAdmin,
    isUser,
    canManageUsers,
    canManageRoles,
    canManagePreferences,
    canViewParents,
    canCreateParent,
    canEditParent,
    canDeleteParent,
    canManageDashboards,
    canViewLogs,
    canManageSuperset,
    canViewDashboardCatalog,
    canViewAdministration,
    syncUserFromStorage,
    setCurrentUser,
    clearAuth,
  }
}
