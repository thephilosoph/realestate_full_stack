<script setup lang="ts">
import AdminSidebar from '@/components/admin/AdminSidebar.vue'
</script>

<template>
  <div class="flex min-h-screen bg-bg text-white">
    <AdminSidebar />
    <main class="flex-1 overflow-auto">
      <div class="mx-auto max-w-6xl px-6 py-8">
        <router-view />
      </div>
    </main>
  </div>
</template>
