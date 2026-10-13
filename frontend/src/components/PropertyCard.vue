<script setup lang="ts">
import type { Property } from '@/types'
import { useLocalized } from '@/composables/useLocalized'
import { formatPrice } from '@/utils/format'

defineProps<{ property: Property }>()
const { t, tField } = useLocalized()

function imageUrl(p: Property) {
  return (
    p.primary_image?.url ||
    p.images?.[0]?.url ||
    'https://images.unsplash.com/photo-1613490493576-7fde63acd811?w=800&q=80'
  )
}
</script>

<template>
  <article class="card flex h-full flex-col overflow-hidden">
    <div class="aspect-video overflow-hidden">
      <img
        :src="imageUrl(property)"
        :alt="tField(property.title)"
        class="size-full object-cover transition-transform duration-500 hover:scale-105"
        loading="lazy"
      />
    </div>
    <div class="flex flex-1 flex-col gap-3.5 p-5 md:p-6">
      <h3 class="text-lg font-semibold md:text-xl">{{ tField(property.title) }}</h3>
      <p class="text-sm leading-relaxed text-muted">
        {{ tField(property.description).slice(0, 90) }}…
        <router-link
          :to="`/properties/${property.id}`"
          class="text-white underline underline-offset-2"
        >
          {{ t('common.readMore') }}
        </router-link>
      </p>
      <div class="flex flex-wrap gap-2">
        <span class="chip">{{ property.bedrooms }}-Bedroom</span>
        <span class="chip">{{ property.bathrooms }}-Bathroom</span>
        <span v-if="property.type" class="chip">{{ property.type }}</span>
      </div>
      <div class="mt-auto flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <span class="mb-0.5 block text-xs text-muted">{{ t('common.price') }}</span>
          <span class="text-xl font-semibold">{{ formatPrice(property.price) }}</span>
        </div>
        <router-link
          :to="`/properties/${property.id}`"
          class="btn-primary w-full px-4 py-3 text-sm sm:w-auto"
        >
          {{ t('property.viewDetails') }}
        </router-link>
      </div>
    </div>
  </article>
</template>
