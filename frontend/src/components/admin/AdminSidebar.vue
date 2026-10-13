<script setup lang="ts">
import { useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const route = useRoute()
const auth = useAuthStore()

const links = [
  { to: '/admin', label: 'Dashboard', exact: true },
  { to: '/admin/properties', label: 'Properties' },
  { to: '/admin/features', label: 'Features' },
  { to: '/admin/faqs', label: 'FAQs' },
  { to: '/admin/testimonials', label: 'Testimonials' },
  { to: '/admin/clients', label: 'Clients' },
  { to: '/admin/team', label: 'Team' },
]

function isActive(to: string, exact?: boolean) {
  if (exact) return route.path === to
  return route.path === to || route.path.startsWith(to + '/')
}
</script>

<template>
  <aside class="flex w-60 shrink-0 flex-col border-r border-border bg-bg-elevated">
    <div class="border-b border-border px-5 py-5">
      <router-link to="/admin" class="text-lg font-semibold">Estatein Admin</router-link>
      <p class="mt-1 truncate text-xs text-muted">{{ auth.user?.email }}</p>
    </div>
    <nav class="flex flex-1 flex-col gap-1 p-3">
      <router-link
        v-for="l in links"
        :key="l.to"
        :to="l.to"
        class="rounded-lg px-3 py-2.5 text-sm text-muted transition-colors hover:bg-bg-card hover:text-white"
        :class="{ 'bg-bg-card text-white': isActive(l.to, l.exact) }"
      >
        {{ l.label }}
      </router-link>
    </nav>
    <div class="border-t border-border p-3">
      <router-link to="/" class="mb-2 block rounded-lg px-3 py-2 text-sm text-muted hover:text-white">
        ← Public site
      </router-link>
      <button
        type="button"
        class="w-full rounded-lg px-3 py-2.5 text-start text-sm text-red-400 hover:bg-bg-card"
        @click="auth.logout().then(() => $router.push('/admin/login'))"
      >
        Log out
      </button>
    </div>
  </aside>
</template>
