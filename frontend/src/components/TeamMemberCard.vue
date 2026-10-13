<script setup lang="ts">
import type { TeamMember } from '@/types'
import { useLocalized } from '@/composables/useLocalized'

defineProps<{ member: TeamMember }>()
const { t, tField } = useLocalized()
</script>

<template>
  <article class="card overflow-hidden p-4">
    <div class="relative mb-4 overflow-hidden rounded-xl bg-bg-elevated">
      <img
        v-if="member.photo_url"
        :src="member.photo_url"
        :alt="tField(member.name)"
        class="aspect-square w-full object-cover"
      />
      <div v-else class="aspect-square w-full bg-bg-card-hover" />
      <span
        class="absolute bottom-3 left-1/2 flex size-9 -translate-x-1/2 items-center justify-center rounded-full bg-purple text-sm"
        aria-hidden="true"
      >𝕏</span>
    </div>
    <h3 class="text-center font-semibold">{{ tField(member.name) }}</h3>
    <p class="mb-4 text-center text-sm text-muted">{{ tField(member.role) }}</p>
    <button type="button" class="btn-secondary w-full py-2.5 text-sm">
      {{ t('common.sayHello') }}
    </button>
  </article>
</template>
