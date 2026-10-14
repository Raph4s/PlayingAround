<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { ArrowLeft, ChevronDown } from 'lucide-vue-next'

const router = useRouter()

const form = ref({
  name: '',
  description: '',
  publication: 'Draft',
})

const isLoading = ref(false)

function handleBack() {
  router.push('/admin/parents')
}

function handleSave() {
  if (!form.value.name.trim()) return
  isLoading.value = true

  setTimeout(() => {
    isLoading.value = false
    router.push('/admin/parents')
  }, 350)
}
</script>

<template>
  <div class="space-y-6 max-w-4xl mx-auto selection:bg-blue-100 font-sans">
    <!-- Header & Breadcrumbs (Figma: Kelola Kategori / Baru) -->
    <div class="flex items-center justify-between">
      <div>
        <nav class="text-xs text-blue-600 font-medium mb-1">
          <router-link to="/admin/parents" class="hover:underline">Kelola Kategori</router-link>
          <span class="text-slate-400 mx-1.5">/</span>
          <span class="text-slate-600">Baru</span>
        </nav>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Tambah Kategori</h1>
        <p class="text-xs text-slate-500 mt-1">
          Lengkapi nama, deskripsi, dan status kategori untuk pengelompokan dashboard.
        </p>
      </div>

      <button
        type="button"
        @click="handleBack"
        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer"
      >
        <ArrowLeft class="h-3.5 w-3.5" />
        <span>Kembali</span>
      </button>
    </div>

    <!-- Form Container (Exact Figma 1:7439) -->
    <form @submit.prevent="handleSave" class="bg-white rounded-xl border border-slate-200 p-6 sm:p-8 shadow-2xs space-y-5">
      <!-- Nama -->
      <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama</label>
        <input
          v-model="form.name"
          type="text"
          required
          placeholder="Masukkan nama kategori"
          class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-xs text-slate-800 placeholder:text-slate-400 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600 transition shadow-2xs"
        />
      </div>

      <!-- Deskripsi -->
      <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi</label>
        <textarea
          v-model="form.description"
          rows="4"
          maxlength="240"
          required
          placeholder="Tuliskan deskripsi dan cakupan informasi kategori."
          class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-xs text-slate-800 placeholder:text-slate-400 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600 transition shadow-2xs resize-none"
        ></textarea>
        <p class="text-[11px] text-slate-400 mt-1">
          Maksimal 240 karakter untuk ringkasan kategori.
        </p>
      </div>

      <!-- Publikasi -->
      <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Publikasi</label>
        <div class="relative">
          <select
            v-model="form.publication"
            class="w-full appearance-none rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 pr-8 text-xs text-slate-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600 transition shadow-2xs cursor-pointer"
          >
            <option value="Draft">Draft</option>
            <option value="Public">Public</option>
            <option value="Private">Private</option>
          </select>
          <ChevronDown class="absolute right-3 top-3 h-3.5 w-3.5 text-slate-400 pointer-events-none" />
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
        <button
          type="button"
          @click="handleBack"
          class="rounded-lg border border-slate-200 px-5 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer"
        >
          Batal
        </button>

        <button
          type="submit"
          :disabled="isLoading"
          class="rounded-lg bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white px-5 py-2.5 text-xs font-semibold transition shadow-2xs cursor-pointer disabled:opacity-70"
        >
          <span v-if="!isLoading">Tambah Kategori</span>
          <span v-else>Menyimpan...</span>
        </button>
      </div>
    </form>
  </div>
</template>
