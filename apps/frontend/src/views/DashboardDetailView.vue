<script setup lang="ts">
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const topicParam = computed(() => (route.params.topic as string) || 'pariwisata')
const dashboardIdParam = computed(() => (route.params.dashboardId as string) || 'wisata-kunjungan')

// Title formatting helper
function formatSlug(slug: string): string {
  return slug
      .split('-')
      .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
      .join(' ')
}

const topicTitle = computed(() => formatSlug(topicParam.value))

const dashboardTitle = computed(() => {
  if (dashboardIdParam.value === 'wisata-kunjungan') return 'Kunjungan Wisatawan'
  return formatSlug(dashboardIdParam.value)
})

const isPublic = true
const description = 'Pantau tren kunjungan wisatawan dan lama tinggal di Daerah Istimewa Yogyakarta.'
const iframeStandinUrl = '/dashboard.png'
</script>

<template>
  <div class="space-y-5 max-w-7xl mx-auto selection:bg-blue-100">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-xs text-slate-500">
      <router-link to="/dashboard" class="text-blue-600 hover:underline">
        Dashboard
      </router-link>
      <span class="text-slate-300">/</span>
      <router-link :to="`/dashboard/${topicParam}`" class="hover:text-slate-800">
        {{ topicTitle }}
      </router-link>
      <span class="text-slate-300">/</span>
      <span class="text-slate-700 font-medium">
        {{ dashboardTitle }}
      </span>
    </nav>

    <!-- Header: Title, Public Badge, and Description -->
    <div>
      <div class="flex items-center gap-3">
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">
          {{ dashboardTitle }}
        </h1>
        <span
            v-if="isPublic"
            class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-sky-100 text-sky-700"
        >
          Publik
        </span>
        <span
            v-else
            class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700"
        >
          Privat
        </span>
      </div>
      <p class="text-xs text-slate-500 mt-1">
        {{ description }}
      </p>
    </div>

    <!-- Iframe Container Loading /dashboard.png -->
    <div class="w-full bg-white rounded-xl border border-slate-200 overflow-hidden shadow-2xs">
      <iframe
          :src="iframeStandinUrl"
          class="w-full h-[820px] border-0 bg-white"
          loading="lazy"
          allowfullscreen
      ></iframe>
    </div>
  </div>
</template>