<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { ArrowLeft, Mail, ShieldCheck } from 'lucide-vue-next'
import logoUrl from '@/assets/logo.svg'

const router = useRouter()

const userEmail = ref('nadia@gmail.com')
const digits = ref(['', '', '', '', '', '', '', '', '']) // 9 digits matching Figma
const inputRefs = ref<HTMLInputElement[]>([])

const countdown = ref(59)
let timer: any = null

const isLoading = ref(false)
const errorMessage = ref('')

onMounted(() => {
  const saved = sessionStorage.getItem('reset_email')
  if (saved) {
    userEmail.value = saved
  }

  startCountdown()

  // Auto focus first digit
  setTimeout(() => {
    inputRefs.value[0]?.focus()
  }, 100)
})

onUnmounted(() => {
  if (timer) clearInterval(timer)
})

function startCountdown() {
  countdown.value = 59
  if (timer) clearInterval(timer)
  timer = setInterval(() => {
    if (countdown.value > 0) {
      countdown.value--
    } else {
      clearInterval(timer)
    }
  }, 1000)
}

function handleInput(index: number, event: Event) {
  const target = event.target as HTMLInputElement
  const val = target.value.replace(/\D/g, '')

  digits.value[index] = val ? val[val.length - 1] : ''

  if (val && index < digits.value.length - 1) {
    inputRefs.value[index + 1]?.focus()
  }
}

function handleKeyDown(index: number, event: KeyboardEvent) {
  if (event.key === 'Backspace' && !digits.value[index] && index > 0) {
    inputRefs.value[index - 1]?.focus()
  }
}

function handlePaste(event: ClipboardEvent) {
  event.preventDefault()
  const pasted = event.clipboardData?.getData('text') || ''
  const cleanNumbers = pasted.replace(/\D/g, '').slice(0, 9).split('')

  cleanNumbers.forEach((char, idx) => {
    if (idx < digits.value.length) {
      digits.value[idx] = char
    }
  })

  const nextFocusIndex = Math.min(cleanNumbers.length, digits.value.length - 1)
  inputRefs.value[nextFocusIndex]?.focus()
}

function handleResend() {
  if (countdown.value > 0) return
  startCountdown()
  errorMessage.value = ''
}

function handleVerify() {
  errorMessage.value = ''
  const code = digits.value.join('')

  if (code.length < 9) {
    // Exact Figma 1:2366 error text
    errorMessage.value = 'Kode verifikasi salah atau kedaluwarsa.'
    return
  }

  isLoading.value = true

  setTimeout(() => {
    isLoading.value = false
    sessionStorage.setItem('reset_code_verified', 'true')
    router.push('/reset-password')
  }, 450)
}

function toggleErrorPreview() {
  if (errorMessage.value) {
    errorMessage.value = ''
  } else {
    errorMessage.value = 'Kode verifikasi salah atau kedaluwarsa.'
  }
}
</script>

<template>
  <div class="min-h-screen bg-[#F8FAFC] flex flex-col items-center justify-center p-4 selection:bg-blue-100 font-sans">
    <!-- Auth Card (Exact Figma 1:2297 & 1:2366: 440px standalone centered card) -->
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
          <div class="h-[3px] rounded-full bg-[#E6E8E9]"></div>
          <p class="text-xs font-medium text-slate-500 mt-2">1. Email</p>
        </div>
        <div>
          <div class="h-[3px] rounded-full bg-[#0775C5]"></div>
          <p class="text-xs font-semibold text-[#0775C5] mt-2">2. Verifikasi</p>
        </div>
        <div>
          <div class="h-[3px] rounded-full bg-[#E6E8E9]"></div>
          <p class="text-xs font-medium text-slate-500 mt-2">3. Password baru</p>
        </div>
      </div>

      <!-- Step Symbol Badge (Exact Figma 1:2391: 52x52 rounded-xl bg-[#F0F7FD] with mail icon) -->
      <div class="w-[52px] h-[52px] rounded-xl bg-[#F0F7FD] flex items-center justify-center text-[#0775C5] mb-5">
        <Mail class="w-6 h-6 stroke-[1.75]" />
      </div>

      <!-- Form Introduction -->
      <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Periksa email Anda</h1>
        <p class="text-sm text-slate-500 mt-1.5 leading-relaxed">
          Masukkan kode 9 digit yang kami kirim ke
        </p>
        <div class="flex items-center gap-1.5 mt-1 text-sm font-medium">
          <span class="text-slate-900 font-semibold">{{ userEmail }}</span>
          <router-link to="/forgot-password" class="text-[#0775C5] hover:underline font-medium ml-1">
            Ubah email
          </router-link>
        </div>
      </div>

      <!-- Error Banner (Figma 1:2366: ! Kode verifikasi salah atau kedaluwarsa.) -->
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

      <!-- Verification Form Section -->
      <form @submit.prevent="handleVerify" class="mt-5 space-y-4">
        <!-- 9-digit segmented OTP inputs (Exact Figma: 3 groups of 3 digits) -->
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-2">
            Kode verifikasi
          </label>
          <div class="flex items-center justify-between gap-1.5 sm:gap-2" @paste="handlePaste">
            <!-- Group 1 (Digits 0, 1, 2) -->
            <div class="flex items-center gap-1 sm:gap-1.5">
              <input
                v-for="idx in [0, 1, 2]"
                :key="idx"
                :ref="(el) => (inputRefs[idx] = el as HTMLInputElement)"
                v-model="digits[idx]"
                type="text"
                maxlength="1"
                inputmode="numeric"
                autocomplete="one-time-code"
                @input="handleInput(idx, $event)"
                @keydown="handleKeyDown(idx, $event)"
                :class="[
                  'w-[34px] sm:w-[38px] h-[48px] sm:h-[52px] text-center text-lg font-bold font-mono rounded-lg border outline-none transition',
                  errorMessage
                    ? 'border-red-300 bg-red-50/20 text-red-700'
                    : digits[idx]
                    ? 'border-[#0775C5] bg-blue-50/20 text-slate-900 border-2'
                    : 'border-slate-200 bg-white text-slate-900 focus:border-[#0775C5] focus:border-2',
                ]"
              />
            </div>

            <!-- Group 2 (Digits 3, 4, 5) -->
            <div class="flex items-center gap-1 sm:gap-1.5">
              <input
                v-for="idx in [3, 4, 5]"
                :key="idx"
                :ref="(el) => (inputRefs[idx] = el as HTMLInputElement)"
                v-model="digits[idx]"
                type="text"
                maxlength="1"
                inputmode="numeric"
                autocomplete="one-time-code"
                @input="handleInput(idx, $event)"
                @keydown="handleKeyDown(idx, $event)"
                :class="[
                  'w-[34px] sm:w-[38px] h-[48px] sm:h-[52px] text-center text-lg font-bold font-mono rounded-lg border outline-none transition',
                  errorMessage
                    ? 'border-red-300 bg-red-50/20 text-red-700'
                    : digits[idx]
                    ? 'border-[#0775C5] bg-blue-50/20 text-slate-900 border-2'
                    : 'border-slate-200 bg-white text-slate-900 focus:border-[#0775C5] focus:border-2',
                ]"
              />
            </div>

            <!-- Group 3 (Digits 6, 7, 8) -->
            <div class="flex items-center gap-1 sm:gap-1.5">
              <input
                v-for="idx in [6, 7, 8]"
                :key="idx"
                :ref="(el) => (inputRefs[idx] = el as HTMLInputElement)"
                v-model="digits[idx]"
                type="text"
                maxlength="1"
                inputmode="numeric"
                autocomplete="one-time-code"
                @input="handleInput(idx, $event)"
                @keydown="handleKeyDown(idx, $event)"
                :class="[
                  'w-[34px] sm:w-[38px] h-[48px] sm:h-[52px] text-center text-lg font-bold font-mono rounded-lg border outline-none transition',
                  errorMessage
                    ? 'border-red-300 bg-red-50/20 text-red-700'
                    : digits[idx]
                    ? 'border-[#0775C5] bg-blue-50/20 text-slate-900 border-2'
                    : 'border-slate-200 bg-white text-slate-900 focus:border-[#0775C5] focus:border-2',
                ]"
              />
            </div>
          </div>

          <p class="text-xs text-slate-500 mt-2 leading-relaxed">
            Kode berlaku selama 30 menit. Jangan bagikan kode ini kepada siapa pun.
          </p>
        </div>

        <!-- Tombol Verifikasi kode -->
        <button
          type="submit"
          :disabled="isLoading"
          class="w-full rounded-lg bg-[#0775C5] hover:bg-[#0662a6] active:bg-[#05538d] text-white py-2.5 text-xs font-semibold transition shadow-xs cursor-pointer disabled:opacity-70 mt-6"
        >
          <span v-if="!isLoading">Verifikasi kode</span>
          <span v-else>Memverifikasi...</span>
        </button>

        <!-- Resend row -->
        <div class="text-center text-xs text-slate-500 pt-2 leading-relaxed">
          <span>Belum menerima kode? Periksa folder spam atau </span>
          <button
            type="button"
            @click="handleResend"
            class="text-[#0775C5] hover:text-[#0662a6] font-semibold cursor-pointer underline ml-0.5"
          >
            Kirim ulang kode
          </button>
        </div>

        <!-- Link Kembali ke email -->
        <div class="text-center pt-2">
          <router-link
            to="/forgot-password"
            class="inline-flex items-center gap-1.5 text-xs text-[#0775C5] hover:text-[#0662a6] font-medium hover:underline"
          >
            <ArrowLeft class="h-3.5 w-3.5" />
            <span>Kembali ke email</span>
          </router-link>
        </div>
      </form>

      <!-- Status Preview Toggle (Figma 1:2366 preview) -->
      <div class="mt-6 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
        <span>Pratinjau status:</span>
        <button
          type="button"
          @click="toggleErrorPreview"
          class="underline hover:text-slate-600 cursor-pointer"
        >
          {{ errorMessage ? 'Normal (1:2297)' : 'Kode salah/kedaluwarsa (1:2366)' }}
        </button>
      </div>
    </div>

    <!-- Security Footer (Exact Figma 1:2374: shield-check + Pemulihan akun yang aman dan terlindungi) -->
    <div class="mt-6 flex items-center justify-center gap-2 text-xs text-slate-500">
      <ShieldCheck class="w-4 h-4 text-slate-400 shrink-0" />
      <span>Pemulihan akun yang aman dan terlindungi</span>
    </div>
  </div>
</template>
