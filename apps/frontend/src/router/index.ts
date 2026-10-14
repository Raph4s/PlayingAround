import { createRouter, createWebHistory } from 'vue-router'
import PublicLayout from '@/layouts/PublicLayout.vue'
import AdminLayout from '@/layouts/AdminLayout.vue'

const routes = [
    // Public
    {
        path: '/',
        component: PublicLayout,
        children: [
            {
                path: '',
                name: 'home',
                component: () => import('@/views/Homeview.vue'),
            },
        ],
    },

    // Auth & Activation
    {
        path: '/login',
        name: 'login',
        component: () => import('@/views/auth/LoginView.vue'),
    },
    {
        path: '/auth/login',
        redirect: '/login',
    },
    {
        path: '/forgot-password',
        name: 'forgot-password',
        component: () => import('@/views/auth/ForgotPasswordView.vue'),
    },
    {
        path: '/verify-code',
        name: 'verify-code',
        component: () => import('@/views/auth/VerifyCodeView.vue'),
    },
    {
        path: '/reset-password',
        name: 'reset-password',
        component: () => import('@/views/auth/ResetPasswordView.vue'),
    },
    {
        path: '/activate',
        name: 'activate',
        component: () => import('@/views/auth/ActivateAccountView.vue'),
    },

    // Back-office Admin Layout Shell
    {
        path: '/',
        component: AdminLayout,
        children: [
            {
                path: 'dashboard',
                name: 'dashboard-catalog',
                component: () => import('@/views/DashboardView.vue'),
            },
            {
                path: 'dashboard/:topic',
                name: 'topic-detail',
                component: () => import('@/views/TopicDetailView.vue'),
            },
            {
                path: 'dashboard/:topic/:dashboardId',
                name: 'dashboard-detail',
                component: () => import('@/views/DashboardDetailView.vue'),
            },

            // Modul Kelola User Admin
            {
                path: 'admin/users',
                name: 'admin-users',
                component: () => import('@/views/admin/UserListView.vue'),
            },
            {
                path: 'admin/users/invite',
                name: 'admin-users-invite',
                component: () => import('@/views/admin/UserInviteView.vue'),
            },
            {
                path: 'admin/users/:id/edit',
                name: 'admin-users-edit',
                component: () => import('@/views/admin/UserEditView.vue'),
            },

            // Modul Kelola Parent (Kategori Sektor)
            {
                path: 'admin/parents',
                name: 'admin-parents',
                component: () => import('@/views/admin/ParentListView.vue'),
            },
            {
                path: 'admin/parents/create',
                name: 'admin-parents-create',
                component: () => import('@/views/admin/ParentCreateView.vue'),
            },
            {
                path: 'admin/parents/:id/edit',
                name: 'admin-parents-edit',
                component: () => import('@/views/admin/ParentEditView.vue'),
            },

            // Modul Kelola Dashboard
            {
                path: 'admin/dashboards',
                name: 'admin-dashboards',
                component: () => import('@/views/admin/DashboardListView.vue'),
            },
            {
                path: 'admin/dashboards/create',
                name: 'admin-dashboards-create',
                component: () => import('@/views/admin/DashboardCreateView.vue'),
            },
            {
                path: 'admin/dashboards/:id/edit',
                name: 'admin-dashboards-edit',
                component: () => import('@/views/admin/DashboardEditView.vue'),
            },

            // Modul Konfigurasi Superset
            {
                path: 'admin/superset',
                name: 'admin-superset',
                component: () => import('@/views/admin/SupersetConfigView.vue'),
            },
            {
                path: 'admin/superset/create',
                name: 'admin-superset-create',
                component: () => import('@/views/admin/SupersetCreateView.vue'),
            },
            {
                path: 'admin/superset/:id/edit',
                name: 'admin-superset-edit',
                component: () => import('@/views/admin/SupersetEditView.vue'),
            },

            // Modul Preferensi Akun
            {
                path: 'admin/preferences',
                name: 'admin-preferences',
                component: () => import('@/views/admin/PreferencesView.vue'),
            },

            // Modul Hak Akses & Peran
            {
                path: 'admin/roles',
                name: 'admin-roles',
                component: () => import('@/views/admin/RolesManagementView.vue'),
            },

            // Modul Log Aktivitas & Audit Trail
            {
                path: 'admin/logs',
                name: 'admin-logs',
                component: () => import('@/views/admin/AuditLogView.vue'),
            },
        ],
    },

    {
        path: '/:pathMatch(.*)*',
        redirect: '/dashboard',
    },
]

export const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router
