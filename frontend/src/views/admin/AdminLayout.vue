<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import AdminSidebar from '@/components/admin/AdminSidebar.vue'

const route = useRoute()

const sectionTitle = computed(() => {
  const crumb = route.path
    .replace('/admin', '')
    .split('/')
    .filter(Boolean)
    .map((part) => (part.match(/^\d+$/) ? 'Edit' : part === 'new' ? 'New' : part))
    .pop()
  if (!crumb) return 'Dashboard'
  return crumb.charAt(0).toUpperCase() + crumb.slice(1)
})
</script>

<template>
  <div class="flex min-h-screen bg-bg text-white">
    <AdminSidebar />
    <div class="flex min-w-0 flex-1 flex-col">
      <header class="sticky top-0 z-10 flex items-center justify-between border-b border-border bg-bg/90 px-6 py-3.5 backdrop-blur">
        <div class="flex items-center gap-2 text-sm">
          <router-link to="/admin" class="text-muted transition-colors hover:text-white">Admin</router-link>
          <span v-if="route.path !== '/admin'" class="text-soft">/</span>
          <span v-if="route.path !== '/admin'" class="font-medium text-white">{{ sectionTitle }}</span>
        </div>
        <router-link
          to="/"
          class="flex items-center gap-1.5 rounded-lg border border-border px-3 py-1.5 text-xs text-muted transition-colors hover:border-border-light hover:text-white"
        >
          <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm10.5-9h-4.5m-12 0H1.5" />
          </svg>
          Public site
        </router-link>
      </header>
      <main class="flex-1 overflow-auto">
        <div class="mx-auto max-w-7xl px-6 py-8">
          <router-view />
        </div>
      </main>
    </div>
  </div>
</template>
