<script setup lang="ts">
import type { Faq } from '@/types'
import { useLocalized } from '@/composables/useLocalized'

defineProps<{ item: Faq }>()
const { t, tField } = useLocalized()
</script>

<template>
  <article class="card flex flex-col gap-3 p-6 md:p-7">
    <h3 class="text-[17px] font-semibold leading-snug">{{ tField(item.question) }}</h3>
    <p class="flex-1 text-sm leading-relaxed text-muted">{{ tField(item.answer) }}</p>
    <button type="button" class="btn-ghost mt-2 self-start px-4 py-2.5 text-sm">
      {{ t('common.readMore') }}
    </button>
  </article>
</template>
