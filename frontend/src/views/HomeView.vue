<script setup lang="ts">
import { computed } from 'vue'
import { useProperties } from '@/composables/useProperties'
import { useFaqs, useTestimonials } from '@/composables/useContent'
import { useLocalized } from '@/composables/useLocalized'
import PropertyCard from '@/components/PropertyCard.vue'
import TestimonialCard from '@/components/TestimonialCard.vue'
import FaqCard from '@/components/FaqCard.vue'
import FeatureTile from '@/components/ui/FeatureTile.vue'
import SectionHeader from '@/components/ui/SectionHeader.vue'
import StateBlock from '@/components/ui/StateBlock.vue'
import CtaBanner from '@/components/ui/CtaBanner.vue'
import BaseButton from '@/components/ui/BaseButton.vue'

const { t } = useLocalized()

const featureKeys = ['feature1', 'feature2', 'feature3', 'feature4'] as const

const stats = computed(() => [
  { value: '200+', label: t('home.statCustomers') },
  { value: '10K+', label: t('home.statProperties') },
  { value: '16+', label: t('home.statYears') },
])

const {
  data: propertiesRes,
  isLoading: propsLoading,
  error: propsError,
  refetch: refetchProps,
} = useProperties({ perPage: 3, sort: 'sort_order' })
const properties = computed(() => propertiesRes.value?.data ?? [])

const {
  data: testimonialsRes,
  isLoading: tLoading,
  error: tError,
  refetch: refetchT,
} = useTestimonials(3)
const testimonials = computed(() => testimonialsRes.value?.data ?? [])

const {
  data: faqsRes,
  isLoading: fLoading,
  error: fError,
  refetch: refetchF,
} = useFaqs(3)
const faqs = computed(() => faqsRes.value?.data ?? [])
</script>

<template>
  <div>
    <section class="overflow-hidden pb-10 pt-8 md:pb-14 md:pt-12">
      <div class="container-estate grid items-center gap-10 lg:grid-cols-2 lg:gap-12">
        <div class="order-2 lg:order-1">
          <h1 class="mb-5 text-[32px] font-semibold leading-[1.15] tracking-tight md:text-[40px] xl:text-[52px]">
            {{ t('home.heroTitle') }}
          </h1>
          <p class="mb-8 max-w-md text-base leading-relaxed text-muted md:text-[17px]">
            {{ t('home.heroBody') }}
          </p>
          <div class="mb-10 flex flex-wrap gap-3">
            <router-link to="/about" class="btn-ghost">{{ t('common.learnMore') }}</router-link>
            <router-link to="/properties" class="btn-primary">{{ t('common.browseProperties') }}</router-link>
          </div>
          <div class="flex flex-wrap gap-3">
            <div
              v-for="s in stats"
              :key="s.label"
              class="min-w-[120px] rounded-xl border border-border bg-bg-elevated px-4 py-4 md:min-w-[140px]"
            >
              <strong class="mb-1 block text-xl font-semibold md:text-2xl">{{ s.value }}</strong>
              <span class="text-xs text-muted">{{ s.label }}</span>
            </div>
          </div>
        </div>
        <div class="relative order-1 lg:order-2">
          <div class="aspect-4/5 max-h-[520px] overflow-hidden rounded-[20px] border border-border xl:h-[600px] xl:max-h-none xl:aspect-auto">
            <img
              src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=900&q=80"
              alt=""
              class="size-full object-cover"
            />
          </div>
        </div>
      </div>
    </section>

    <section id="features" class="pb-10">
      <div class="container-estate grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <FeatureTile v-for="key in featureKeys" :key="key" :title="t(`home.${key}`)" />
      </div>
    </section>

    <section class="section">
      <div class="container-estate">
        <SectionHeader :title="t('home.featuredTitle')" :description="t('home.featuredDesc')">
          <template #action>
            <router-link to="/properties" class="btn-secondary">{{ t('home.viewAllProperties') }}</router-link>
          </template>
        </SectionHeader>
        <StateBlock
          :loading="propsLoading"
          :error="propsError"
          :empty="!properties.length"
          :empty-text="t('home.emptyProperties')"
          @retry="refetchProps()"
        >
          <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
            <PropertyCard v-for="p in properties" :key="p.id" :property="p" />
          </div>
        </StateBlock>
      </div>
    </section>

    <section id="testimonials" class="section">
      <div class="container-estate">
        <SectionHeader :title="t('home.testimonialsTitle')" :description="t('home.testimonialsDesc')">
          <template #action>
            <BaseButton variant="secondary" type="button">{{ t('home.viewAllTestimonials') }}</BaseButton>
          </template>
        </SectionHeader>
        <StateBlock
          :loading="tLoading"
          :error="tError"
          :empty="!testimonials.length"
          :empty-text="t('home.emptyTestimonials')"
          @retry="refetchT()"
        >
          <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
            <TestimonialCard v-for="item in testimonials" :key="item.id" :item="item" />
          </div>
        </StateBlock>
      </div>
    </section>

    <section id="faq" class="section">
      <div class="container-estate">
        <SectionHeader :title="t('home.faqTitle')" :description="t('home.faqDesc')">
          <template #action>
            <BaseButton variant="secondary" type="button">{{ t('home.viewAllFaqs') }}</BaseButton>
          </template>
        </SectionHeader>
        <StateBlock
          :loading="fLoading"
          :error="fError"
          :empty="!faqs.length"
          :empty-text="t('home.emptyFaqs')"
          @retry="refetchF()"
        >
          <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
            <FaqCard v-for="item in faqs" :key="item.id" :item="item" />
          </div>
        </StateBlock>
      </div>
    </section>

    <CtaBanner />
  </div>
</template>
