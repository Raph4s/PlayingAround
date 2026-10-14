<script setup lang="ts">
import { useRouter } from 'vue-router'
import { ArrowRight } from 'lucide-vue-next'

const router = useRouter()

const heroIllustrationUrl =
    'https://i.pinimg.com/1200x/51/41/4b/51414baa9cb2daf105cbc3751037ca76.jpg'

function handleGoToDashboard() {
  router.push('/dashboard')
}
</script>

<template>
  <!-- Hanya konten tengah: Navbar & Footer ditangani otomatis oleh PublicLayout -->
  <div class="flex-1 flex items-center bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-16 lg:py-24 w-full">
      <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-12 lg:gap-16">
        <!-- Kolom Kiri: Teks & CTA -->
        <div class="space-y-6">
          <h1 class="text-3xl sm:text-4xl lg:text-[42px] font-bold tracking-tight text-slate-900 leading-[1.2]">
            Pusat Monitoring dan Informasi Jogja Center
          </h1>

          <p class="text-sm sm:text-base text-slate-600 leading-relaxed max-w-lg">
            Pantau perkembangan Daerah Istimewa Yogyakarta dan temukan informasi publik yang akurat, terintegrasi, serta mudah dipahami dalam satu pusat data.
          </p>

          <div class="pt-2">
            <button
                type="button"
                @click="handleGoToDashboard"
                class="inline-flex items-center gap-2.5 rounded-lg bg-[#2557c7] px-6 py-3 text-xs font-semibold text-white shadow-xs hover:bg-blue-700 active:bg-blue-800 transition cursor-pointer"
            >
              <span>Lihat Dashboard</span>
              <ArrowRight class="h-4 w-4" />
            </button>
          </div>
        </div>

        <!-- Kolom Kanan: Ilustrasi Visualisasi -->
        <div class="flex items-center justify-center lg:justify-end">
          <div class="relative w-full max-w-lg rounded-2xl overflow-hidden bg-slate-50 border border-slate-100 shadow-sm p-4">
            <img
                :src="heroIllustrationUrl"
                alt="Visualisasi Dashboard Jogja Center"
                class="w-full h-72 sm:h-84 object-cover rounded-xl"
                loading="eager"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>