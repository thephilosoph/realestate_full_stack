<script setup lang="ts">
const model = defineModel<{ en: string; ar: string }>({ required: true })

defineProps<{
  label: string
  multiline?: boolean
  required?: boolean
}>()
</script>

<template>
  <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
    <label class="flex flex-col gap-1.5 text-sm">
      <span class="text-muted">{{ label }} (EN)</span>
      <textarea
        v-if="multiline"
        v-model="model.en"
        rows="3"
        :required="required"
        class="rounded-lg border border-border-light bg-bg px-3 py-2.5 outline-none focus:border-purple"
      />
      <input
        v-else
        v-model="model.en"
        type="text"
        :required="required"
        class="rounded-lg border border-border-light bg-bg px-3 py-2.5 outline-none focus:border-purple"
      />
    </label>
    <label class="flex flex-col gap-1.5 text-sm">
      <span class="text-muted">{{ label }} (AR)</span>
      <textarea
        v-if="multiline"
        v-model="model.ar"
        rows="3"
        :required="required"
        dir="rtl"
        class="rounded-lg border border-border-light bg-bg px-3 py-2.5 outline-none focus:border-purple"
      />
      <input
        v-else
        v-model="model.ar"
        type="text"
        :required="required"
        dir="rtl"
        class="rounded-lg border border-border-light bg-bg px-3 py-2.5 outline-none focus:border-purple"
      />
    </label>
  </div>
</template>
