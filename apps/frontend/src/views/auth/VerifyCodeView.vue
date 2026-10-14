<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { ArrowLeft, Mail, ShieldCheck } from 'lucide-vue-next'
import logoUrl from '@/assets/logo.svg'
import { authService } from '@/services/authService'

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

async function handleResend() {
  if (countdown.value > 0) return
  errorMessage.value = ''
  startCountdown()

  try {
    const resetId = sessionStorage.getItem('reset_id') || undefined
    const res = await authService.requestReset({ email: userEmail.value, id: resetId })
    if (res && res.token) {
      sessionStorage.setItem('reset_id', res.token)
    }
  } catch {
    // Keep local countdown running
  }
}

async function handleVerify() {
  errorMessage.value = ''
  const code = digits.value.join('')

  if (code.length < 9) {
    // Exact Figma 1:2366 error text
    errorMessage.value = 'Kode verifikasi salah atau kedaluwarsa.'
    return
  }

  const resetId = sessionStorage.getItem('reset_id')
  if (!resetId) {
    // Fallback for preview/mock test
    sessionStorage.setItem('reset_code_verified', 'true')
    router.push('/reset-password')
    return
  }

  isLoading.value = true

  try {
    const res = await authService.validateReset({ id: resetId, code })
    if (res && res.token) {
      sessionStorage.setItem('reset_token', res.token)
    }
    sessionStorage.setItem('reset_code_verified', 'true')
    router.push('/reset-password')
  } catch (error: any) {
    errorMessage.value =
      error.response?.data?.message || 'Kode verifikasi salah atau kedaluwarsa.'
  } finally {
    isLoading.value = false
  }
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
    <!-- Auth Card (Exact Figma 1:2330 & 1:2366: 440px standalone centered card) -->
    <div class="w-full max-w-[440px] bg-white rounded-xl border border-slate-200/80 shadow-[0_4px_16px_rgba(0,0,0,0.05)] p-8 sm:p-10 transition-all">
      <!-- Top Brand Logo -->
      <div class="flex justify-center mb-6">
        <router-link to="/" class="inline-block select-none">
          <img :src="logoUrl" alt="Jogja Center" class="h-10 w-10 object-contain" />
        </router-link>
      </div>

      <!-- Reset Progress Stepper (Step 2 active) -->
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
          <div class="h-[3px] rounded-full bg-[#E6E8E9]"></div>
          <p class="text-xs font-medium text-slate-500 mt-2">3. Password baru</p>
        </div>
      </div>

      <!-- Step Symbol Badge (Exact Figma 1:2349: 52x52 rounded-xl bg-[#F0F7FD] with mail icon) -->
      <div class="w-[52px] h-[52px] rounded-xl bg-[#F0F7FD] flex items-center justify-center text-[#0775C5] mb-5">
        <Mail class="w-6 h-6 stroke-[1.75]" />
      </div>

      <!-- Form Introduction -->
      <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Masukkan kode verifikasi</h1>
        <p class="text-sm text-slate-500 mt-1.5 leading-relaxed">
          Kode 9 digit telah dikirimkan ke <span class="font-semibold text-slate-800">{{ userEmail }}</span>
        </p>
      </div>

      <!-- Error Banner (Figma 1:2366: ! Kode verifikasi salah atau kedaluwarsa. Coba lagi atau kirim ulang kode baru.) -->
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

      <!-- Code Digits Input Grid (Exact Figma: 9 individual square inputs) -->
      <form @submit.prevent="handleVerify" class="mt-6 space-y-6">
        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-2">
            Kode 9 digit
          </label>
          <div class="grid grid-cols-9 gap-1.5 sm:gap-2" @paste="handlePaste">
            <input
              v-for="(_, index) in digits"
              :key="index"
              :ref="(el) => (inputRefs[index] = el as HTMLInputElement)"
              v-model="digits[index]"
              type="text"
              inputmode="numeric"
              maxlength="1"
              :class="[
                'w-full aspect-square text-center font-semibold text-base sm:text-lg rounded-lg border outline-none transition',
                errorMessage
                  ? 'border-red-300 focus:border-red-500 focus:ring-1 focus:ring-red-500 bg-red-50/20 text-red-900'
                  : 'border-slate-200 focus:border-[#0775C5] focus:ring-1 focus:ring-[#0775C5] text-slate-900 bg-white',
              ]"
              @input="handleInput(index, $event)"
              @keydown="handleKeyDown(index, $event)"
            />
          </div>
        </div>

        <!-- Tombol Verifikasi -->
        <button
          type="submit"
          :disabled="isLoading"
          class="w-full rounded-lg bg-[#0775C5] hover:bg-[#0662a6] active:bg-[#05538d] text-white py-2.5 text-xs font-semibold transition shadow-xs cursor-pointer disabled:opacity-70"
        >
          <span v-if="!isLoading">Verifikasi</span>
          <span v-else>Memverifikasi...</span>
        </button>

        <!-- Resend Code Timer / Button (Figma 1:2356 & 1:2378) -->
        <div class="text-center text-xs text-slate-500">
          <span v-if="countdown > 0">
            Kirim ulang kode dalam <span class="font-semibold text-slate-700">{{ countdown }} detik</span>
          </span>
          <button
            v-else
            type="button"
            @click="handleResend"
            class="text-[#0775C5] hover:text-[#0662a6] font-semibold hover:underline cursor-pointer"
          >
            Kirim ulang kode
          </button>
        </div>

        <!-- Link Kembali -->
        <div class="text-center pt-2">
          <router-link
            to="/forgot-password"
            class="inline-flex items-center gap-1.5 text-xs text-[#0775C5] hover:text-[#0662a6] font-medium hover:underline"
          >
            <ArrowLeft class="h-3.5 w-3.5" />
            <span>Kembali ke ubah email</span>
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
          {{ errorMessage ? 'Normal (1:2330)' : 'Kode salah/kedaluwarsa (1:2366)' }}
        </button>
      </div>
    </div>

    <!-- Security Footer -->
    <div class="mt-6 flex items-center justify-center gap-2 text-xs text-slate-500">
      <ShieldCheck class="w-4 h-4 text-slate-400 shrink-0" />
      <span>Pemulihan akun yang aman dan terlindungi</span>
    </div>
  </div>
</template>
