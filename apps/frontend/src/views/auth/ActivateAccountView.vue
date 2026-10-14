<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { Eye, EyeOff, Check, X, AlertCircle } from 'lucide-vue-next'
import logoUrl from '@/assets/logo.svg'

const router = useRouter()

// View states matching Figma: 'form' (1:2604/1:2616) | 'success' (1:2628) | 'expired' (1:2637)
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

  // Validate passwords match (Figma 1:2616: Password tidak sama)
  if (form.value.password !== form.value.confirmPassword) {
    passwordMismatch.value = true
    return
  }

  isLoading.value = true

  setTimeout(() => {
    // Save newly activated user credentials into localStorage for local login testing
    const activatedUser = {
      name: form.value.fullName,
      username: form.value.username,
      email: `${form.value.username}@jogjakota.go.id`,
      password: form.value.password,
      role: 'Admin',
      initials: form.value.fullName.slice(0, 2).toUpperCase(),
    }
    localStorage.setItem('registered_user', JSON.stringify(activatedUser))

    isLoading.value = false
    flowState.value = 'success'
  }, 400)
}

function handleGoToLogin() {
  router.push('/login')
}

function handleContactAdmin() {
  window.location.href = 'mailto:admin@jogjakota.go.id'
}
</script>

<template>
  <div class="min-h-screen bg-[#f8fafc] flex items-center justify-center p-4 selection:bg-blue-100 font-sans">
    <div class="w-full max-w-[440px] bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 sm:p-10 transition-all">
      <!-- Logo Jogja Center -->
      <div class="flex justify-center mb-6">
        <img :src="logoUrl" alt="Jogja Center Logo" class="h-10 w-10 object-contain" />
      </div>

      <!-- ================= 1. FORM STATE (Figma 1:2604 & 1:2616) ================= -->
      <div v-if="flowState === 'form'">
        <div class="mb-6">
          <h1 class="text-xl font-bold text-slate-900 tracking-tight">Aktivasi Akun</h1>
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
              Min. 8 karakter, huruf besar, huruf kecil, dan angka
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
                    ? 'border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500 bg-red-50/20'
                    : 'border-slate-200 focus:border-blue-600 focus:ring-1 focus:ring-blue-600',
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
            <!-- Inline Error (Figma 1:2616: Password tidak sama) -->
            <p v-if="passwordMismatch" class="text-[11px] text-red-500 mt-1 font-medium">
              Password tidak sama
            </p>
          </div>

          <!-- Tombol Aktivasi Akun -->
          <button
            type="submit"
            :disabled="isLoading"
            class="w-full mt-2 rounded-lg bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white py-2.5 text-xs font-semibold transition shadow-xs cursor-pointer disabled:opacity-70"
          >
            <span v-if="!isLoading">Aktivasi Akun</span>
            <span v-else>Memproses...</span>
          </button>
        </form>

        <!-- Preview Switcher for states -->
        <div class="mt-6 pt-4 border-t border-slate-100 flex justify-center gap-3 text-[11px] text-slate-400">
          <span>Pratinjau status:</span>
          <button type="button" @click="flowState = 'success'" class="underline hover:text-blue-600 cursor-pointer">
            Berhasil (1:2628)
          </button>
          <button type="button" @click="flowState = 'expired'" class="underline hover:text-red-600 cursor-pointer">
            Gagal / Kedaluwarsa (1:2637)
          </button>
        </div>
      </div>

      <!-- ================= 2. SUCCESS STATE (Figma 1:2628) ================= -->
      <div v-else-if="flowState === 'success'" class="text-center py-4 space-y-4">
        <div class="w-14 h-14 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto">
          <Check class="h-6 w-6 stroke-[2.5]" />
        </div>

        <div>
          <h2 class="text-base font-bold text-slate-900">Akun Berhasil Diaktifkan</h2>
          <p class="text-xs text-slate-500 mt-1.5 max-w-[280px] mx-auto leading-relaxed">
            Akun Anda sudah aktif. Silakan masuk untuk melanjutkan.
          </p>
        </div>

        <button
          type="button"
          @click="handleGoToLogin"
          class="w-full mt-4 rounded-lg bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white py-2.5 text-xs font-semibold transition shadow-xs cursor-pointer"
        >
          Masuk
        </button>
      </div>

      <!-- ================= 3. EXPIRED STATE (Figma 1:2637) ================= -->
      <div v-else-if="flowState === 'expired'" class="text-center py-4 space-y-4">
        <div class="w-14 h-14 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto">
          <X class="h-6 w-6 stroke-[2.5]" />
        </div>

        <div>
          <h2 class="text-base font-bold text-slate-900">Aktivasi Akun Gagal</h2>
          <p class="text-xs text-slate-500 mt-1.5 max-w-[280px] mx-auto leading-relaxed">
            Tautan aktivasi sudah tidak berlaku atau telah kedaluwarsa.
          </p>
        </div>

        <button
          type="button"
          @click="handleContactAdmin"
          class="w-full mt-4 rounded-lg bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white py-2.5 text-xs font-semibold transition shadow-xs cursor-pointer"
        >
          Hubungi Administrator
        </button>

        <button
          type="button"
          @click="flowState = 'form'"
          class="w-full text-center text-xs text-slate-500 hover:text-slate-800 py-1 transition cursor-pointer"
        >
          Kembali ke form
        </button>
      </div>
    </div>
  </div>
</template>
