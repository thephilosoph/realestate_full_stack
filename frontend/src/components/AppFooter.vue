<script setup lang="ts">
import { useLocalized } from '@/composables/useLocalized'

const year = new Date().getFullYear()
const { t } = useLocalized()
</script>

<template>
  <footer class="border-t border-border bg-bg pt-12 md:pt-16">
    <div class="container-estate grid grid-cols-2 gap-8 pb-10 md:grid-cols-3 xl:grid-cols-[1.5fr_repeat(5,1fr)]">
      <div class="col-span-2 md:col-span-3 xl:col-span-1">
        <router-link to="/" class="mb-5 flex items-center gap-2.5 text-lg font-semibold">
          <span class="size-7 rounded-full bg-linear-to-br from-purple to-[#a685fa]" />
          Estatein
        </router-link>
        <form class="flex max-w-xs items-center gap-2 rounded-[10px] border border-border bg-bg-elevated px-3 py-2" @submit.prevent>
          <span class="text-muted">✉</span>
          <input
            type="email"
            :placeholder="t('footer.emailPlaceholder')"
            class="min-w-0 flex-1 bg-transparent text-sm outline-none placeholder:text-soft"
          />
          <button type="submit" class="text-purple" aria-label="Subscribe">➤</button>
        </form>
      </div>

      <div>
        <h4 class="mb-4 text-[15px] font-semibold">{{ t('footer.home') }}</h4>
        <ul class="flex flex-col gap-2.5 text-sm text-muted">
          <li><router-link to="/" class="hover:text-white">{{ t('footer.heroSection') }}</router-link></li>
          <li><router-link to="/#features" class="hover:text-white">{{ t('footer.features') }}</router-link></li>
          <li><router-link to="/properties" class="hover:text-white">{{ t('footer.properties') }}</router-link></li>
          <li><router-link to="/#testimonials" class="hover:text-white">{{ t('footer.testimonials') }}</router-link></li>
          <li><router-link to="/#faq" class="hover:text-white">{{ t('footer.faqs') }}</router-link></li>
        </ul>
      </div>
      <div>
        <h4 class="mb-4 text-[15px] font-semibold">{{ t('footer.about') }}</h4>
        <ul class="flex flex-col gap-2.5 text-sm text-muted">
          <li><router-link to="/about" class="hover:text-white">{{ t('footer.ourStory') }}</router-link></li>
          <li><router-link to="/about" class="hover:text-white">{{ t('footer.ourWorks') }}</router-link></li>
          <li><router-link to="/about" class="hover:text-white">{{ t('footer.howItWorks') }}</router-link></li>
          <li><router-link to="/about" class="hover:text-white">{{ t('footer.ourTeam') }}</router-link></li>
          <li><router-link to="/about" class="hover:text-white">{{ t('footer.ourClients') }}</router-link></li>
        </ul>
      </div>
      <div>
        <h4 class="mb-4 text-[15px] font-semibold">{{ t('footer.properties') }}</h4>
        <ul class="flex flex-col gap-2.5 text-sm text-muted">
          <li><router-link to="/properties" class="hover:text-white">{{ t('footer.portfolio') }}</router-link></li>
          <li><router-link to="/properties" class="hover:text-white">{{ t('footer.categories') }}</router-link></li>
        </ul>
      </div>
      <div>
        <h4 class="mb-4 text-[15px] font-semibold">{{ t('footer.services') }}</h4>
        <ul class="flex flex-col gap-2.5 text-sm text-muted">
          <li><router-link to="/services" class="hover:text-white">{{ t('footer.valuation') }}</router-link></li>
          <li><router-link to="/services" class="hover:text-white">{{ t('footer.marketing') }}</router-link></li>
          <li><router-link to="/services" class="hover:text-white">{{ t('footer.negotiation') }}</router-link></li>
          <li><router-link to="/services" class="hover:text-white">{{ t('footer.closing') }}</router-link></li>
          <li><router-link to="/services" class="hover:text-white">{{ t('footer.propMgmt') }}</router-link></li>
        </ul>
      </div>
      <div>
        <h4 class="mb-4 text-[15px] font-semibold">{{ t('footer.contact') }}</h4>
        <ul class="flex flex-col gap-2.5 text-sm text-muted">
          <li><router-link to="/contact" class="hover:text-white">{{ t('footer.contactForm') }}</router-link></li>
          <li><router-link to="/contact" class="hover:text-white">{{ t('footer.ourOffices') }}</router-link></li>
        </ul>
      </div>
    </div>

    <div class="container-estate flex flex-col gap-4 border-t border-border py-6 text-sm text-muted sm:flex-row sm:items-center sm:justify-between">
      <div class="flex flex-wrap gap-4">
        <span>©{{ year }} Estatein. {{ t('footer.rights') }}</span>
        <a href="#" class="hover:text-white">{{ t('footer.terms') }}</a>
      </div>
      <div class="flex gap-3">
        <a href="#" class="flex size-9 items-center justify-center rounded-full bg-bg-elevated hover:bg-border" aria-label="Facebook">f</a>
        <a href="#" class="flex size-9 items-center justify-center rounded-full bg-bg-elevated hover:bg-border" aria-label="LinkedIn">in</a>
        <a href="#" class="flex size-9 items-center justify-center rounded-full bg-bg-elevated hover:bg-border" aria-label="Twitter">𝕏</a>
        <a href="#" class="flex size-9 items-center justify-center rounded-full bg-bg-elevated hover:bg-border" aria-label="YouTube">▶</a>
      </div>
    </div>
  </footer>
</template>