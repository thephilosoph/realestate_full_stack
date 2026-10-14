<script setup lang="ts">
import { onMounted, ref } from 'vue'
import {
  adminProperties,
  adminFaqs,
  adminTestimonials,
  adminTeam,
  adminClients,
  adminFeatures,
} from '@/api/admin/resources'
import type { Property } from '@/types'
import { formatPrice } from '@/utils/format'
import { useLocalized } from '@/composables/useLocalized'

const { tField } = useLocalized()

const loading = ref(true)
const loadFailed = ref(false)
const recent = ref<Property[]>([])

const counts = ref<Record<string, number | string>>({
  properties: '—',
  features: '—',
  faqs: '—',
  testimonials: '—',
  clients: '—',
  team: '—',
})

const cards = [
  {
    key: 'properties',
    label: 'Properties',
    sub: 'Listings in the catalog',
    to: '/admin/properties',
    icon: 'M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21',
  },
  {
    key: 'features',
    label: 'Features',
    sub: 'Amenities & highlights',
    to: '/admin/features',
    icon: 'M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456Z',
  },
  {
    key: 'faqs',
    label: 'FAQs',
    sub: 'Answers on the public site',
    to: '/admin/faqs',
    icon: 'M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z',
  },
  {
    key: 'testimonials',
    label: 'Testimonials',
    sub: 'Client reviews',
    to: '/admin/testimonials',
    icon: 'M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z',
  },
  {
    key: 'clients',
    label: 'Clients',
    sub: 'Logos on the homepage',
    to: '/admin/clients',
    icon: 'M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21',
  },
  {
    key: 'team',
    label: 'Team members',
    sub: 'People behind Estatein',
    to: '/admin/team',
    icon: 'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z',
  },
]

const quickActions = [
  { label: 'New property', to: '/admin/properties/new' },
  { label: 'New FAQ', to: '/admin/faqs/new' },
  { label: 'New testimonial', to: '/admin/testimonials/new' },
  { label: 'New team member', to: '/admin/team/new' },
]

onMounted(async () => {
  try {
    const [p, f, faq, t, c, tm, recentRes] = await Promise.all([
      adminProperties.list({ perPage: 1 }),
      adminFeatures.list({ perPage: 1 }),
      adminFaqs.list({ perPage: 1 }),
      adminTestimonials.list({ perPage: 1 }),
      adminClients.list({ perPage: 1 }),
      adminTeam.list({ perPage: 1 }),
      adminProperties.list({ perPage: 5, sort: 'created_at', direction: 'desc' }),
    ])
    counts.value = {
      properties: p.meta?.total ?? p.data.length,
      features: f.meta?.total ?? f.data.length,
      faqs: faq.meta?.total ?? faq.data.length,
      testimonials: t.meta?.total ?? t.data.length,
      clients: c.meta?.total ?? c.data.length,
      team: tm.meta?.total ?? tm.data.length,
    }
    recent.value = recentRes.data
  } catch {
    loadFailed.value = true
  } finally {
    loading.value = false
  }
})

const greeting = (() => {
  const h = new Date().getHours()
  if (h < 12) return 'Good morning'
  if (h < 18) return 'Good afternoon'
  return 'Good evening'
})()
</script>

<template>
  <div>
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
      <div>
        <h1 class="text-2xl font-semibold tracking-tight">{{ greeting }}</h1>
        <p class="mt-1 text-sm text-muted">Here's what's live across the Estatein platform.</p>
      </div>
      <div class="flex flex-wrap gap-2">
        <router-link
          v-for="action in quickActions"
          :key="action.to"
          :to="action.to"
          class="rounded-lg border border-border px-3.5 py-2 text-xs font-medium text-muted transition-colors hover:border-purple/50 hover:bg-purple-soft hover:text-white"
        >
          + {{ action.label }}
        </router-link>
      </div>
    </div>

    <p v-if="loadFailed" class="mb-6 rounded-lg border border-red-900/50 bg-red-950/40 px-4 py-3 text-sm text-red-300">
      Failed to load statistics. Check that the API is running and your session is valid.
    </p>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
      <router-link
        v-for="c in cards"
        :key="c.key"
        :to="c.to"
        class="card group relative overflow-hidden p-5 hover:border-purple/50"
      >
        <div class="flex items-start justify-between">
          <div>
            <span class="text-sm text-muted">{{ c.label }}</span>
            <strong class="mt-2 block text-3xl font-semibold tracking-tight">{{ counts[c.key] }}</strong>
            <span class="mt-1 block text-xs text-soft">{{ c.sub }}</span>
          </div>
          <span
            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-purple-soft transition-colors group-hover:bg-purple"
          >
            <svg class="h-5 w-5 text-purple-hover transition-colors group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" :d="c.icon" />
            </svg>
          </span>
        </div>
        <span class="mt-4 flex items-center gap-1 text-xs font-medium text-purple-hover opacity-0 transition-opacity group-hover:opacity-100">
          Manage
          <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12l-7.5 7.5M21 12H3" />
          </svg>
        </span>
      </router-link>
    </div>

    <div class="card mt-8 overflow-hidden p-0">
      <div class="flex items-center justify-between border-b border-border px-6 py-4">
        <div>
          <h2 class="font-semibold">Recently added properties</h2>
          <p class="mt-0.5 text-xs text-muted">The latest listings in the catalog</p>
        </div>
        <router-link to="/admin/properties" class="text-xs font-medium text-purple-hover hover:text-white">
          View all →
        </router-link>
      </div>

      <div v-if="loading" class="divide-y divide-border">
        <div v-for="i in 3" :key="i" class="flex items-center gap-4 px-6 py-4">
          <div class="h-12 w-12 animate-pulse rounded-lg bg-bg-elevated" />
          <div class="flex-1 space-y-2">
            <div class="h-3.5 w-1/3 animate-pulse rounded bg-bg-elevated" />
            <div class="h-3 w-1/5 animate-pulse rounded bg-bg-elevated" />
          </div>
        </div>
      </div>

      <div v-else-if="recent.length" class="divide-y divide-border">
        <router-link
          v-for="p in recent"
          :key="p.id"
          :to="`/admin/properties/${p.id}`"
          class="flex items-center gap-4 px-6 py-4 transition-colors hover:bg-bg-card-hover"
        >
          <span class="h-12 w-12 shrink-0 overflow-hidden rounded-lg bg-bg-elevated">
            <img
              v-if="p.primary_image?.url"
              :src="p.primary_image.url"
              alt=""
              class="h-full w-full object-cover"
            />
          </span>
          <div class="min-w-0 flex-1">
            <p class="truncate text-sm font-medium">{{ tField(p.title) || 'Untitled' }}</p>
            <p class="truncate text-xs text-muted">
              {{ p.type }}<template v-if="p.location"> · {{ tField(p.location) }}</template>
            </p>
          </div>
          <div class="text-right">
            <p class="text-sm font-semibold">{{ formatPrice(p.price) }}</p>
            <p class="text-xs text-soft">{{ p.bedrooms ?? 0 }} bd · {{ p.bathrooms ?? 0 }} ba</p>
          </div>
        </router-link>
      </div>

      <div v-else class="flex flex-col items-center gap-3 px-6 py-12 text-center">
        <p class="text-sm text-muted">No properties yet.</p>
        <router-link to="/admin/properties/new" class="btn-primary px-4 py-2 text-sm">Add your first property</router-link>
      </div>
    </div>
  </div>
</template>
