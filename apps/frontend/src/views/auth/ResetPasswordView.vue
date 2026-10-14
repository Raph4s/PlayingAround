<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { Eye, EyeOff, Check, ArrowLeft, CheckCircle2, Lock, ShieldCheck } from 'lucide-vue-next'
import logoUrl from '@/assets/logo.svg'
import { authService } from '@/services/authService'

const router = useRouter()

const password = ref('')
const confirmPassword = ref('')
const showPassword = ref(false)
const showConfirmPassword = ref(false)

const isLoading = ref(false)
const errorMessage = ref('')
const isSuccess = ref(false)

// Criteria checks matching Figma 1:2283
const hasMinLength = computed(() => password.value.length >= 8)
const hasCombinedCaseAndNumber = computed(() => {
  const hasUpper = /[A-Z]/.test(password.value)
  const hasLower = /[a-z]/.test(password.value)
  const hasNum = /[0-9]/.test(password.value)
  return hasUpper && hasLower && hasNum
})

const passwordMismatch = computed(() => {
  return confirmPassword.value.length > 0 && password.value !== confirmPassword.value
})

const isFormValid = computed(() => {
  return (
    hasMinLength.value &&
    hasCombinedCaseAndNumber.value &&
    password.value === confirmPassword.value &&
    confirmPassword.value.length > 0
  )
})

async function handleResetPassword() {
  errorMessage.value = ''

  if (!hasMinLength.value || !hasCombinedCaseAndNumber.value) {
    // Exact Figma 1:3892 error message
    errorMessage.value =
      'Password minimal 8 karakter dan harus mengandung huruf besar, huruf kecil, serta angka.'
    return
  }

  if (password.value !== confirmPassword.value) {
    return
  }

  const resetId = sessionStorage.getItem('reset_id')
  const resetToken = sessionStorage.getItem('reset_token')

  isLoading.value = true

  try {
    if (resetId && resetToken) {
      await authService.resetPassword({
        id: resetId,
        password: password.value,
        bearerToken: resetToken,
      })
    }
    isLoading.value = false
    isSuccess.value = true

    // Clean reset session keys
    sessionStorage.removeItem('reset_id')
    sessionStorage.removeItem('reset_token')
    sessionStorage.removeItem('reset_email')
    sessionStorage.removeItem('reset_code_verified')
  } catch (error: any) {
    isLoading.value = false
    errorMessage.value =
      error.response?.data?.message || 'Gagal mengubah password. Permintaan mungkin kedaluwarsa.'
  }
}

function toggleErrorPreview() {
  if (errorMessage.value) {
    errorMessage.value = ''
  } else {
    errorMessage.value =
      'Password minimal 8 karakter dan harus mengandung huruf besar, huruf kecil, serta angka.'
  }
}
</script>

<template>
  <div class="min-h-screen bg-[#F8FAFC] flex flex-col items-center justify-center p-4 selection:bg-blue-100 font-sans">
    <!-- Auth Card (Exact Figma 1:2253, 1:3892: 440px standalone centered card) -->
    <div class="w-full max-w-[440px] bg-white rounded-xl border border-slate-200/80 shadow-[0_4px_16px_rgba(0,0,0,0.05)] p-8 sm:p-10 transition-all">
      <!-- Success State Modal/Card -->
      <div v-if="isSuccess" class="space-y-5 text-center py-4">
        <div class="w-14 h-14 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto border border-emerald-200">
          <CheckCircle2 class="h-8 w-8" />
        </div>

        <div>
          <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Password Berhasil Diperbarui</h1>
          <p class="text-xs text-slate-500 mt-2 leading-relaxed max-w-sm mx-auto">
            Password akun Anda telah berhasil diubah. Silakan masuk kembali menggunakan password baru Anda.
          </p>
        </div>

        <router-link
          to="/login"
          class="inline-block w-full py-2.5 bg-[#0775C5] hover:bg-[#0662a6] text-white rounded-lg text-xs font-semibold transition shadow-xs cursor-pointer"
        >
          Masuk ke akun
        </router-link>
      </div>

      <!-- Normal / Form State (Steps 1, 2, 3) -->
      <div v-else>
        <!-- Top Brand Logo -->
        <div class="flex justify-center mb-6">
          <router-link to="/" class="inline-block select-none">
            <img :src="logoUrl" alt="Jogja Center" class="h-10 w-10 object-contain" />
          </router-link>
        </div>

        <!-- Reset Progress Stepper (Step 3 active) -->
        <div class="grid grid-cols-3 gap-3 mb-6">
          <div>
            <div class="h-[3px] rounded-full bg-[#0775C5]"></div>
            <p class="text-xs font-semibold text-[#0775C5] mt-2">1. Email</p>
          </div>
          <div>
            <div class="h-[3px] rounded-full bg-[#0775C5]"></div>
            <p class="text-xs font-semibold text-[#0775C5] mt-2">2. Verifikasi</p>
          </div>
          <div>
            <div class="h-[3px] rounded-full bg-[#0775C5]"></div>
            <p class="text-xs font-semibold text-[#0775C5] mt-2">3. Password baru</p>
          </div>
        </div>

        <!-- Step Symbol Badge (Exact Figma: 52x52 rounded-xl bg-[#F0F7FD] with lock icon) -->
        <div class="w-[52px] h-[52px] rounded-xl bg-[#F0F7FD] flex items-center justify-center text-[#0775C5] mb-5">
          <Lock class="w-6 h-6 stroke-[1.75]" />
        </div>

        <!-- Form Introduction -->
        <div>
          <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Buat password baru</h1>
          <p class="text-sm text-slate-500 mt-1.5 leading-relaxed">
            Buat password yang kuat dan aman untuk melindungi akun Anda.
          </p>
        </div>

        <!-- Error Banner (Figma 1:3892) -->
        <transition
          enter-active-class="transition duration-200 ease-out"
          enter-from-class="opacity-0 -translate-y-1"
          enter-to-class="opacity-100 translate-y-0"
        >
          <div
            v-if="errorMessage"
            class="flex items-start gap-2.5 p-3 rounded-lg bg-[#FFF3F0] border border-[#FFC4BC] text-[#D33027] text-xs font-medium mt-4"
          >
            <div class="w-5 h-5 rounded-full bg-[#D33027] text-white flex items-center justify-center font-bold text-xs shrink-0 select-none mt-0.5">
              !
            </div>
            <span class="leading-relaxed">{{ errorMessage }}</span>
          </div>
        </transition>

        <!-- Form Section -->
        <form @submit.prevent="handleResetPassword" class="mt-5 space-y-4">
          <!-- Password Input -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
              Password baru
            </label>
            <div class="relative">
              <input
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                required
                placeholder="Minimal 8 karakter"
                :class="[
                  'w-full rounded-lg border px-3.5 py-2.5 pr-10 text-xs outline-none transition',
                  errorMessage
                    ? 'border-red-300 focus:border-red-500 focus:ring-1 focus:ring-red-500 bg-red-50/20'
                    : 'border-slate-200 focus:border-[#0775C5] focus:ring-1 focus:ring-[#0775C5] text-slate-800 placeholder:text-slate-400',
                ]"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition cursor-pointer"
              >
                <EyeOff v-if="showPassword" class="h-4 w-4" />
                <Eye v-else class="h-4 w-4" />
              </button>
            </div>
          </div>

          <!-- Password Checklist Criteria (Exact Figma 1:2283) -->
          <div class="p-3 bg-slate-50/80 rounded-lg border border-slate-100 space-y-2 text-xs">
            <div class="flex items-center gap-2">
              <div
                :class="[
                  'w-4 h-4 rounded-full flex items-center justify-center text-[10px] transition',
                  hasMinLength ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-400',
                ]"
              >
                <Check class="h-2.5 w-2.5 stroke-[3]" />
              </div>
              <span :class="hasMinLength ? 'text-slate-800 font-medium' : 'text-slate-500'">
                Minimal 8 karakter
              </span>
            </div>

            <div class="flex items-center gap-2">
              <div
                :class="[
                  'w-4 h-4 rounded-full flex items-center justify-center text-[10px] transition',
                  hasCombinedCaseAndNumber ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-400',
                ]"
              >
                <Check class="h-2.5 w-2.5 stroke-[3]" />
              </div>
              <span :class="hasCombinedCaseAndNumber ? 'text-slate-800 font-medium' : 'text-slate-500'">
                Kombinasi huruf besar, kecil, dan angka
              </span>
            </div>
          </div>

          <!-- Confirm Password Input -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
              Konfirmasi password baru
            </label>
            <div class="relative">
              <input
                v-model="confirmPassword"
                :type="showConfirmPassword ? 'text' : 'password'"
                required
                placeholder="Ketik ulang password baru"
                :class="[
                  'w-full rounded-lg border px-3.5 py-2.5 pr-10 text-xs outline-none transition',
                  passwordMismatch
                    ? 'border-red-300 focus:border-red-500 focus:ring-1 focus:ring-red-500 bg-red-50/20'
                    : 'border-slate-200 focus:border-[#0775C5] focus:ring-1 focus:ring-[#0775C5] text-slate-800 placeholder:text-slate-400',
                ]"
              />
              <button
                type="button"
                @click="showConfirmPassword = !showConfirmPassword"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition cursor-pointer"
              >
                <EyeOff v-if="showConfirmPassword" class="h-4 w-4" />
                <Eye v-else class="h-4 w-4" />
              </button>
            </div>
            <p v-if="passwordMismatch" class="text-xs text-rose-500 mt-1.5 font-medium">
              Password tidak sama.
            </p>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="isLoading || !isFormValid"
            class="w-full rounded-lg bg-[#0775C5] hover:bg-[#0662a6] active:bg-[#05538d] text-white py-2.5 text-xs font-semibold transition shadow-xs cursor-pointer disabled:opacity-50 mt-6"
          >
            <span v-if="!isLoading">Simpan kata sandi</span>
            <span v-else>Menyimpan...</span>
          </button>

          <!-- Back to Login -->
          <div class="text-center pt-2">
            <router-link
              to="/login"
              class="inline-flex items-center gap-1.5 text-xs text-[#0775C5] hover:text-[#0662a6] font-medium hover:underline"
            >
              <ArrowLeft class="h-3.5 w-3.5" />
              <span>Kembali ke masuk</span>
            </router-link>
          </div>
        </form>

        <!-- Status Preview Toggle (Figma 1:3892 preview) -->
        <div class="mt-6 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
          <span>Pratinjau status:</span>
          <button
            type="button"
            @click="toggleErrorPreview"
            class="underline hover:text-slate-600 cursor-pointer"
          >
            {{ errorMessage ? 'Normal (1:2253)' : 'Kriteria belum cocok (1:3892)' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Security Footer -->
    <div class="mt-6 flex items-center justify-center gap-2 text-xs text-slate-500">
      <ShieldCheck class="w-4 h-4 text-slate-400 shrink-0" />
      <span>Pemulihan akun yang aman dan terlindungi</span>
    </div>
  </div>
</template>
