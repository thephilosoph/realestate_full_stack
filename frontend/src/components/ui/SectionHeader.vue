<script setup lang="ts">
defineProps<{
  title: string
  description?: string
  showSpark?: boolean
}>()
</script>

<template>
  <div class="mb-8 flex flex-col gap-4 md:mb-12 md:flex-row md:items-start md:justify-between">
    <div>
      <div v-if="showSpark !== false" class="mb-3 text-purple">✦</div>
      <h2 class="section-title">{{ title }}</h2>
      <p v-if="description" class="section-desc">{{ description }}</p>
    </div>
    <div v-if="$slots.action" class="shrink-0 self-start">
      <slot name="action" />
    </div>
  </div>
</template>
