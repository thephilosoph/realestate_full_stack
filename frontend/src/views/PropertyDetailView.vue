<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { useProperty } from '@/composables/useProperties'
import { useFaqs } from '@/composables/useContent'
import { useLocalized } from '@/composables/useLocalized'
import { formatPrice } from '@/utils/format'
import FaqCard from '@/components/FaqCard.vue'
import StateBlock from '@/components/ui/StateBlock.vue'
import CtaBanner from '@/components/ui/CtaBanner.vue'
import SectionHeader from '@/components/ui/SectionHeader.vue'

const route = useRoute()
const { t, tField } = useLocalized()
const id = computed(() => route.params.id as string)

const { data: property, isLoading, error, refetch } = useProperty(id)
const { data: faqsRes } = useFaqs(3)
const faqs = computed(() => faqsRes.value?.data ?? [])

const gallery = computed(() => {
  if (!property.value) return []
  if (property.value.images?.length) return property.value.images.map((i) => i.url)
  if (property.value.primary_image?.url) return [property.value.primary_image.url]
  return []
})
</script>

<template>
  <StateBlock
    :loading="isLoading"
    :error="error"
    :empty="!property && !isLoading"
    :empty-text="t('property.notFound')"
    @retry="refetch()"
  >
    <div v-if="property">
      <section class="border-b border-border pt-8 pb-6">
        <div class="container-estate flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
          <div>
            <h1 class="text-2xl font-semibold md:text-3xl">{{ tField(property.title) }}</h1>
            <p class="mt-1 text-sm text-muted">📍 {{ tField(property.location) }}</p>
          </div>
          <div class="md:text-right">
            <span class="block text-xs text-muted">{{ t('common.price') }}</span>
            <span class="text-2xl font-semibold">{{ formatPrice(property.price) }}</span>
          </div>
        </div>
      </section>

      <section class="section pt-8">
        <div class="container-estate">
          <div v-if="gallery.length" class="mb-3 flex gap-2 overflow-x-auto pb-2">
            <img v-for="(src, i) in gallery" :key="i" :src="src" alt="" class="size-16 shrink-0 rounded-lg border border-border object-cover md:size-20" />
          </div>
          <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div class="aspect-4/3 overflow-hidden rounded-2xl border border-border bg-bg-card">
              <img v-if="gallery[0]" :src="gallery[0]" :alt="tField(property.title)" class="size-full object-cover" />
            </div>
            <div class="aspect-4/3 overflow-hidden rounded-2xl border border-border bg-bg-card">
              <img v-if="gallery[1] || gallery[0]" :src="gallery[1] || gallery[0]" alt="" class="size-full object-cover" />
            </div>
          </div>
        </div>
      </section>

      <section class="pb-12">
        <div class="container-estate grid grid-cols-1 gap-5 lg:grid-cols-2">
          <article class="card p-6 md:p-8">
            <h2 class="mb-3 text-lg font-semibold">{{ t('property.description') }}</h2>
            <p class="mb-6 text-sm leading-relaxed text-muted">{{ tField(property.description) }}</p>
            <div class="grid grid-cols-3 gap-4 border-t border-border pt-5">
              <div>
                <span class="block text-xs text-muted">{{ t('property.bedrooms') }}</span>
                <strong class="text-lg">{{ String(property.bedrooms).padStart(2, '0') }}</strong>
              </div>
              <div>
                <span class="block text-xs text-muted">{{ t('property.bathrooms') }}</span>
                <strong class="text-lg">{{ String(property.bathrooms).padStart(2, '0') }}</strong>
              </div>
              <div>
                <span class="block text-xs text-muted">{{ t('property.type') }}</span>
                <strong class="text-lg">{{ property.type || '—' }}</strong>
              </div>
            </div>
          </article>
          <article class="card p-6 md:p-8">
            <h2 class="mb-4 text-lg font-semibold">{{ t('property.features') }}</h2>
            <ul v-if="property.features?.length" class="flex flex-col gap-3">
              <li v-for="f in property.features" :key="f.id" class="flex gap-3 text-sm text-muted">
                <span class="text-purple">⚡</span>
                {{ tField(f.name) }}
              </li>
            </ul>
            <p v-else class="text-sm text-muted">{{ t('property.noFeatures') }}</p>
          </article>
        </div>
      </section>

      <section class="section pt-0">
        <div class="container-estate">
          <SectionHeader :title="t('home.faqTitle')" :description="t('home.faqDesc')" />
          <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
            <FaqCard v-for="item in faqs" :key="item.id" :item="item" />
          </div>
        </div>
      </section>

      <CtaBanner />
    </div>
  </StateBlock>
</template>
