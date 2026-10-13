<script setup lang="ts">
import { computed } from 'vue'
import { useLocalized } from '@/composables/useLocalized'
import FeatureTile from '@/components/ui/FeatureTile.vue'
import CtaBanner from '@/components/ui/CtaBanner.vue'

const { t } = useLocalized()

const topFeatures = computed(() => [
  t('services.feature1'),
  t('services.feature2'),
  t('services.feature3'),
  t('services.feature4'),
])

const selling = computed(() => [
  { title: t('services.sell1Title'), body: t('services.sell1Body') },
  { title: t('services.sell2Title'), body: t('services.sell2Body') },
  { title: t('services.sell3Title'), body: t('services.sell3Body') },
  { title: t('services.sell4Title'), body: t('services.sell4Body') },
])

const management = computed(() => [
  { title: t('services.mgmt1Title'), body: t('services.mgmt1Body') },
  { title: t('services.mgmt2Title'), body: t('services.mgmt2Body') },
  { title: t('services.mgmt3Title'), body: t('services.mgmt3Body') },
  { title: t('services.mgmt4Title'), body: t('services.mgmt4Body') },
])

const investment = computed(() => [
  { title: t('services.inv1Title'), body: t('services.inv1Body') },
  { title: t('services.inv2Title'), body: t('services.inv2Body') },
  { title: t('services.inv3Title'), body: t('services.inv3Body') },
  { title: t('services.inv4Title'), body: t('services.inv4Body') },
])
</script>

<template>
  <div>
    <section class="section pb-8 pt-10 md:pt-14">
      <div class="container-estate max-w-3xl">
        <h1 class="section-title">{{ t('services.title') }}</h1>
        <p class="section-desc">{{ t('services.subtitle') }}</p>
      </div>
    </section>

    <section class="pb-12">
      <div class="container-estate grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <FeatureTile v-for="f in topFeatures" :key="f" :title="f" />
      </div>
    </section>

    <section class="section pt-0">
      <div class="container-estate">
        <div class="mb-3 text-purple">✦</div>
        <h2 class="section-title">{{ t('services.unlockTitle') }}</h2>
        <p class="section-desc mb-10">{{ t('services.unlockDesc') }}</p>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
          <article v-for="s in selling" :key="s.title" class="card p-6">
            <div class="mb-3 flex items-center gap-3">
              <span class="flex size-10 items-center justify-center rounded-full bg-purple-soft text-purple">●</span>
              <h3 class="font-semibold">{{ s.title }}</h3>
            </div>
            <p class="text-sm text-muted">{{ s.body }}</p>
          </article>
          <article
            class="card flex flex-col justify-between p-6 md:col-span-2 xl:col-span-1"
            style="background-image: radial-gradient(ellipse at center, rgba(112,59,247,0.15), transparent 70%)"
          >
            <div>
              <h3 class="mb-2 text-lg font-semibold">{{ t('services.sellCtaTitle') }}</h3>
              <p class="text-sm text-muted">{{ t('services.sellCtaBody') }}</p>
            </div>
            <router-link to="/contact" class="btn-secondary mt-6 self-start">{{ t('common.learnMore') }}</router-link>
          </article>
        </div>
      </div>
    </section>

    <section class="section pt-0">
      <div class="container-estate">
        <div class="mb-3 text-purple">✦</div>
        <h2 class="section-title">{{ t('services.manageTitle') }}</h2>
        <p class="section-desc mb-10">{{ t('services.manageDesc') }}</p>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
          <article v-for="s in management" :key="s.title" class="card p-6">
            <div class="mb-3 flex items-center gap-3">
              <span class="flex size-10 items-center justify-center rounded-full bg-purple-soft text-purple">●</span>
              <h3 class="font-semibold">{{ s.title }}</h3>
            </div>
            <p class="text-sm text-muted">{{ s.body }}</p>
          </article>
          <article
            class="card flex flex-col justify-between p-6 md:col-span-2 xl:col-span-1"
            style="background-image: radial-gradient(ellipse at center, rgba(112,59,247,0.15), transparent 70%)"
          >
            <div>
              <h3 class="mb-2 text-lg font-semibold">{{ t('services.mgmtCtaTitle') }}</h3>
              <p class="text-sm text-muted">{{ t('services.mgmtCtaBody') }}</p>
            </div>
            <router-link to="/contact" class="btn-secondary mt-6 self-start">{{ t('common.learnMore') }}</router-link>
          </article>
        </div>
      </div>
    </section>

    <section class="section pt-0">
      <div class="container-estate">
        <div class="mb-3 text-purple">✦</div>
        <div class="mb-10 grid items-start gap-8 lg:grid-cols-2">
          <div>
            <h2 class="section-title">{{ t('services.investTitle') }}</h2>
            <p class="section-desc">{{ t('services.investDesc') }}</p>
          </div>
          <article
            class="card p-6"
            style="background-image: radial-gradient(ellipse at center, rgba(112,59,247,0.15), transparent 70%)"
          >
            <h3 class="mb-2 font-semibold">{{ t('services.invCtaTitle') }}</h3>
            <p class="mb-4 text-sm text-muted">{{ t('services.invCtaBody') }}</p>
            <router-link to="/contact" class="btn-secondary">{{ t('common.learnMore') }}</router-link>
          </article>
        </div>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
          <article v-for="s in investment" :key="s.title" class="card p-6">
            <div class="mb-3 flex items-center gap-3">
              <span class="flex size-10 items-center justify-center rounded-full bg-purple-soft text-purple">●</span>
              <h3 class="font-semibold">{{ s.title }}</h3>
            </div>
            <p class="text-sm text-muted">{{ s.body }}</p>
          </article>
        </div>
      </div>
    </section>

    <CtaBanner />
  </div>
</template>