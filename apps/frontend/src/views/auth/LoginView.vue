<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { Eye, EyeOff, AlertCircle } from 'lucide-vue-next'
import logoUrl from '@/assets/logo.svg'
import tuguJogjaUrl from '@/assets/tugujogja.png'
import { authService } from '@/services/authService'

const router = useRouter()

const identifier = ref('')
const password = ref('')
const rememberMe = ref(false)
const showPassword = ref(false)
const errorMessage = ref('')
const isLoading = ref(false)

function normalizeRoleName(rawRole?: string): 'Superadmin' | 'Admin' | 'User' {
  const normalized = (rawRole || '').toLowerCase()
  if (normalized.includes('super')) return 'Superadmin'
  if (normalized.includes('admin')) return 'Admin'
  return 'User'
}

async function handleLogin() {
  errorMessage.value = ''
  isLoading.value = true

  try {
    // Real backend authentication via Laravel Sanctum
    const result = await authService.login({
      email: identifier.value,
      password: password.value,
    })

    if (result && result.token) {
      localStorage.setItem('token', result.token)
      const roleName = normalizeRoleName(result.user?.roles?.[0])
      localStorage.setItem(
        'user',
        JSON.stringify({
          id: result.user?.id || 1,
          email: result.user?.email || identifier.value,
          name: result.user?.fullname || identifier.value.split('@')[0],
          initials: (result.user?.fullname || identifier.value).substring(0, 2).toUpperCase(),
          role: roleName,
        })
      )
      router.push('/dashboard')
      return
    }
  } catch (error: any) {
    // Check fallback for local mockup / preview
    const storedUserRaw = localStorage.getItem('registered_user')
    const storedUser = storedUserRaw ? JSON.parse(storedUserRaw) : null

    const isCustomValid =
      storedUser &&
      (identifier.value === storedUser.username || identifier.value === storedUser.email) &&
      password.value === storedUser.password

    const isDefaultValid =
      (identifier.value === 'admin' ||
        identifier.value === 'admin@jogjakota.go.id' ||
        identifier.value === 'haidarbahzi07@gmail.com') &&
      (password.value === 'admin123' || password.value === '1234567890')

    if (isCustomValid || isDefaultValid) {
      const activeUser = isCustomValid
        ? {
            ...storedUser,
            role: normalizeRoleName(storedUser.role),
          }
        : {
            name: 'Superadmin123',
            initials: 'SA',
            role: 'Superadmin',
            email: 'admin@jogjakota.go.id',
          }

      localStorage.setItem('token', 'mock_jwt_token_jogjacenter_2026')
      localStorage.setItem('user', JSON.stringify(activeUser))
      router.push('/dashboard')
      return
    }

    if (error.response?.data?.message) {
      errorMessage.value = error.response.data.message
    } else {
      // Exact Figma 1:5242 error text
      errorMessage.value = 'Email/username atau kata sandi salah'
    }
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen flex w-full font-sans antialiased bg-white selection:bg-blue-100">
    <!-- Left Column: Auth Form Area (Figma 1:5221 & 1:5233) -->
    <div class="flex-1 flex flex-col justify-between p-8 sm:p-12 lg:p-16 max-w-xl mx-auto lg:mx-0 w-full">
      <!-- Top Brand Logo -->
      <router-link to="/" class="flex items-center gap-3 w-fit select-none">
        <img :src="logoUrl" alt="Jogja Center" class="h-8 w-8 object-contain shrink-0" />
        <span class="font-bold text-sm tracking-wide text-slate-900">Jogja Center</span>
      </router-link>

      <!-- Center Form Container -->
      <div class="my-auto py-8 w-full max-w-md mx-auto lg:mx-0">
        <!-- Header (Figma 1:5224) -->
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Selamat Datang</h1>
        <p class="text-xs text-slate-500 mt-2 leading-relaxed">
          Silakan masukkan email atau username dan kata sandi untuk masuk ke sistem.
        </p>

        <!-- Error Notification Banner (Figma 1:5239: Error Banner) -->
        <transition
          enter-active-class="transition duration-200 ease-out"
          enter-from-class="opacity-0 -translate-y-1"
          enter-to-class="opacity-100 translate-y-0"
        >
          <div
            v-if="errorMessage"
            class="flex items-center gap-2.5 p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 text-xs font-medium mt-4 shadow-2xs"
          >
            <div class="w-5 h-5 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-xs shrink-0">
              !
            </div>
            <span>{{ errorMessage }}</span>
          </div>
        </transition>

        <!-- Form Elements (Figma 1:5227) -->
        <form @submit.prevent="handleLogin" class="mt-6 space-y-4">
          <!-- Input / Email atau Username (Figma 1:5228) -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
              Email atau Username
            </label>
            <input
              v-model="identifier"
              type="text"
              required
              placeholder="Masukkan email atau username"
              :class="[
                'w-full px-3.5 py-2.5 rounded-lg border text-xs outline-none transition',
                errorMessage
                  ? 'border-rose-300 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 bg-rose-50/20'
                  : 'border-slate-200 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 text-slate-800 placeholder:text-slate-400',
              ]"
            />
          </div>

          <!-- Input / Kata Sandi (Figma 1:5229) -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
              Kata Sandi
            </label>
            <div class="relative">
              <input
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                required
                placeholder="Masukkan kata sandi"
                :class="[
                  'w-full px-3.5 py-2.5 pr-10 rounded-lg border text-xs outline-none transition',
                  errorMessage
                    ? 'border-rose-300 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 bg-rose-50/20'
                    : 'border-slate-200 focus:border-blue-600 focus:ring-1 focus:ring-blue-600 text-slate-800 placeholder:text-slate-400',
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

          <!-- Checkbox / Ingat saya (Figma 1:5230) -->
          <div class="flex items-center gap-2 pt-1">
            <input
              id="remember"
              v-model="rememberMe"
              type="checkbox"
              class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
            />
            <label for="remember" class="text-xs text-slate-600 select-none cursor-pointer">
              Ingat saya
            </label>
          </div>

          <!-- Button / Masuk (Figma 1:5231) -->
          <button
            type="submit"
            :disabled="isLoading"
            class="w-full h-11 py-2.5 px-4 rounded-lg bg-[#2557c7] hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-xs transition shadow-2xs cursor-pointer disabled:opacity-70 mt-2"
          >
            <span v-if="!isLoading">Masuk</span>
            <span v-else>Memproses...</span>
          </button>

          <!-- Forgot Password Link (Figma 1:5232) -->
          <div class="text-center pt-2">
            <router-link
              to="/forgot-password"
              class="text-xs text-blue-600 hover:text-blue-700 font-semibold hover:underline"
            >
              Lupa kata sandi?
            </router-link>
          </div>
        </form>
      </div>

      <!-- Bottom Brand Footer -->
      <div class="text-[11px] text-slate-400 pt-6">
        © 2026 Pemerintah Daerah Daerah Istimewa Yogyakarta. Seluruh hak cipta dilindungi.
      </div>
    </div>

    <!-- Right Column: Visual Artwork (Auth Split Layout) -->
    <div class="hidden lg:flex lg:w-1/2 bg-[#0e214d] relative overflow-hidden items-center justify-center p-12 select-none">
      <!-- Background subtle radial glow -->
      <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>

      <div class="relative z-10 max-w-lg text-center space-y-6">
        <!-- Tugu Jogja Graphic Illustration -->
        <div class="mx-auto w-64 h-72 flex items-center justify-center">
          <img
            :src="tuguJogjaUrl"
            alt="Tugu Yogyakarta Artwork"
            class="max-h-full max-w-full object-contain drop-shadow-[0_20px_40px_rgba(0,0,0,0.4)]"
          />
        </div>

        <div class="space-y-2">
          <h2 class="text-2xl font-bold text-white tracking-tight">
            Pusat Data Terpadu DIY
          </h2>
          <p class="text-xs text-blue-200/80 leading-relaxed max-w-md mx-auto">
            Satu pintu akses data pembangunan dan visualisasi analitik lintas sektor untuk mendukung kebijakan berbasis bukti di Daerah Istimewa Yogyakarta.
          </p>
        </div>

        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/15 text-[11px] text-blue-100 font-medium">
          <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
          <span>Sistem Informasi Terintegrasi & Aman</span>
        </div>
      </div>
    </div>
  </div>
</template>
