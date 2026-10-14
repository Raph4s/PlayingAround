<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { ArrowLeft, KeyRound, ShieldCheck } from 'lucide-vue-next'
import logoUrl from '@/assets/logo.svg'

const router = useRouter()

const email = ref('')
const isLoading = ref(false)
const errorMessage = ref('')

function handleRequestReset() {
  errorMessage.value = ''

  if (!email.value.trim()) {
    errorMessage.value = 'Silakan masukkan alamat email yang terdaftar.'
    return
  }

  // Basic email syntax check
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
    errorMessage.value = 'Format email tidak valid.'
    return
  }

  // Mock test error check (Figma 1:2475)
  if (email.value.toLowerCase().includes('error') || email.value.toLowerCase().includes('tidak')) {
    errorMessage.value = 'Email tidak terdaftar.'
    return
  }

  isLoading.value = true

  setTimeout(() => {
    isLoading.value = false
    sessionStorage.setItem('reset_email', email.value)
    router.push('/verify-code')
  }, 450)
}

function toggleErrorPreview() {
  if (errorMessage.value) {
    errorMessage.value = ''
  } else {
    errorMessage.value = 'Email tidak terdaftar.'
  }
}
</script>

<template>
  <div class="min-h-screen bg-[#F8FAFC] flex flex-col items-center justify-center p-4 selection:bg-blue-100 font-sans">
    <!-- Auth Card (Exact Figma 1:2440 & 1:2475: 440px standalone centered card) -->
    <div class="w-full max-w-[440px] bg-white rounded-xl border border-slate-200/80 shadow-[0_4px_16px_rgba(0,0,0,0.05)] p-8 sm:p-10 transition-all">
      <!-- Top Brand Logo -->
      <div class="flex justify-center mb-6">
        <router-link to="/" class="inline-block select-none">
          <img :src="logoUrl" alt="Jogja Center" class="h-10 w-10 object-contain" />
        </router-link>
      </div>

      <!-- Reset Progress Stepper (Exact Figma) -->
      <div class="grid grid-cols-3 gap-3 mb-6">
        <div>
          <div class="h-[3px] rounded-full bg-[#0775C5]"></div>
          <p class="text-xs font-semibold text-[#0775C5] mt-2">1. Email</p>
        </div>
        <div>
          <div class="h-[3px] rounded-full bg-[#E6E8E9]"></div>
          <p class="text-xs font-medium text-slate-500 mt-2">2. Verifikasi</p>
        </div>
        <div>
          <div class="h-[3px] rounded-full bg-[#E6E8E9]"></div>
          <p class="text-xs font-medium text-slate-500 mt-2">3. Password baru</p>
        </div>
      </div>

      <!-- Step Symbol Badge (Exact Figma 1:2460: 52x52 rounded-xl bg-[#F0F7FD] with key-round icon) -->
      <div class="w-[52px] h-[52px] rounded-xl bg-[#F0F7FD] flex items-center justify-center text-[#0775C5] mb-5">
        <KeyRound class="w-6 h-6 stroke-[1.75]" />
      </div>

      <!-- Form Introduction -->
      <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Lupa password?</h1>
        <p class="text-sm text-slate-500 mt-1.5 leading-relaxed">
          Masukkan email yang terdaftar untuk menerima kode verifikasi.
        </p>
      </div>

      <!-- Error Banner (Figma 1:2475: ! Email tidak terdaftar.) -->
      <transition
        enter-active-class="transition duration-200 ease-out"
        enter-from-class="opacity-0 -translate-y-1"
        enter-to-class="opacity-100 translate-y-0"
      >
        <div
          v-if="errorMessage"
          class="flex items-center gap-2.5 p-3 rounded-lg bg-[#FFF3F0] border border-[#FFC4BC] text-[#D33027] text-xs font-medium mt-4"
        >
          <div class="w-5 h-5 rounded-full bg-[#D33027] text-white flex items-center justify-center font-bold text-xs shrink-0 select-none">
            !
          </div>
          <span>{{ errorMessage }}</span>
        </div>
      </transition>

      <!-- Form Section -->
      <form @submit.prevent="handleRequestReset" class="mt-5 space-y-4">
        <!-- Input Alamat Email -->
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1.5">
            Alamat email
          </label>
          <input
            v-model="email"
            type="email"
            required
            placeholder="nama@email.com"
            :class="[
              'w-full rounded-lg border px-3.5 py-2.5 text-xs outline-none transition',
              errorMessage
                ? 'border-red-300 focus:border-red-500 focus:ring-1 focus:ring-red-500 bg-red-50/20'
                : 'border-slate-200 focus:border-[#0775C5] focus:ring-1 focus:ring-[#0775C5] text-slate-800 placeholder:text-slate-400',
            ]"
          />
          <p class="text-xs text-slate-500 mt-2 leading-relaxed">
            Kami akan mengirimkan kode 9 digit ke email Anda untuk memastikan akun ini milik Anda.
          </p>
        </div>

        <!-- Tombol Kirim Kode Verifikasi -->
        <button
          type="submit"
          :disabled="isLoading"
          class="w-full rounded-lg bg-[#0775C5] hover:bg-[#0662a6] active:bg-[#05538d] text-white py-2.5 text-xs font-semibold transition shadow-xs cursor-pointer disabled:opacity-70 mt-6"
        >
          <span v-if="!isLoading">Kirim kode verifikasi</span>
          <span v-else>Mengirim...</span>
        </button>

        <!-- Link Kembali ke Masuk -->
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

      <!-- Status Preview Toggle (Figma 1:2475 preview) -->
      <div class="mt-6 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
        <span>Pratinjau status:</span>
        <button
          type="button"
          @click="toggleErrorPreview"
          class="underline hover:text-slate-600 cursor-pointer"
        >
          {{ errorMessage ? 'Normal (1:2440)' : 'Email tidak terdaftar (1:2475)' }}
        </button>
      </div>
    </div>

    <!-- Security Footer (Exact Figma 1:2444: shield-check + Pemulihan akun yang aman dan terlindungi) -->
    <div class="mt-6 flex items-center justify-center gap-2 text-xs text-slate-500">
      <ShieldCheck class="w-4 h-4 text-slate-400 shrink-0" />
      <span>Pemulihan akun yang aman dan terlindungi</span>
    </div>
  </div>
</template>
