<script setup lang="ts">
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { Eye, EyeOff, Check, X } from 'lucide-vue-next'
import logoUrl from '@/assets/logo.svg'

const router = useRouter()
const route = useRoute()

// Status alur: 'form' | 'success' | 'expired'
const flowState = ref<'form' | 'success' | 'expired'>('form')

const form = ref({
  fullName: '',
  username: '',
  password: '',
  confirmPassword: '',
})

const showPassword = ref(false)
const showConfirmPassword = ref(false)
const passwordMismatch = ref(false)
const isLoading = ref(false)

function handleSubmit() {
  passwordMismatch.value = false

  // Validasi kecocokan kata sandi (sesuai desain Sumber 5)
  if (form.value.password !== form.value.confirmPassword) {
    passwordMismatch.value = true
    return
  }

  isLoading.value = true

  // Simulasi pemanggilan endpoint backend Laravel nantinya:
  // await apiClient.post('/api/auth/activate', { token: route.query.token, ...form.value })
  setTimeout(() => {
    isLoading.value = false
    // Ganti ke state berhasil (sesuai desain Sumber 4)
    flowState.value = 'success'
  }, 500)
}

function handleGoToLogin() {
  router.push('/')
}

function handleContactAdmin() {
  // Arahkan ke kontak bantuan atau email admin
  window.location.href = 'mailto:admin@jogjakota.go.id'
}
</script>

<template>
  <div class="min-h-screen bg-[#f8fafc] flex items-center justify-center p-4 selection:bg-blue-100">
    <div class="w-full max-w-[440px] bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 sm:p-10 transition-all">

      <!-- Logo Jogja Center -->
      <div class="flex justify-center mb-6">
        <img :src="logoUrl" alt="Jogja Center Logo" class="h-10 w-10 object-contain" />
      </div>

      <!-- ================= STATE 1: FORM AKTIVASI AKUN ================= -->
      <div v-if="flowState === 'form'">
        <div class="mb-6">
          <h1 class="text-lg font-bold text-slate-900 tracking-tight">Aktivasi Akun</h1>
          <p class="text-xs text-slate-500 mt-1">Lengkapi data untuk mengaktifkan akun Anda</p>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-4">
          <!-- Nama Lengkap -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Lengkap</label>
            <input
                v-model="form.fullName"
                type="text"
                required
                placeholder="Masukkan nama lengkap"
                class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-xs outline-none transition focus:border-blue-600 focus:ring-1 focus:ring-blue-600 placeholder:text-slate-400 text-slate-800"
            />
          </div>

          <!-- Username -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Username</label>
            <input
                v-model="form.username"
                type="text"
                required
                placeholder="Masukkan username"
                class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 text-xs outline-none transition focus:border-blue-600 focus:ring-1 focus:ring-blue-600 placeholder:text-slate-400 text-slate-800"
            />
          </div>

          <!-- Password -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Password</label>
            <div class="relative">
              <input
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  required
                  minlength="8"
                  placeholder="Masukkan password"
                  class="w-full rounded-lg border border-slate-200 px-3.5 py-2.5 pr-10 text-xs outline-none transition focus:border-blue-600 focus:ring-1 focus:ring-blue-600 placeholder:text-slate-400 text-slate-800"
              />
              <button
                  type="button"
                  tabindex="-1"
                  class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 cursor-pointer"
                  @click="showPassword = !showPassword"
              >
                <EyeOff v-if="showPassword" class="h-4 w-4" />
                <Eye v-else class="h-4 w-4" />
              </button>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">
              Minimal 8 karakter, mengandung huruf besar dan angka
            </p>
          </div>

          <!-- Konfirmasi Password -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Konfirmasi Password</label>
            <div class="relative">
              <input
                  v-model="form.confirmPassword"
                  :type="showConfirmPassword ? 'text' : 'password'"
                  required
                  placeholder="Ulangi password"
                  :class="[
                  'w-full rounded-lg border px-3.5 py-2.5 pr-10 text-xs outline-none transition placeholder:text-slate-400 text-slate-800',
                  passwordMismatch
                    ? 'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500'
                    : 'border-slate-200 focus:border-blue-600 focus:ring-1 focus:ring-blue-600'
                ]"
              />
              <button
                  type="button"
                  tabindex="-1"
                  class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 cursor-pointer"
                  @click="showConfirmPassword = !showConfirmPassword"
              >
                <EyeOff v-if="showConfirmPassword" class="h-4 w-4" />
                <Eye v-else class="h-4 w-4" />
              </button>
            </div>
            <!-- Pesan error kata sandi tidak sama (Sumber 5) -->
            <p v-if="passwordMismatch" class="text-[11px] text-red-500 mt-1">
              Kata sandi tidak sama
            </p>
          </div>

          <!-- Submit Button -->
          <button
              type="submit"
              :disabled="isLoading"
              class="w-full mt-2 rounded-lg bg-[#2557c7] hover:bg-blue-700 active:bg-blue-800 text-white py-2.5 text-xs font-medium transition shadow-2xs cursor-pointer disabled:opacity-70"
          >
            <span v-if="!isLoading">Aktivasi Akun</span>
            <span v-else>Memproses...</span>
          </button>
        </form>
      </div>

      <!-- ================= STATE 2: BERHASIL DIAKTIFKAN ================= -->
      <div v-else-if="flowState === 'success'" class="text-center py-4">
        <div class="w-14 h-14 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-5">
          <Check class="h-6 w-6 stroke-[2.5]" />
        </div>

        <h2 class="text-base font-bold text-slate-900">Akun Berhasil Diaktifkan</h2>
        <p class="text-xs text-slate-500 mt-1.5 max-w-[280px] mx-auto leading-relaxed">
          Akun Anda sudah aktif. Silakan masuk untuk melanjutkan.
        </p>

        <button
            @click="handleGoToLogin"
            class="w-full mt-6 rounded-lg bg-[#2557c7] hover:bg-blue-700 active:bg-blue-800 text-white py-2.5 text-xs font-medium transition shadow-2xs cursor-pointer"
        >
          Masuk
        </button>
      </div>

      <!-- ================= STATE 3: AKTIVASI GAGAL / KEDALUWARSA ================= -->
      <div v-else-if="flowState === 'expired'" class="text-center py-4">
        <div class="w-14 h-14 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-5">
          <X class="h-6 w-6 stroke-[2.5]" />
        </div>

        <h2 class="text-base font-bold text-slate-900">Aktivasi Akun Gagal</h2>
        <p class="text-xs text-slate-500 mt-1.5 max-w-[280px] mx-auto leading-relaxed">
          Tautan aktivasi sudah tidak berlaku atau telah kedaluwarsa.
        </p>

        <button
            @click="handleContactAdmin"
            class="w-full mt-6 rounded-lg bg-[#2557c7] hover:bg-blue-700 active:bg-blue-800 text-white py-2.5 text-xs font-medium transition shadow-2xs cursor-pointer"
        >
          Hubungi Administrator
        </button>
      </div>

    </div>
  </div>
</template>