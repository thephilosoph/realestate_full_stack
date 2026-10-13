<script setup lang="ts">
import { useI18n } from 'vue-i18n'

defineProps<{
  loading?: boolean
  error?: Error | null | unknown
  empty?: boolean
  emptyText?: string
}>()

const emit = defineEmits<{ retry: [] }>()
const { t } = useI18n()
</script>

<template>
  <div
    v-if="loading"
    class="rounded-2xl border border-border bg-bg-card px-6 py-12 text-center text-muted"
    role="status"
  >
    {{ t('common.loading') }}
  </div>
  <div
    v-else-if="error"
    class="rounded-2xl border border-red-900/50 bg-red-950/30 px-6 py-12 text-center text-red-300"
    role="alert"
  >
    <p class="font-medium">{{ t('common.errorTitle') }}</p>
    <p class="mt-1 text-sm opacity-80">
      {{ error instanceof Error ? error.message : t('common.errorHint') }}
    </p>
    <button
      type="button"
      class="btn-secondary mt-4 text-sm"
      @click="emit('retry')"
    >
      {{ t('common.retry') }}
    </button>
  </div>
  <div
    v-else-if="empty"
    class="rounded-2xl border border-border bg-bg-card px-6 py-12 text-center text-muted"
  >
    {{ emptyText || t('common.empty') }}
  </div>
  <slot v-else />
</template>
