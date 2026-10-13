<script setup lang="ts">
import { computed } from 'vue'
import { useLocalized } from '@/composables/useLocalized'
import CtaBanner from '@/components/ui/CtaBanner.vue'

const { t } = useLocalized()

const contacts = computed(() => [
  { label: t('contact.emailLabel'), icon: '✉' },
  { label: t('contact.phoneLabel'), icon: '☎' },
  { label: t('contact.hqLabel'), icon: '📍' },
  { label: t('contact.socialLabel'), icon: '◎' },
])

const offices = computed(() => [
  {
    type: t('contact.hqType'),
    address: t('contact.hqAddress'),
    body: t('contact.hqBody'),
  },
  {
    type: t('contact.regType'),
    address: t('contact.regAddress'),
    body: t('contact.regBody'),
  },
])
</script>

<template>
  <div>
    <section class="section pb-8 pt-10 md:pt-14">
      <div class="container-estate max-w-3xl">
        <h1 class="section-title">{{ t('contact.title') }}</h1>
        <p class="section-desc">{{ t('contact.subtitle') }}</p>
      </div>
    </section>

    <section class="pb-12">
      <div class="container-estate grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <div
          v-for="c in contacts"
          :key="c.label"
          class="card relative flex min-h-[110px] flex-col justify-center gap-2 p-6"
        >
          <span class="text-purple">{{ c.icon }}</span>
          <strong class="text-[15px] font-medium">{{ c.label }}</strong>
          <span class="absolute right-5 top-5 text-soft rtl:right-auto rtl:left-5">↗</span>
        </div>
      </div>
    </section>

    <section class="section pt-0">
      <div class="container-estate">
        <div class="mb-3 text-purple">✦</div>
        <h2 class="section-title">{{ t('contact.connectTitle') }}</h2>
        <p class="section-desc mb-10">{{ t('contact.connectDesc') }}</p>
        <form class="card grid grid-cols-1 gap-4 p-6 md:grid-cols-3 md:p-8" @submit.prevent>
          <label class="flex flex-col gap-2 text-sm">
            {{ t('contact.firstName') }}
            <input type="text" :placeholder="t('form.firstNamePh')" class="rounded-[10px] border border-border-light bg-bg px-4 py-3 outline-none focus:border-purple" />
          </label>
          <label class="flex flex-col gap-2 text-sm">
            {{ t('contact.lastName') }}
            <input type="text" :placeholder="t('form.lastNamePh')" class="rounded-[10px] border border-border-light bg-bg px-4 py-3 outline-none focus:border-purple" />
          </label>
          <label class="flex flex-col gap-2 text-sm">
            {{ t('contact.email') }}
            <input type="email" :placeholder="t('form.emailPh')" class="rounded-[10px] border border-border-light bg-bg px-4 py-3 outline-none focus:border-purple" />
          </label>
          <label class="flex flex-col gap-2 text-sm">
            {{ t('contact.phone') }}
            <input type="tel" :placeholder="t('form.phonePh')" class="rounded-[10px] border border-border-light bg-bg px-4 py-3 outline-none focus:border-purple" />
          </label>
          <label class="flex flex-col gap-2 text-sm">
            {{ t('contact.inquiryType') }}
            <select class="rounded-[10px] border border-border-light bg-bg px-4 py-3 text-muted outline-none focus:border-purple">
              <option>{{ t('contact.inquiryType') }}</option>
            </select>
          </label>
          <label class="flex flex-col gap-2 text-sm">
            {{ t('contact.hearAbout') }}
            <select class="rounded-[10px] border border-border-light bg-bg px-4 py-3 text-muted outline-none focus:border-purple">
              <option>{{ t('contact.hearAbout') }}</option>
            </select>
          </label>
          <label class="flex flex-col gap-2 text-sm md:col-span-3">
            {{ t('contact.message') }}
            <textarea rows="5" :placeholder="t('form.messagePh')" class="rounded-[10px] border border-border-light bg-bg px-4 py-3 outline-none focus:border-purple" />
          </label>
          <div class="flex flex-col gap-4 md:col-span-3 md:flex-row md:items-center md:justify-between">
            <label class="flex items-center gap-2 text-sm text-muted">
              <input type="checkbox" class="accent-purple" />
              {{ t('common.termsAgree') }}
            </label>
            <button type="submit" class="btn-primary">{{ t('common.sendMessage') }}</button>
          </div>
        </form>
      </div>
    </section>

    <section class="section pt-0">
      <div class="container-estate">
        <div class="mb-3 text-purple">✦</div>
        <h2 class="section-title">{{ t('contact.officesTitle') }}</h2>
        <p class="section-desc mb-6">{{ t('contact.officesDesc') }}</p>
        <div class="mb-6 flex flex-wrap gap-2">
          <button type="button" class="btn-primary py-2 px-4 text-sm">{{ t('common.all') }}</button>
          <button type="button" class="btn-ghost py-2 px-4 text-sm">{{ t('contact.regional') }}</button>
          <button type="button" class="btn-ghost py-2 px-4 text-sm">{{ t('contact.international') }}</button>
        </div>
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
          <article v-for="o in offices" :key="o.type" class="card p-7">
            <span class="mb-2 block text-sm text-muted">{{ o.type }}</span>
            <h3 class="mb-3 text-xl font-semibold">{{ o.address }}</h3>
            <p class="mb-5 text-sm leading-relaxed text-muted">{{ o.body }}</p>
            <button type="button" class="btn-primary w-full">{{ t('contact.getDirection') }}</button>
          </article>
        </div>
      </div>
    </section>

    <section class="section pt-0">
      <div class="container-estate">
        <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
          <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=600&q=80" alt="" class="aspect-video rounded-xl object-cover" />
          <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=600&q=80" alt="" class="aspect-video rounded-xl object-cover" />
          <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=600&q=80" alt="" class="aspect-video rounded-xl object-cover md:row-span-2 md:h-full md:aspect-auto" />
          <img src="https://images.unsplash.com/photo-1556761175-5973dc0f32e7?w=600&q=80" alt="" class="aspect-video rounded-xl object-cover" />
          <div class="col-span-2 flex flex-col justify-end rounded-xl border border-border bg-bg-card p-6">
            <div class="mb-2 text-purple">✦</div>
            <h3 class="mb-2 text-xl font-semibold">{{ t('contact.galleryTitle') }}</h3>
            <p class="text-sm text-muted">{{ t('contact.galleryDesc') }}</p>
          </div>
        </div>
      </div>
    </section>

    <CtaBanner />
  </div>
</template>