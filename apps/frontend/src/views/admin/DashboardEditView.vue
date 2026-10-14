<script setup lang="ts">
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { ArrowLeft, Trash2 } from 'lucide-vue-next'

const router = useRouter()
const route = useRoute()

const isSubmitting = ref(false)

const form = ref({
  dashboard: 'Indikator Ekonomi Kota',
  kategori: 'Ekonomi',
  deskripsi: 'Data pertumbuhan ekonomi tahunan, inflasi, dan indikator PDRB Kota Yogyakarta.',
  sumberData: 'superset123',
  publikasi: 'Public',
})

const categories = [
  'Ekonomi',
  'Pariwisata',
  'Kesehatan',
  'Pendidikan',
  'Transportasi',
  'Evaluasi Kinerja Khusus',
]

const supersetConnections = [
  'superset123',
  'superset-wisata',
  'superset-internal',
]

function handleSave() {
  isSubmitting.value = true
  setTimeout(() => {
    isSubmitting.value = false
    router.push('/admin/dashboards')
  }, 400)
}

function handleDelete() {
  if (confirm(`Apakah Anda yakin ingin menghapus dashboard "${form.value.dashboard}"?`)) {
    router.push('/admin/dashboards')
  }
}
</script>

<template>
  <div class="space-y-6 max-w-4xl mx-auto selection:bg-blue-100 font-sans">
    <!-- Header & Breadcrumbs (Figma: Kelola Dashboard / Edit) -->
    <div class="flex items-center justify-between">
      <div>
        <nav class="text-xs text-blue-600 font-medium mb-1">
          <router-link to="/admin/dashboards" class="hover:underline">Kelola Dashboard</router-link>
          <span class="text-slate-400 mx-1.5">/</span>
          <span class="text-slate-600">Edit</span>
        </nav>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Dashboard</h1>
        <p class="text-xs text-slate-500 mt-1">
          Perbarui informasi dashboard, kategori, deskripsi, sumber data, dan status publikasi.
        </p>
      </div>

      <div class="flex items-center gap-2">
        <button
          type="button"
          @click="handleDelete"
          class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-rose-200 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition cursor-pointer"
        >
          <Trash2 class="h-3.5 w-3.5" />
          <span>Hapus</span>
        </button>
        <router-link
          to="/admin/dashboards"
          class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer"
        >
          <ArrowLeft class="h-3.5 w-3.5" />
          <span>Kembali</span>
        </router-link>
      </div>
    </div>

    <!-- Form Container -->
    <form @submit.prevent="handleSave" class="bg-white rounded-xl border border-slate-200 p-6 sm:p-8 shadow-2xs space-y-6">
      <div class="space-y-4">
        <!-- Field 1: Dashboard (Judul) -->
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1.5">
            Dashboard <span class="text-rose-500">*</span>
          </label>
          <input
            v-model="form.dashboard"
            type="text"
            required
            placeholder="Masukkan judul dashboard"
            class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white transition"
          />
        </div>

        <!-- Field 2: Kategori -->
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1.5">
            Kategori <span class="text-rose-500">*</span>
          </label>
          <select
            v-model="form.kategori"
            required
            class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white transition cursor-pointer"
          >
            <option v-for="cat in categories" :key="cat" :value="cat">
              {{ cat }}
            </option>
          </select>
        </div>

        <!-- Field 3: Deskripsi -->
        <div>
          <div class="flex items-center justify-between mb-1.5">
            <label class="block text-xs font-semibold text-slate-700">
              Deskripsi
            </label>
            <span class="text-[11px] text-slate-400">Maksimal 240 karakter untuk ringkasan katalog.</span>
          </div>
          <textarea
            v-model="form.deskripsi"
            maxlength="240"
            rows="3"
            placeholder="Tuliskan ringkasan isi, tujuan, dan cakupan data dashboard."
            class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white transition resize-none"
          ></textarea>
        </div>

        <!-- Field 4: Sumber Data -->
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1.5">
            Sumber Data <span class="text-rose-500">*</span>
          </label>
          <select
            v-model="form.sumberData"
            required
            class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white transition cursor-pointer"
          >
            <option v-for="conn in supersetConnections" :key="conn" :value="conn">
              {{ conn }}
            </option>
          </select>
        </div>

        <!-- Field 5: Publikasi -->
        <div>
          <div class="flex items-center justify-between mb-1.5">
            <label class="block text-xs font-semibold text-slate-700">
              Publikasi
            </label>
            <span class="text-[11px] text-slate-400">Pilihan: Private / Public / Draft</span>
          </div>
          <select
            v-model="form.publikasi"
            class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white transition cursor-pointer"
          >
            <option value="Draft">Draft</option>
            <option value="Public">Public</option>
            <option value="Private">Private</option>
          </select>
        </div>
      </div>

      <!-- Actions -->
      <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
        <router-link
          to="/admin/dashboards"
          class="px-5 py-2.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer"
        >
          Batal
        </router-link>
        <button
          type="submit"
          :disabled="isSubmitting"
          class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg bg-[#2557c7] text-white text-xs font-semibold hover:bg-blue-700 active:bg-blue-800 shadow-2xs transition cursor-pointer disabled:opacity-70"
        >
          <span>{{ isSubmitting ? 'Menyimpan...' : 'Simpan Perubahan' }}</span>
        </button>
      </div>
    </form>
  </div>
</template>
