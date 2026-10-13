<script setup lang="ts">
import type { Client } from '@/types'
import { useLocalized } from '@/composables/useLocalized'

defineProps<{ client: Client }>()
const { t, tField } = useLocalized()
</script>

<template>
  <article class="card p-7">
    <div class="mb-4 flex items-start justify-between gap-4">
      <div class="flex items-center gap-3">
        <img
          v-if="client.logo_url"
          :src="client.logo_url"
          :alt="tField(client.name)"
          class="h-10 w-auto object-contain"
        />
        <h3 class="text-xl font-semibold">{{ tField(client.name) }}</h3>
      </div>
      <a
        v-if="client.website_url"
        :href="client.website_url"
        target="_blank"
        rel="noopener"
        class="btn-ghost py-2 px-4 text-sm"
      >
        {{ t('common.visitWebsite') }}
      </a>
    </div>
  </article>
</template>
