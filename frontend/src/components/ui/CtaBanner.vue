<script setup lang="ts">
import { useI18n } from 'vue-i18n'

withDefaults(
  defineProps<{
    title?: string
    description?: string
    to?: string
    buttonLabel?: string
  }>(),
  {
    to: '/properties',
  }
)

const { t } = useI18n()
</script>

<template>
  <section class="pb-16 md:pb-20">
    <div class="container-estate">
      <div
        class="flex flex-col items-start justify-between gap-6 rounded-2xl border border-border bg-bg-elevated p-8 md:flex-row md:items-center md:p-12"
        style="
          background-image: radial-gradient(
            ellipse at top right,
            rgba(112, 59, 247, 0.12),
            transparent 50%
          );
        "
      >
        <div>
          <h2 class="mb-2 text-2xl font-semibold md:text-[28px]">
            {{ title || t('cta.title') }}
          </h2>
          <p class="max-w-xl text-[15px] leading-relaxed text-muted">
            {{ description || t('cta.body') }}
          </p>
        </div>
        <router-link :to="to" class="btn-primary shrink-0">
          {{ buttonLabel || t('cta.button') }}
        </router-link>
      </div>
    </div>
  </section>
</template>
