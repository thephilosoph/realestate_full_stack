<script setup lang="ts">
import type { Testimonial } from '@/types'
import { useLocalized } from '@/composables/useLocalized'

defineProps<{ item: Testimonial }>()
const { tField } = useLocalized()
</script>

<template>
  <article class="card flex flex-col gap-3.5 p-6 md:p-7">
    <div class="text-sm tracking-wider text-yellow-400" :aria-label="`${item.rating} stars`">
      {{ '★'.repeat(item.rating || 5) }}
    </div>
    <h3 class="text-lg font-semibold">{{ tField(item.title) }}</h3>
    <p class="flex-1 text-sm leading-relaxed text-muted">{{ tField(item.body) }}</p>
    <div class="mt-2 flex items-center gap-3">
      <img
        v-if="item.avatar_url"
        :src="item.avatar_url"
        :alt="tField(item.name)"
        class="size-11 rounded-full object-cover"
      />
      <div v-else class="size-11 rounded-full bg-linear-to-br from-purple to-[#a685fa]" />
      <div>
        <strong class="block text-sm">{{ tField(item.name) }}</strong>
        <span class="text-[13px] text-muted">{{ tField(item.location) }}</span>
      </div>
    </div>
  </article>
</template>
