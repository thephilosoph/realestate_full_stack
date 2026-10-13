<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { adminProperties, adminFaqs, adminTestimonials, adminTeam, adminClients, adminFeatures } from '@/api/admin/resources'

const counts = ref<Record<string, number | string>>({
  properties: '—',
  features: '—',
  faqs: '—',
  testimonials: '—',
  clients: '—',
  team: '—',
})

onMounted(async () => {
  try {
    const [p, f, faq, t, c, tm] = await Promise.all([
      adminProperties.list({ perPage: 1 }),
      adminFeatures.list({ perPage: 1 }),
      adminFaqs.list({ perPage: 1 }),
      adminTestimonials.list({ perPage: 1 }),
      adminClients.list({ perPage: 1 }),
      adminTeam.list({ perPage: 1 }),
    ])
    counts.value = {
      properties: p.meta?.total ?? p.data.length,
      features: f.meta?.total ?? f.data.length,
      faqs: faq.meta?.total ?? faq.data.length,
      testimonials: t.meta?.total ?? t.data.length,
      clients: c.meta?.total ?? c.data.length,
      team: tm.meta?.total ?? tm.data.length,
    }
  } catch {
    // leave dashes
  }
})

const cards = [
  { key: 'properties', label: 'Properties', to: '/admin/properties' },
  { key: 'features', label: 'Features', to: '/admin/features' },
  { key: 'faqs', label: 'FAQs', to: '/admin/faqs' },
  { key: 'testimonials', label: 'Testimonials', to: '/admin/testimonials' },
  { key: 'clients', label: 'Clients', to: '/admin/clients' },
  { key: 'team', label: 'Team members', to: '/admin/team' },
]
</script>

<template>
  <div>
    <h1 class="mb-2 text-2xl font-semibold">Dashboard</h1>
    <p class="mb-8 text-sm text-muted">Manage published content for the public Estatein site.</p>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
      <router-link
        v-for="c in cards"
        :key="c.key"
        :to="c.to"
        class="card p-6 hover:border-purple"
      >
        <span class="text-sm text-muted">{{ c.label }}</span>
        <strong class="mt-2 block text-3xl">{{ counts[c.key] }}</strong>
      </router-link>
    </div>
  </div>
</template>
