<script setup lang="ts">
defineProps<{ title: string }>()
</script>

<template>
  <div class="card relative flex min-h-[120px] flex-col gap-3 p-6">
    <div class="flex size-11 items-center justify-center rounded-full bg-purple-soft text-purple">
      <slot name="icon">◆</slot>
    </div>
    <h3 class="max-w-[180px] text-[15px] font-medium leading-snug">{{ title }}</h3>
    <span class="absolute right-5 top-5 text-soft" aria-hidden="true">↗</span>
  </div>
</template>
