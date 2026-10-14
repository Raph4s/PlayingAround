<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { Lock } from 'lucide-vue-next'

const route = useRoute()

const topicParam = computed(() => (route.params.topic as string) || 'pariwisata')
const dashboardIdParam = computed(() => (route.params.dashboardId as string) || 'kunjungan-wisatawan')

function formatSlug(slug: string): string {
  return slug
    .split('-')
    .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
    .join(' ')
}

const topicTitle = computed(() => formatSlug(topicParam.value))

const dashboardTitle = computed(() => {
  if (dashboardIdParam.value === 'kunjungan-wisatawan' || dashboardIdParam.value === 'wisata-kunjungan') {
    return 'Kunjungan Wisatawan'
  }
  return formatSlug(dashboardIdParam.value)
})

const isPublic = computed(() => {
  const privates = ['okupansi-hotel', 'retribusi-wisata', 'audit-pajak-wisata', 'kerjasama-swasta', 'internal-dinas', 'farmasi-obat', 'anggaran']
  return !privates.some((p) => dashboardIdParam.value.toLowerCase().includes(p))
})

const description = computed(() => {
  if (dashboardTitle.value === 'Kunjungan Wisatawan') {
    return 'Pantau tren kunjungan wisatawan dan lama tinggal di Daerah Istimewa Yogyakarta.'
  }
  return `Pantau visualisasi dan metrik analitik ${dashboardTitle.value} di Daerah Istimewa Yogyakarta.`
})

const iframeStandinUrl = '/dashboard.png'
</script>

<template>
  <div class="space-y-6 max-w-7xl mx-auto selection:bg-blue-100">
    <!-- Breadcrumb matching Figma Node 1:5794 -->
    <div class="flex items-center gap-2 text-sm font-medium">
      <router-link
        to="/dashboard"
        class="text-[#0775c5] hover:underline"
      >
        Dashboard
      </router-link>
      <span class="text-slate-400">/</span>
      <router-link
        :to="`/dashboard/${topicParam}`"
        class="text-slate-500 hover:text-slate-800"
      >
        {{ topicTitle }}
      </router-link>
      <span class="text-slate-400">/</span>
      <span class="text-slate-500">{{ dashboardTitle }}</span>
    </div>

    <!-- Detail Heading matching Figma Node 1:5803 -->
    <div class="space-y-1">
      <div class="flex items-center gap-3">
        <h1 class="text-[28px] font-semibold leading-[36px] tracking-tight text-slate-900">
          {{ dashboardTitle }}
        </h1>
        <span
          v-if="isPublic"
          class="bg-[#d6e9fd] text-[#0775c5] text-xs px-2.5 py-0.5 rounded-full font-normal"
        >
          Publik
        </span>
        <span
          v-else
          class="bg-[#f1f5f9] text-[#475569] text-xs px-2.5 py-0.5 rounded-full font-normal inline-flex items-center gap-1"
        >
          <Lock class="size-3" />
          Privat
        </span>
      </div>
      <p class="text-[14px] leading-[22px] text-slate-500">
        {{ description }}
      </p>
    </div>

    <!-- Embedded Dashboard Container matching Figma Node 1:5810 -->
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
