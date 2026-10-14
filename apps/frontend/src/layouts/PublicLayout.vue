<script setup lang="ts">
import Navbar from '@/components/common/Navbar.vue'
import Footer from '@/components/common/Footer.vue'
</script>

<template>
  <div class="min-h-screen flex flex-col bg-white text-slate-800 antialiased font-sans">
    <Navbar />
    <main class="flex-1 flex flex-col">
      <router-view />
    </main>
    <Footer />
  </div>
</template>