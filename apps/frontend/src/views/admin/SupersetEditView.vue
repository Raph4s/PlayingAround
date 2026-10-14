<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { ArrowLeft, CheckCircle2, Trash2, RefreshCw } from 'lucide-vue-next'

const router = useRouter()

const host = ref('https://bi.jogjakota.go.id')
const username = ref('svc_jogjacenter_prod')
const password = ref('••••••••••••••••••••••')

const isTesting = ref(false)
const testStatus = ref<'none' | 'success' | 'failed'>('none')
const isSaving = ref(false)

function handleTestConnection() {
  isTesting.value = true
  testStatus.value = 'none'

  setTimeout(() => {
    isTesting.value = false
    testStatus.value = 'success'
  }, 600)
}

function handleSave() {
  isSaving.value = true
  setTimeout(() => {
    isSaving.value = false
    router.push('/admin/superset')
  }, 400)
}

function handleDelete() {
  if (confirm(`Apakah Anda yakin ingin menghapus koneksi "${host.value}"?`)) {
    router.push('/admin/superset')
  }
}
</script>

<template>
  <div class="space-y-6 max-w-4xl mx-auto selection:bg-blue-100 font-sans">
    <!-- Header (Figma: Edit koneksi Superset) -->
    <div class="flex items-center justify-between">
      <div>
        <nav class="text-xs text-blue-600 font-medium mb-1">
          <router-link to="/admin/superset" class="hover:underline">Superset</router-link>
          <span class="text-slate-400 mx-1.5">/</span>
          <span class="text-slate-600">Edit</span>
        </nav>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit koneksi Superset</h1>
        <p class="text-xs text-slate-500 mt-1">
          Perbarui kredensial dan pengaturan sinkronisasi koneksi produksi.
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
          to="/admin/superset"
          class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer"
        >
          <ArrowLeft class="h-3.5 w-3.5" />
          <span>Kembali</span>
        </router-link>
      </div>
    </div>

    <!-- Form Container -->
    <form @submit.prevent="handleSave" class="bg-white rounded-xl border border-slate-200 p-6 sm:p-8 shadow-2xs space-y-5">
      <!-- Test Connection Feedback Banner -->
      <div
        v-if="testStatus === 'success'"
        class="flex items-center gap-2 p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium"
      >
        <CheckCircle2 class="h-4 w-4 text-emerald-600 shrink-0" />
        <span>Koneksi berhasil terhubung dengan host server Superset.</span>
      </div>

      <!-- Host Server Input -->
      <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
          Host Server <span class="text-rose-500">*</span>
        </label>
        <input
          v-model="host"
          type="text"
          required
          class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-lg text-xs font-mono text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white transition"
        />
      </div>

      <!-- Username Input -->
      <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
          Username <span class="text-rose-500">*</span>
        </label>
        <input
          v-model="username"
          type="text"
          required
          class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white transition"
        />
      </div>

      <!-- Password Input -->
      <div>
        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
          Password <span class="text-rose-500">*</span>
        </label>
        <input
          v-model="password"
          type="password"
          required
          class="w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white transition"
        />
      </div>

      <!-- Test Connection Row -->
      <div class="pt-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-3.5 rounded-lg bg-slate-50 border border-slate-100">
        <span class="text-xs text-slate-500">
          Lakukan Test Connection sebelum menyimpan perubahan.
        </span>
        <button
          type="button"
          @click="handleTestConnection"
          :disabled="isTesting"
          class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg border border-slate-200 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-50 transition cursor-pointer self-start sm:self-auto disabled:opacity-60"
        >
          <RefreshCw class="h-3.5 w-3.5" :class="{ 'animate-spin': isTesting }" />
          <span>{{ isTesting ? 'Menguji...' : 'Test Connection' }}</span>
        </button>
      </div>

      <!-- Actions -->
      <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
        <router-link
          to="/admin/superset"
          class="px-5 py-2.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer"
        >
          Batal
        </router-link>
        <button
          type="submit"
          :disabled="isSaving"
          class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg bg-[#2557c7] text-white text-xs font-semibold hover:bg-blue-700 active:bg-blue-800 shadow-2xs transition cursor-pointer disabled:opacity-70"
        >
          <span>{{ isSaving ? 'Menyimpan...' : 'Simpan Perubahan' }}</span>
        </button>
      </div>
    </form>
  </div>
</template>
