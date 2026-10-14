<script setup lang="ts">
import { ref, computed } from 'vue'
import {
  Sparkles,
  Palette,
  Image as ImageIcon,
  LayoutTemplate,
  Search as SearchIcon,
  Save,
  CheckCircle2,
  AlertCircle,
  UploadCloud,
  ExternalLink,
  RotateCcw,
  Eye,
  Check,
} from 'lucide-vue-next'

type PreferenceTab = 'identitas' | 'warna' | 'banner' | 'footer' | 'metadata'
const activeTab = ref<PreferenceTab>('identitas')

const isSaving = ref(false)
const saveSuccess = ref(false)
const isDirty = ref(false)

// 1. Identitas Form
const identitasForm = ref({
  appName: 'Jogja Center',
  logoName: 'logo-jogjacenter.svg',
  logoSize: '184 KB',
  faviconName: 'favicon.ico',
  faviconSize: '12 KB',
})

// 2. Warna Form
const warnaForm = ref({
  primary: '#2952CC',
  secondary: '#0F2260',
  textColor: '#000000',
  bgColor: '#F8FAFC',
})

const colorHexValid = computed(() => {
  const hexRegex = /^#([0-9A-Fa-f]{6})$/
  return (
    hexRegex.test(warnaForm.value.primary) &&
    hexRegex.test(warnaForm.value.secondary) &&
    hexRegex.test(warnaForm.value.textColor) &&
    hexRegex.test(warnaForm.value.bgColor)
  )
})

// 3. Banner Form
const bannerForm = ref({
  title: 'Data Jogja, Lebih Dekat',
  description:
    'Jelajahi data pembangunan Daerah Istimewa Yogyakarta yang terbuka, akurat, dan mudah dipahami.',
  ctaText: 'Lihat dashboard',
  isEnabled: true,
  imageName: 'banner-hero-diy.webp',
  imageSize: '1.4 MB',
})

// 4. Footer Form
const footerForm = ref({
  title: 'Jogja Center',
  description:
    'Portal data terpadu untuk mendukung transparansi dan layanan publik Daerah Istimewa Yogyakarta.',
  instagram: 'instagram.com/humas_jogja',
  youtube: 'youtube.com/@PemdaDIY',
  address:
    'Kompleks Kepatihan, Danurejan, Kota Yogyakarta, Daerah Istimewa Yogyakarta 55213',
  phone: '(0274) 562811',
  email: 'jogjacenter@jogjaprov.go.id',
  copyright: '© 2026 Pemerintah Daerah Daerah Istimewa Yogyakarta',
})

// 5. Metadata Form
const metadataForm = ref({
  title: 'Jogja Center — Portal Data Terpadu DIY',
  description:
    'Portal data terpadu Pemerintah Daerah Daerah Istimewa Yogyakarta untuk mengakses dashboard dan informasi publik terkini.',
  keywords: 'data jogja, dashboard publik, pemda diy',
})

function markDirty() {
  isDirty.value = true
}

function handleSave() {
  if (!colorHexValid.value && activeTab.value === 'warna') return

  isSaving.value = true
  setTimeout(() => {
    isSaving.value = false
    isDirty.value = false
    saveSuccess.value = true
    setTimeout(() => {
      saveSuccess.value = false
    }, 2500)
  }, 400)
}

function handleResetWarna() {
  warnaForm.value = {
    primary: '#2952CC',
    secondary: '#0F2260',
    textColor: '#000000',
    bgColor: '#F8FAFC',
  }
}
</script>

<template>
  <div class="space-y-6 max-w-7xl mx-auto selection:bg-blue-100 font-sans">
    <!-- Header & Breadcrumbs (Figma: Administrasi / Preferensi) -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <nav class="text-xs text-blue-600 font-medium mb-1">
          <span>Administrasi</span>
          <span class="text-slate-400 mx-1.5">/</span>
          <span class="text-slate-600">Preferensi</span>
        </nav>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Preferensi</h1>
        <p class="text-xs text-slate-500 mt-1">
          <template v-if="activeTab === 'identitas'">
            Kelola nama, logo, dan identitas visual yang tampil di seluruh situs.
          </template>
          <template v-else-if="activeTab === 'warna'">
            Sesuaikan palet merek dan periksa kontrasnya sebelum diterapkan.
          </template>
          <template v-else-if="activeTab === 'banner'">
            Atur konten hero yang menyambut pengunjung pada halaman utama.
          </template>
          <template v-else-if="activeTab === 'footer'">
            Lengkapi informasi kontak dan tautan yang tampil pada bagian bawah situs.
          </template>
          <template v-else-if="activeTab === 'metadata'">
            Optimalkan tampilan situs pada mesin pencari dan saat tautan dibagikan.
          </template>
        </p>
      </div>

      <!-- Save notification or unsaved changes tag -->
      <div class="flex items-center gap-3">
        <span
          v-if="isDirty"
          class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[11px] font-semibold"
        >
          Perubahan belum disimpan
        </span>
        <div
          v-if="saveSuccess"
          class="flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-medium animate-fade-in"
        >
          <CheckCircle2 class="h-3.5 w-3.5 text-emerald-600" />
          <span>Pengaturan preferensi tersimpan.</span>
        </div>
      </div>
    </div>

    <!-- Navigation Tabs (Exact 5 tabs from Figma: Identitas, Warna, Banner, Footer, Metadata) -->
    <div class="flex items-center gap-1 border-b border-slate-200 text-xs font-semibold overflow-x-auto pb-px">
      <button
        type="button"
        @click="activeTab = 'identitas'"
        :class="[
          'px-4 py-2.5 rounded-t-lg transition cursor-pointer flex items-center gap-2 border-b-2 font-medium',
          activeTab === 'identitas'
            ? 'border-blue-600 text-blue-600 bg-white font-semibold'
            : 'border-transparent text-slate-500 hover:text-slate-800 hover:bg-slate-50',
        ]"
      >
        <Sparkles class="h-4 w-4" />
        <span>Identitas</span>
      </button>

      <button
        type="button"
        @click="activeTab = 'warna'"
        :class="[
          'px-4 py-2.5 rounded-t-lg transition cursor-pointer flex items-center gap-2 border-b-2 font-medium',
          activeTab === 'warna'
            ? 'border-blue-600 text-blue-600 bg-white font-semibold'
            : 'border-transparent text-slate-500 hover:text-slate-800 hover:bg-slate-50',
        ]"
      >
        <Palette class="h-4 w-4" />
        <span>Warna</span>
      </button>

      <button
        type="button"
        @click="activeTab = 'banner'"
        :class="[
          'px-4 py-2.5 rounded-t-lg transition cursor-pointer flex items-center gap-2 border-b-2 font-medium',
          activeTab === 'banner'
            ? 'border-blue-600 text-blue-600 bg-white font-semibold'
            : 'border-transparent text-slate-500 hover:text-slate-800 hover:bg-slate-50',
        ]"
      >
        <ImageIcon class="h-4 w-4" />
        <span>Banner</span>
      </button>

      <button
        type="button"
        @click="activeTab = 'footer'"
        :class="[
          'px-4 py-2.5 rounded-t-lg transition cursor-pointer flex items-center gap-2 border-b-2 font-medium',
          activeTab === 'footer'
            ? 'border-blue-600 text-blue-600 bg-white font-semibold'
            : 'border-transparent text-slate-500 hover:text-slate-800 hover:bg-slate-50',
        ]"
      >
        <LayoutTemplate class="h-4 w-4" />
        <span>Footer</span>
      </button>

      <button
        type="button"
        @click="activeTab = 'metadata'"
        :class="[
          'px-4 py-2.5 rounded-t-lg transition cursor-pointer flex items-center gap-2 border-b-2 font-medium',
          activeTab === 'metadata'
            ? 'border-blue-600 text-blue-600 bg-white font-semibold'
            : 'border-transparent text-slate-500 hover:text-slate-800 hover:bg-slate-50',
        ]"
      >
        <SearchIcon class="h-4 w-4" />
        <span>Metadata</span>
      </button>
    </div>

    <!-- TAB 1: IDENTITAS (Figma: 1:5633) -->
    <div v-if="activeTab === 'identitas'" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
      <!-- Left: Form -->
      <div class="lg:col-span-7 bg-white rounded-xl border border-slate-200 p-6 shadow-2xs space-y-6">
        <div>
          <h2 class="text-sm font-bold text-slate-900">Identitas aplikasi</h2>
          <p class="text-xs text-slate-500 mt-0.5">
            Informasi ini digunakan pada navbar, judul halaman, dan komunikasi publik.
          </p>
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama aplikasi</label>
          <input
            v-model="identitasForm.appName"
            @input="markDirty"
            type="text"
            maxlength="60"
            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white"
          />
          <span class="text-[11px] text-slate-400 mt-1 block">Maksimal 60 karakter.</span>
        </div>

        <div class="pt-4 border-t border-slate-100 space-y-4">
          <div>
            <h3 class="text-xs font-bold text-slate-800">Aset merek</h3>
            <p class="text-[11px] text-slate-500 mt-0.5">
              Gunakan berkas transparan agar logo tetap tajam pada latar terang dan gelap.
            </p>
          </div>

          <!-- Logo Utama -->
          <div class="flex items-center justify-between p-3.5 rounded-lg border border-slate-200 bg-slate-50/60">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-lg bg-blue-700 text-white flex items-center justify-center font-bold text-xs shadow-2xs">
                JC
              </div>
              <div>
                <div class="text-xs font-semibold text-slate-900">Logo utama</div>
                <div class="text-[11px] text-slate-500">{{ identitasForm.logoName }} • {{ identitasForm.logoSize }}</div>
              </div>
            </div>
            <button
              type="button"
              class="px-3 py-1.5 rounded-md border border-slate-200 text-xs font-medium text-slate-600 bg-white hover:bg-slate-50 transition cursor-pointer"
            >
              Ganti
            </button>
          </div>

          <!-- Favicon -->
          <div class="flex items-center justify-between p-3.5 rounded-lg border border-slate-200 bg-slate-50/60">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-slate-200 flex items-center justify-center text-slate-700 text-xs font-bold">
                🏛️
              </div>
              <div>
                <div class="text-xs font-semibold text-slate-900">Favicon</div>
                <div class="text-[11px] text-slate-500">{{ identitasForm.faviconName }} • {{ identitasForm.faviconSize }}</div>
              </div>
            </div>
            <button
              type="button"
              class="px-3 py-1.5 rounded-md border border-slate-200 text-xs font-medium text-slate-600 bg-white hover:bg-slate-50 transition cursor-pointer"
            >
              Ganti
            </button>
          </div>
        </div>
      </div>

      <!-- Right: Preview Branding (Exact Figma 1:5633) -->
      <div class="lg:col-span-5 space-y-4">
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs space-y-4">
          <div>
            <h3 class="text-xs font-bold text-slate-900">Preview branding</h3>
            <p class="text-[11px] text-slate-500">
              Pratinjau tampilan identitas pada navigasi utama dan tab browser.
            </p>
          </div>

          <!-- Browser Tab Preview -->
          <div class="p-3 bg-slate-100 rounded-lg border border-slate-200">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white rounded-t border-t border-x border-slate-200 text-[11px] font-medium text-slate-700 shadow-2xs">
              <span class="w-2.5 h-2.5 rounded-full bg-blue-600"></span>
              <span class="truncate max-w-[200px]">{{ identitasForm.appName }} — Portal Data Terpadu</span>
            </div>
          </div>

          <!-- Nav & Hero Mini Preview -->
          <div class="rounded-lg border border-slate-200 overflow-hidden bg-slate-50 text-slate-800 shadow-2xs">
            <div class="px-4 py-2.5 bg-blue-900 text-white flex items-center justify-between text-xs">
              <div class="flex items-center gap-2">
                <div class="w-5 h-5 rounded bg-blue-600 flex items-center justify-center font-bold text-[10px]">JC</div>
                <span class="font-bold tracking-tight">{{ identitasForm.appName }}</span>
              </div>
              <span class="text-[10px] text-blue-200">Superadmin123</span>
            </div>
            <div class="p-5 text-center space-y-2 bg-gradient-to-b from-blue-50/50 to-white">
              <span class="inline-block px-2 py-0.5 rounded text-[9px] font-bold tracking-wider uppercase bg-blue-100 text-blue-700">
                PORTAL DATA TERPADU
              </span>
              <h4 class="text-sm font-bold text-slate-900">Selamat datang di {{ identitasForm.appName }}</h4>
              <p class="text-[11px] text-slate-500 max-w-xs mx-auto">
                Temukan dashboard dan informasi layanan publik dalam satu tempat.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- TAB 2: WARNA (Figma: 1:4973) -->
    <div v-else-if="activeTab === 'warna'" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
      <!-- Left: Form Palet Warna -->
      <div class="lg:col-span-7 bg-white rounded-xl border border-slate-200 p-6 shadow-2xs space-y-6">
        <div>
          <h2 class="text-sm font-bold text-slate-900">Palet warna</h2>
          <p class="text-xs text-slate-500 mt-0.5">
            Masukkan nilai HEX enam digit. Perubahan akan terlihat langsung pada pratinjau.
          </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <!-- Primer -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Warna primer</label>
            <div class="flex items-center gap-2">
              <input
                v-model="warnaForm.primary"
                type="color"
                class="w-10 h-10 p-0.5 rounded-lg border border-slate-200 cursor-pointer"
              />
              <input
                v-model="warnaForm.primary"
                @input="markDirty"
                type="text"
                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-mono text-slate-800"
              />
            </div>
            <span class="text-[11px] text-slate-400 mt-1 block">Digunakan untuk tombol dan tautan utama.</span>
          </div>

          <!-- Sekunder -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Warna sekunder</label>
            <div class="flex items-center gap-2">
              <input
                v-model="warnaForm.secondary"
                type="color"
                class="w-10 h-10 p-0.5 rounded-lg border border-slate-200 cursor-pointer"
              />
              <input
                v-model="warnaForm.secondary"
                @input="markDirty"
                type="text"
                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-mono text-slate-800"
              />
            </div>
            <span class="text-[11px] text-slate-400 mt-1 block">Digunakan pada navbar dan elemen pendukung.</span>
          </div>

          <!-- Warna Teks -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Warna teks</label>
            <div class="flex items-center gap-2">
              <input
                v-model="warnaForm.textColor"
                type="color"
                class="w-10 h-10 p-0.5 rounded-lg border border-slate-200 cursor-pointer"
              />
              <input
                v-model="warnaForm.textColor"
                @input="markDirty"
                type="text"
                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-mono text-slate-800"
              />
            </div>
            <span class="text-[11px] text-slate-400 mt-1 block">Digunakan untuk warna teks di dalam website.</span>
          </div>

          <!-- Background -->
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Warna background</label>
            <div class="flex items-center gap-2">
              <input
                v-model="warnaForm.bgColor"
                type="color"
                class="w-10 h-10 p-0.5 rounded-lg border border-slate-200 cursor-pointer"
              />
              <input
                v-model="warnaForm.bgColor"
                @input="markDirty"
                type="text"
                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-mono text-slate-800"
              />
            </div>
            <span class="text-[11px] text-slate-400 mt-1 block">Latar belakang halaman utama.</span>
          </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
          <button
            type="button"
            @click="handleResetWarna"
            class="inline-flex items-center gap-1.5 text-xs text-slate-600 hover:text-slate-900 cursor-pointer"
          >
            <RotateCcw class="h-3.5 w-3.5" />
            <span>Reset default</span>
          </button>
        </div>
      </div>

      <!-- Right: Preview Komponen UI (Exact Figma 1:4973) -->
      <div class="lg:col-span-5 space-y-4">
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs space-y-4">
          <div>
            <h3 class="text-xs font-bold text-slate-900">Preview komponen UI</h3>
            <p class="text-[11px] text-slate-500">
              Contoh tampilan warna pada elemen yang paling sering digunakan.
            </p>
          </div>

          <!-- Dynamic Live Card -->
          <div
            class="p-4 rounded-xl border border-slate-200 space-y-3"
            :style="{ backgroundColor: warnaForm.bgColor, color: warnaForm.textColor }"
          >
            <div
              class="px-3 py-2 rounded-lg text-white flex items-center justify-between text-xs"
              :style="{ backgroundColor: warnaForm.secondary }"
            >
              <span class="font-bold">Jogja Center</span>
              <span class="text-[10px] opacity-80">Dashboard publik</span>
            </div>

            <div class="bg-white p-3.5 rounded-lg border border-slate-200 space-y-2">
              <div class="text-xs font-bold text-slate-900">Ringkasan layanan publik</div>
              <p class="text-[11px] text-slate-500">Data diperbarui 5 menit lalu</p>
              <div class="grid grid-cols-3 gap-2 pt-2 border-t border-slate-100 text-center">
                <div>
                  <div class="text-xs font-bold text-slate-900">24</div>
                  <div class="text-[10px] text-slate-400">Dashboard</div>
                </div>
                <div>
                  <div class="text-xs font-bold text-slate-900">18</div>
                  <div class="text-[10px] text-slate-400">Sumber data</div>
                </div>
                <div>
                  <div class="text-xs font-bold text-slate-900">12,4K</div>
                  <div class="text-[10px] text-slate-400">Kunjungan</div>
                </div>
              </div>
            </div>

            <!-- Dynamic Button States -->
            <div class="space-y-1.5 pt-1">
              <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">State tombol</span>
              <div class="flex items-center gap-2">
                <button
                  type="button"
                  class="px-3 py-1.5 rounded-md text-white text-xs font-semibold shadow-2xs"
                  :style="{ backgroundColor: warnaForm.primary }"
                >
                  Aksi utama
                </button>
                <button
                  type="button"
                  class="px-3 py-1.5 rounded-md text-xs font-semibold border border-slate-200 bg-white text-slate-600"
                >
                  Hover
                </button>
                <button
                  type="button"
                  disabled
                  class="px-3 py-1.5 rounded-md text-xs font-medium bg-slate-100 text-slate-400 cursor-not-allowed"
                >
                  Disabled
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- TAB 3: BANNER (Figma: 1:4876) -->
    <div v-else-if="activeTab === 'banner'" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
      <!-- Left: Form Konten Banner -->
      <div class="lg:col-span-7 bg-white rounded-xl border border-slate-200 p-6 shadow-2xs space-y-5">
        <div>
          <h2 class="text-sm font-bold text-slate-900">Konten banner</h2>
          <p class="text-xs text-slate-500 mt-0.5">
            Pastikan pesan singkat, jelas, dan relevan dengan layanan publik.
          </p>
        </div>

        <!-- Banner Image Upload -->
        <div class="p-4 rounded-xl border border-dashed border-slate-300 bg-slate-50/70 text-center space-y-2">
          <UploadCloud class="h-6 w-6 text-slate-400 mx-auto" />
          <div class="text-xs font-semibold text-slate-700">Unggah atau ganti gambar</div>
          <p class="text-[11px] text-slate-500">
            Rekomendasi: JPG/WebP, 1920×720 px, fokus objek berada di sisi kanan (maks. 5 MB).
          </p>
          <div class="text-[11px] font-mono text-blue-600 font-medium">
            {{ bannerForm.imageName }} • {{ bannerForm.imageSize }}
          </div>
        </div>

        <div>
          <div class="flex items-center justify-between mb-1.5">
            <label class="block text-xs font-semibold text-slate-700">Judul</label>
            <span class="text-[11px] text-slate-400">{{ bannerForm.title.length }}/70 karakter</span>
          </div>
          <input
            v-model="bannerForm.title"
            @input="markDirty"
            type="text"
            maxlength="70"
            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white"
          />
        </div>

        <div>
          <div class="flex items-center justify-between mb-1.5">
            <label class="block text-xs font-semibold text-slate-700">Deskripsi</label>
            <span class="text-[11px] text-slate-400">{{ bannerForm.description.length }}/180 karakter</span>
          </div>
          <textarea
            v-model="bannerForm.description"
            @input="markDirty"
            rows="3"
            maxlength="180"
            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white"
          ></textarea>
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1.5">Teks CTA</label>
          <input
            v-model="bannerForm.ctaText"
            @input="markDirty"
            type="text"
            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white"
          />
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
          <div>
            <span class="text-xs font-semibold text-slate-800 block">Tampilkan banner di halaman utama</span>
            <span class="text-[11px] text-slate-500">Jika dinonaktifkan, halaman utama akan langsung menampilkan daftar dashboard.</span>
          </div>
          <button
            type="button"
            @click="bannerForm.isEnabled = !bannerForm.isEnabled; markDirty()"
            :class="[
              'w-11 h-6 rounded-full transition-colors relative cursor-pointer',
              bannerForm.isEnabled ? 'bg-blue-600' : 'bg-slate-200',
            ]"
          >
            <span
              :class="[
                'w-4 h-4 rounded-full bg-white transition-transform block absolute top-1',
                bannerForm.isEnabled ? 'left-6' : 'left-1',
              ]"
            ></span>
          </button>
        </div>
      </div>

      <!-- Right: Preview Desktop Banner (Figma 1:4876) -->
      <div class="lg:col-span-5 space-y-4">
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs space-y-3">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-xs font-bold text-slate-900">Preview desktop</h3>
              <p class="text-[11px] text-slate-500">Rasio pratinjau 16:9 pada layar 1440 px.</p>
            </div>
            <span
              :class="[
                'px-2 py-0.5 rounded text-[10px] font-semibold',
                bannerForm.isEnabled ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500',
              ]"
            >
              {{ bannerForm.isEnabled ? 'Ditampilkan' : 'Disembunyikan' }}
            </span>
          </div>

          <div class="rounded-xl border border-slate-200 overflow-hidden shadow-2xs bg-slate-900 text-white">
            <div class="px-3 py-1.5 bg-slate-800 text-[10px] text-slate-400 font-mono flex items-center gap-1.5 border-b border-slate-700">
              <span class="w-2 h-2 rounded-full bg-rose-500"></span>
              <span class="w-2 h-2 rounded-full bg-amber-500"></span>
              <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
              <span class="ml-2">jogjacenter.jogjaprov.go.id</span>
            </div>

            <!-- Mini Hero Area -->
            <div class="p-6 bg-gradient-to-r from-blue-950 via-blue-900 to-indigo-900 space-y-3">
              <span class="inline-block px-2 py-0.5 rounded text-[9px] font-bold tracking-wider uppercase bg-blue-500/20 text-blue-300 border border-blue-400/30">
                PORTAL DATA TERPADU
              </span>
              <h4 class="text-base font-extrabold leading-tight tracking-tight">{{ bannerForm.title }}</h4>
              <p class="text-xs text-blue-100/80 leading-relaxed max-w-sm">{{ bannerForm.description }}</p>
              <div>
                <button
                  type="button"
                  class="px-4 py-2 rounded-lg bg-blue-600 text-white text-xs font-semibold shadow"
                >
                  {{ bannerForm.ctaText }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- TAB 4: FOOTER (Figma: 1:4768) -->
    <div v-else-if="activeTab === 'footer'" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
      <!-- Left: Form Footer -->
      <div class="lg:col-span-7 bg-white rounded-xl border border-slate-200 p-6 shadow-2xs space-y-5">
        <div>
          <h2 class="text-sm font-bold text-slate-900">Informasi footer</h2>
          <p class="text-xs text-slate-500 mt-0.5">
            Pastikan detail instansi selalu terkini dan dapat dihubungi.
          </p>
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1.5">Judul portal</label>
          <input
            v-model="footerForm.title"
            @input="markDirty"
            type="text"
            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white"
          />
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi instansi</label>
          <textarea
            v-model="footerForm.description"
            @input="markDirty"
            rows="2"
            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white"
          ></textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Instagram</label>
            <input
              v-model="footerForm.instagram"
              @input="markDirty"
              type="text"
              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white"
            />
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">YouTube</label>
            <input
              v-model="footerForm.youtube"
              @input="markDirty"
              type="text"
              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white"
            />
          </div>

          <div class="sm:col-span-2">
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat</label>
            <input
              v-model="footerForm.address"
              @input="markDirty"
              type="text"
              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white"
            />
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nomor kontak</label>
            <input
              v-model="footerForm.phone"
              @input="markDirty"
              type="text"
              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white"
            />
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email resmi</label>
            <input
              v-model="footerForm.email"
              @input="markDirty"
              type="email"
              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white"
            />
          </div>

          <div class="sm:col-span-2">
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Hak cipta (Copyright)</label>
            <input
              v-model="footerForm.copyright"
              @input="markDirty"
              type="text"
              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white"
            />
          </div>
        </div>
      </div>

      <!-- Right: Preview Footer (Figma 1:4768) -->
      <div class="lg:col-span-5 space-y-4">
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs space-y-3">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-xs font-bold text-slate-900">Preview footer</h3>
              <p class="text-[11px] text-slate-500">Tampilan pada desktop dengan konten dan tautan aktif.</p>
            </div>
            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
              Semua tautan valid
            </span>
          </div>

          <div class="rounded-xl border border-slate-800 overflow-hidden bg-slate-900 text-slate-300 p-5 space-y-4 text-xs">
            <div class="space-y-1">
              <div class="font-bold text-white text-sm">{{ footerForm.title }}</div>
              <p class="text-[11px] text-slate-400 leading-relaxed">{{ footerForm.description }}</p>
            </div>

            <div class="space-y-1 pt-2 border-t border-slate-800 text-[11px]">
              <div class="font-bold text-white">Hubungi kami:</div>
              <p class="text-slate-400">{{ footerForm.address }}</p>
              <p class="text-slate-400">{{ footerForm.phone }} • {{ footerForm.email }}</p>
            </div>

            <div class="pt-3 border-t border-slate-800 text-[10px] text-slate-500 text-center">
              {{ footerForm.copyright }}
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- TAB 5: METADATA (Figma: 1:4688) -->
    <div v-else-if="activeTab === 'metadata'" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
      <!-- Left: Form Metadata -->
      <div class="lg:col-span-7 bg-white rounded-xl border border-slate-200 p-6 shadow-2xs space-y-5">
        <div>
          <h2 class="text-sm font-bold text-slate-900">Metadata website</h2>
          <p class="text-xs text-slate-500 mt-0.5">
            Gunakan judul dan deskripsi yang ringkas untuk meningkatkan keterbacaan hasil pencarian.
          </p>
        </div>

        <div>
          <div class="flex items-center justify-between mb-1.5">
            <label class="block text-xs font-semibold text-slate-700">Title</label>
            <span class="text-[11px] text-slate-400">{{ metadataForm.title.length }}/60 karakter</span>
          </div>
          <input
            v-model="metadataForm.title"
            @input="markDirty"
            type="text"
            maxlength="60"
            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white"
          />
        </div>

        <div>
          <div class="flex items-center justify-between mb-1.5">
            <label class="block text-xs font-semibold text-slate-700">Description</label>
            <span class="text-[11px] text-slate-400">{{ metadataForm.description.length }}/160 karakter</span>
          </div>
          <textarea
            v-model="metadataForm.description"
            @input="markDirty"
            rows="3"
            maxlength="160"
            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white"
          ></textarea>
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-700 mb-1.5">Keywords</label>
          <input
            v-model="metadataForm.keywords"
            @input="markDirty"
            type="text"
            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 focus:outline-none focus:border-blue-600 focus:bg-white"
          />
          <span class="text-[11px] text-slate-400 mt-1 block">Pisahkan setiap kata kunci dengan koma.</span>
        </div>
      </div>

      <!-- Right: Previews (Google SERP & Social Share) -->
      <div class="lg:col-span-5 space-y-4">
        <!-- Google SERP Snippet Preview (Figma 1:4688) -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs space-y-3">
          <div>
            <h3 class="text-xs font-bold text-slate-900">Preview hasil pencarian</h3>
            <p class="text-[11px] text-slate-500">Perkiraan tampilan di Google pada perangkat desktop.</p>
          </div>

          <div class="p-4 rounded-xl border border-slate-200 bg-white space-y-1">
            <div class="flex items-center gap-2 text-[11px] text-slate-600">
              <div class="w-4 h-4 rounded bg-blue-600 text-white flex items-center justify-center font-bold text-[8px]">JC</div>
              <span class="font-semibold text-slate-800">Jogja Center</span>
              <span class="text-slate-400">› https://jogjacenter.jogjaprov.go.id</span>
            </div>
            <h4 class="text-sm font-semibold text-blue-700 hover:underline cursor-pointer">
              {{ metadataForm.title }}
            </h4>
            <p class="text-[11px] text-slate-600 leading-relaxed">
              {{ metadataForm.description }}
            </p>
          </div>
        </div>

        <!-- Social Share Card Preview -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs space-y-3">
          <div>
            <h3 class="text-xs font-bold text-slate-900">Preview saat dibagikan</h3>
            <p class="text-[11px] text-slate-500">Tampilan tautan OpenGraph saat dibagikan ke media sosial.</p>
          </div>

          <div class="rounded-xl border border-slate-200 overflow-hidden bg-slate-50">
            <div class="h-24 bg-gradient-to-r from-blue-700 to-indigo-800 flex items-center justify-center text-white text-xs font-bold">
              DATA TERBUKA DIY • Jogja Center
            </div>
            <div class="p-3 bg-white space-y-1">
              <span class="text-[10px] font-bold text-slate-400 uppercase">JOGJACENTER.JOGJAPROV.GO.ID</span>
              <div class="text-xs font-bold text-slate-900 truncate">{{ metadataForm.title }}</div>
              <p class="text-[11px] text-slate-500 line-clamp-2">{{ metadataForm.description }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Fixed Bottom Sticky Bar for Actions (Exact Figma: Batal / Simpan) -->
    <div class="p-4 bg-white rounded-xl border border-slate-200 shadow-2xs flex items-center justify-between">
      <div class="text-xs text-slate-500">
        Perubahan diterapkan ke situs publik setelah disimpan.
      </div>

      <div class="flex items-center gap-2">
        <button
          type="button"
          class="px-4 py-2 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition cursor-pointer"
        >
          Batal
        </button>
        <button
          type="button"
          @click="handleSave"
          :disabled="isSaving || (!colorHexValid && activeTab === 'warna')"
          class="inline-flex items-center gap-1.5 px-5 py-2 rounded-lg bg-blue-600 text-white text-xs font-semibold hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-2xs cursor-pointer"
        >
          <Save class="h-3.5 w-3.5" />
          <span>{{ isSaving ? 'Menyimpan...' : 'Simpan' }}</span>
        </button>
      </div>
    </div>
  </div>
</template>
