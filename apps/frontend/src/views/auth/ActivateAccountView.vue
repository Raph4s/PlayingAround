<script setup lang="ts">
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { Eye, EyeOff, Check, X, AlertCircle } from 'lucide-vue-next'
import logoUrl from '@/assets/logo.svg'
import { authService } from '@/services/authService'

const router = useRouter()
const route = useRoute()

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

const invitationToken =
  (route.params.id as string) ||
  (route.query.token as string) ||
  (route.query.id as string) ||
  ''

async function handleSubmit() {
  passwordMismatch.value = false

  // Validate passwords match (Figma 1:2616: Password tidak sama)
  if (form.value.password !== form.value.confirmPassword) {
    passwordMismatch.value = true
    return
  }

  isLoading.value = true

  try {
    if (invitationToken) {
      await authService.acceptInvitation({
        id: invitationToken,
        password: form.value.password,
        fullname: form.value.fullName,
      })
    }

    // Save newly activated user credentials into localStorage for local login testing fallback
    const activatedUser = {
      name: form.value.fullName,
      username: form.value.username,
      email: `${form.value.username}@jogjakota.go.id`,
      password: form.value.password,
      role: 'Admin',
      initials: form.value.fullName.slice(0, 2).toUpperCase(),
    }
    localStorage.setItem('registered_user', JSON.stringify(activatedUser))

    flowState.value = 'success'
  } catch (error: any) {
    if (error.response?.status === 404 || error.response?.status === 400) {
      flowState.value = 'expired'
    } else {
      flowState.value = 'expired'
    }
  } finally {
    isLoading.value = false
  }
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
            <!-- Error message Figma 1:2616: Password tidak sama -->
            <p v-if="passwordMismatch" class="text-xs text-red-500 mt-1 font-medium">
              Password tidak sama
            </p>
          </div>

          <!-- Button Submit -->
          <button
            type="submit"
            :disabled="isLoading"
            class="w-full mt-2 rounded-lg bg-blue-600 py-2.5 text-xs font-semibold text-white transition hover:bg-blue-700 shadow-xs cursor-pointer disabled:opacity-50"
          >
            <span v-if="!isLoading">Aktifkan Akun</span>
            <span v-else>Memproses...</span>
          </button>
        </form>

        <!-- Preview Flow State Switchers (for Figma design inspection) -->
        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
          <span>Pratinjau status:</span>
          <div class="flex gap-2">
            <button
              type="button"
              @click="flowState = 'success'"
              class="underline hover:text-slate-600 cursor-pointer"
            >
              Berhasil (1:2628)
            </button>
            <button
              type="button"
              @click="flowState = 'expired'"
              class="underline hover:text-slate-600 cursor-pointer"
            >
              Kedaluwarsa (1:2637)
            </button>
          </div>
        </div>
      </div>

      <!-- ================= 2. SUCCESS STATE (Figma 1:2628) ================= -->
      <div v-else-if="flowState === 'success'" class="text-center py-4 space-y-4">
        <!-- Success Icon -->
        <div class="w-14 h-14 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto border border-emerald-200">
          <Check class="h-8 w-8 stroke-[2.5]" />
        </div>

        <div>
          <h2 class="text-xl font-bold text-slate-900 tracking-tight">Akun Berhasil Diaktifkan!</h2>
          <p class="text-xs text-slate-500 mt-2 leading-relaxed max-w-xs mx-auto">
            Akun Anda sudah aktif dan siap digunakan. Silakan masuk untuk mulai menggunakan layanan Jogja Center.
          </p>
        </div>

        <button
          type="button"
          @click="handleGoToLogin"
          class="w-full mt-4 rounded-lg bg-blue-600 py-2.5 text-xs font-semibold text-white transition hover:bg-blue-700 shadow-xs cursor-pointer"
        >
          Masuk ke Akun
        </button>
      </div>

      <!-- ================= 3. EXPIRED STATE (Figma 1:2637) ================= -->
      <div v-else-if="flowState === 'expired'" class="text-center py-4 space-y-4">
        <!-- Expired Warning Icon -->
        <div class="w-14 h-14 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto border border-rose-200">
          <X class="h-8 w-8 stroke-[2.5]" />
        </div>

        <div>
          <h2 class="text-xl font-bold text-slate-900 tracking-tight">Tautan Kedaluwarsa</h2>
          <p class="text-xs text-slate-500 mt-2 leading-relaxed max-w-xs mx-auto">
            Tautan aktivasi akun sudah tidak berlaku atau sudah pernah digunakan. Hubungi admin untuk mendapatkan tautan baru.
          </p>
        </div>

        <div class="space-y-2 mt-4">
          <button
            type="button"
            @click="handleContactAdmin"
            class="w-full rounded-lg bg-blue-600 py-2.5 text-xs font-semibold text-white transition hover:bg-blue-700 shadow-xs cursor-pointer"
          >
            Hubungi Admin
          </button>
          <button
            type="button"
            @click="handleGoToLogin"
            class="w-full rounded-lg border border-slate-200 py-2.5 text-xs font-semibold text-slate-600 transition hover:bg-slate-50 cursor-pointer"
          >
            Kembali ke Halaman Masuk
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
