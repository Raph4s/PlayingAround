<script setup lang="ts">
import { Mail, Phone, MapPin } from 'lucide-vue-next'
</script>

<template>
  <footer class="bg-[#0e214d] text-white border-t border-blue-950/60 mt-auto">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-12">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-16">
        <!-- Col 1: Identity & Description -->
        <div class="space-y-2">
          <h3 class="font-bold text-sm text-white">Jogja Center</h3>
          <p class="text-xs text-slate-300 font-medium">Pusat monitoring dan informasi</p>
          <p class="text-xs text-slate-400 leading-relaxed max-w-sm pt-2">
            Mendukung layanan publik yang transparan, responsif, dan berbasis data untuk masyarakat Daerah Istimewa Yogyakarta.
          </p>
        </div>

        <!-- Col 2: Contact Information -->
        <div class="space-y-3">
          <h4 class="font-bold text-xs text-white">Informasi Kontak</h4>
          <ul class="space-y-2.5 text-xs text-slate-300">
            <li class="flex items-center gap-3">
              <Mail class="h-4 w-4 text-slate-400 shrink-0" />
              <span>jogjacenter@jogjaprov.go.id</span>
            </li>
            <li class="flex items-center gap-3">
              <Phone class="h-4 w-4 text-slate-400 shrink-0" />
              <span>(0274) 562811</span>
            </li>
            <li class="flex items-start gap-3">
              <MapPin class="h-4 w-4 text-slate-400 shrink-0 mt-0.5" />
              <span>Kompleks Kepatihan, Danurejan, Kota Yogyakarta, DIY 55213</span>
            </li>
          </ul>
        </div>
      </div>

      <!-- Copyright Line -->
      <div class="mt-12 pt-6 border-t border-white/10 text-xs text-slate-400">
        © 2026 Jogja Center
      </div>
    </div>
  </footer>
</template>