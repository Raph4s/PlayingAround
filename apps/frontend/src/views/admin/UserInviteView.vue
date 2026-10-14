<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { UserPlus, Mail, ChevronDown, ArrowLeft } from 'lucide-vue-next'

const router = useRouter()

const email = ref('')
const role = ref('')
const isLoading = ref(false)

function handleBack() {
  router.push('/admin/users')
}

function handleSubmit() {
  if (!email.value || !role.value) return
  isLoading.value = true

  setTimeout(() => {
    isLoading.value = false
    router.push('/admin/users')
  }, 400)
}
</script>

<template>
  <div class="space-y-6 max-w-4xl mx-auto selection:bg-blue-100 font-sans">
    <!-- Header & Breadcrumb (Figma: User Admin / Undangan baru) -->
    <div class="flex items-center justify-between">
      <div>
        <nav class="text-xs text-blue-600 font-medium mb-1">
          <router-link to="/admin/users" class="hover:underline">User Admin</router-link>
          <span class="text-slate-400 mx-1.5">/</span>
          <span class="text-slate-600">Undangan baru</span>
        </nav>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Invite User Admin</h1>
        <p class="text-xs text-slate-500 mt-1">
          Kirim akses awal dan tentukan lingkup kerja pengguna baru.
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

    <!-- Card Form Utama -->
    <div class="bg-white rounded-xl border border-slate-200 p-6 sm:p-8 shadow-2xs space-y-6">
      <div class="flex items-start gap-3 border-b border-slate-100 pb-5">
        <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
          <UserPlus class="h-4 w-4" />
        </div>
        <div>
          <h2 class="text-sm font-bold text-slate-900">Informasi pengguna</h2>
          <p class="text-xs text-slate-500 mt-0.5">Isi data sesuai identitas kedinasan pengguna.</p>
        </div>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-5">
        <!-- Input Email -->
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email</label>
          <input
            v-model="email"
            type="email"
            required
            placeholder="nama@jogjakota.go.id"
            class="w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-xs text-slate-800 placeholder:text-slate-400 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600 transition shadow-2xs"
          />
          <p class="text-[11px] text-slate-400 mt-1">Gunakan email kedinasan aktif.</p>
        </div>

        <!-- Input Role -->
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1.5">Role</label>
          <div class="relative">
            <select
              v-model="role"
              required
              class="w-full appearance-none rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 pr-8 text-xs text-slate-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600 transition shadow-2xs cursor-pointer"
            >
              <option value="" disabled selected>Pilih role pengguna</option>
              <option value="Admin">Admin</option>
              <option value="Admin Konten">Admin Konten</option>
              <option value="Analis Data">Analis Data</option>
              <option value="Editor">Editor</option>
              <option value="Viewer">Viewer</option>
            </select>
            <ChevronDown class="absolute right-3 top-3 h-3.5 w-3.5 text-slate-400 pointer-events-none" />
          </div>
        </div>

        <!-- Notification Banner Kuning -->
        <div class="flex items-center gap-2.5 p-3 rounded-lg bg-amber-50 border border-amber-200 text-amber-900 text-xs font-medium">
          <Mail class="h-4 w-4 shrink-0 text-amber-700" />
          <span>Sistem akan mengirim link aktivasi yang berlaku selama 24 jam melalui email.</span>
        </div>

        <!-- Tombol Aksi di Kanan Bawah -->
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
            <span v-if="!isLoading">Kirim Undangan</span>
            <span v-else>Mengirim...</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
