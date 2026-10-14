<script setup lang="ts">
import { useRouter } from 'vue-router'
import logoUrl from '@/assets/logo.svg'

const router = useRouter()

function handleLogin() {
  router.push('/login')
}
</script>

<template>
  <header class="sticky top-0 z-40 bg-[#0e214d] text-white select-none shadow-xs border-b border-blue-950">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 h-16 flex items-center justify-between">
      <!-- Brand Identity -->
      <router-link to="/" class="flex items-center gap-3 hover:opacity-95 transition">
        <img :src="logoUrl" alt="Jogja Center" class="h-8 w-8 object-contain shrink-0" />
        <span class="font-bold text-sm tracking-wide text-white">Jogja Center</span>
      </router-link>

      <!-- Action Button -->
      <div class="flex items-center gap-3">
        <button
            type="button"
            @click="handleLogin"
            class="rounded-lg bg-[#2557c7] px-6 py-2 text-xs font-semibold text-white shadow-xs hover:bg-blue-700 active:bg-blue-800 transition cursor-pointer"
        >
          Login
        </button>
      </div>
    </div>
  </header>
</template>