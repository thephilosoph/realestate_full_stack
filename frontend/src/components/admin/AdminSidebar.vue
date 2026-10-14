<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const route = useRoute()
const auth = useAuthStore()

type NavLink = { to: string; label: string; icon: string; exact?: boolean }

const groups: { label: string; links: NavLink[] }[] = [
  {
    label: 'Overview',
    links: [
      {
        to: '/admin',
        label: 'Dashboard',
        exact: true,
        icon: 'M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z',
      },
    ],
  },
  {
    label: 'Listings',
    links: [
      {
        to: '/admin/properties',
        label: 'Properties',
        icon: 'M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21',
      },
      {
        to: '/admin/features',
        label: 'Features',
        icon: 'M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z',
      },
    ],
  },
  {
    label: 'Site content',
    links: [
      {
        to: '/admin/faqs',
        label: 'FAQs',
        icon: 'M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z',
      },
      {
        to: '/admin/testimonials',
        label: 'Testimonials',
        icon: 'M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z',
      },
      {
        to: '/admin/clients',
        label: 'Clients',
        icon: 'M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21',
      },
      {
        to: '/admin/team',
        label: 'Team',
        icon: 'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z',
      },
    ],
  },
]

const initials = computed(() =>
  (auth.user?.name || auth.user?.email || 'A')
    .split(' ')
    .map((part) => part[0])
    .slice(0, 2)
    .join('')
    .toUpperCase(),
)

function isActive(to: string, exact?: boolean) {
  if (exact) return route.path === to
  return route.path === to || route.path.startsWith(to + '/')
}
</script>

<template>
  <aside class="flex w-64 shrink-0 flex-col border-r border-border bg-bg-elevated">
    <div class="border-b border-border px-5 py-5">
      <router-link to="/admin" class="flex items-center gap-2.5">
        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple">
          <svg class="h-4.5 w-4.5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5 12 3l9 7.5M5 9.75V21h14V9.75" />
          </svg>
        </span>
        <span class="text-base font-semibold tracking-tight">Estatein</span>
        <span class="rounded-full border border-purple/40 bg-purple-soft px-2 py-0.5 text-[10px] font-medium text-purple-hover">ADMIN</span>
      </router-link>
    </div>

    <nav class="flex-1 space-y-6 overflow-y-auto px-3 py-5">
      <div v-for="group in groups" :key="group.label">
        <p class="mb-1.5 px-3 text-[11px] font-semibold uppercase tracking-wider text-soft">{{ group.label }}</p>
        <div class="flex flex-col gap-0.5">
          <router-link
            v-for="l in group.links"
            :key="l.to"
            :to="l.to"
            class="group relative flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition-colors"
            :class="isActive(l.to, l.exact)
              ? 'bg-purple-soft text-white'
              : 'text-muted hover:bg-bg-card hover:text-white'"
          >
            <span
              v-if="isActive(l.to, l.exact)"
              class="absolute left-0 top-1/2 h-5 w-0.5 -translate-y-1/2 rounded-full bg-purple"
            />
            <svg class="h-4.5 w-4.5 shrink-0 transition-colors" :class="isActive(l.to, l.exact) ? 'text-purple-hover' : 'text-soft group-hover:text-muted'" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" :d="l.icon" />
            </svg>
            {{ l.label }}
          </router-link>
        </div>
      </div>
    </nav>

    <div class="border-t border-border p-3">
      <div class="mb-3 flex items-center gap-3 rounded-lg bg-bg-card px-3 py-2.5">
        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-purple-soft text-xs font-semibold text-purple-hover">
          {{ initials }}
        </span>
        <div class="min-w-0">
          <p class="truncate text-sm font-medium text-white">{{ auth.user?.name || 'Admin' }}</p>
          <p class="truncate text-xs text-muted">{{ auth.user?.email }}</p>
        </div>
      </div>

      <div class="flex gap-2">
        <router-link to="/" class="flex flex-1 items-center justify-center gap-1.5 rounded-lg border border-border px-3 py-2 text-xs text-muted transition-colors hover:border-border-light hover:text-white">
          <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm10.5-9h-4.5m-12 0H1.5" />
          </svg>
          View site
        </router-link>
        <button
          type="button"
          class="flex items-center justify-center gap-1.5 rounded-lg border border-red-900/50 bg-red-950/30 px-3 py-2 text-xs text-red-400 transition-colors hover:bg-red-950/50"
          @click="auth.logout().then(() => $router.push('/admin/login'))"
        >
          <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
          </svg>
          Log out
        </button>
      </div>
    </div>
  </aside>
</template>
