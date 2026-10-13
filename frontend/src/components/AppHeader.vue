<script setup lang="ts">
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import { useLocalized } from '@/composables/useLocalized'

const route = useRoute()
const { t, locale, setLocale } = useLocalized()
const menuOpen = ref(false)
const scrolled = ref(false)

const links = computed(() => [
  { name: t('nav.home'), to: '/' },
  { name: t('nav.about'), to: '/about' },
  { name: t('nav.properties'), to: '/properties' },
  { name: t('nav.services'), to: '/services' },
])

function onScroll() {
  scrolled.value = window.scrollY > 16
}

onMounted(() => window.addEventListener('scroll', onScroll, { passive: true }))
onUnmounted(() => window.removeEventListener('scroll', onScroll))

function closeMenu() {
  menuOpen.value = false
}

function toggleLocale() {
  setLocale(locale.value === 'en' ? 'ar' : 'en')
}
</script>

<template>
  <div
    class="relative hidden md:flex items-center justify-center gap-2 border-b border-border bg-bg-elevated px-4 py-2.5 text-sm text-muted"
  >
    <span class="text-purple">✦</span>
    <span>{{ t('promo.text') }}</span>
    <router-link to="/about" class="text-white underline underline-offset-2 hover:text-purple">
      {{ t('promo.learnMore') }}
    </router-link>
  </div>

  <header
    class="sticky top-0 z-50 border-b transition-colors backdrop-blur-md"
    :class="scrolled ? 'bg-bg/95 border-border' : 'bg-bg/85 border-transparent'"
  >
    <div class="container-estate flex h-[72px] md:h-[88px] items-center justify-between gap-4">
      <router-link
        to="/"
        class="flex items-center gap-2.5 text-lg md:text-xl font-semibold"
        @click="closeMenu"
      >
        <span class="size-8 shrink-0 rounded-full bg-linear-to-br from-purple to-[#a685fa]" />
        <span>Estatein</span>
      </router-link>

      <nav
        class="hidden lg:flex items-center gap-1 rounded-xl border border-border bg-bg-elevated p-1.5"
        aria-label="Main"
      >
        <router-link
          v-for="link in links"
          :key="link.to"
          :to="link.to"
          class="rounded-lg px-4 py-2.5 text-sm font-medium text-muted transition-colors hover:bg-bg-card hover:text-white"
          :class="{ 'bg-bg-card text-white': route.path === link.to }"
        >
          {{ link.name }}
        </router-link>
      </nav>

      <div class="hidden lg:flex items-center gap-2">
        <button
          type="button"
          class="rounded-lg border border-border px-2.5 py-1.5 text-xs text-muted hover:text-white"
          @click="toggleLocale"
        >
          {{ locale === 'en' ? 'AR' : 'EN' }}
        </button>
        <router-link to="/contact" class="btn-secondary py-2.5 px-4 text-sm">
          {{ t('nav.contact') }}
        </router-link>
      </div>

      <button
        type="button"
        class="flex flex-col gap-1.5 p-2 lg:hidden"
        aria-label="Toggle menu"
        :aria-expanded="menuOpen"
        @click="menuOpen = !menuOpen"
      >
        <span class="block h-0.5 w-5 rounded bg-white" />
        <span class="block h-0.5 w-5 rounded bg-white" />
        <span class="block h-0.5 w-5 rounded bg-white" />
      </button>
    </div>

    <div
      v-if="menuOpen"
      class="flex flex-col gap-2 border-t border-border bg-bg px-4 py-4 lg:hidden"
    >
      <router-link
        v-for="link in links"
        :key="link.to"
        :to="link.to"
        class="rounded-lg px-4 py-3 text-sm font-medium text-muted hover:bg-bg-elevated hover:text-white"
        @click="closeMenu"
      >
        {{ link.name }}
      </router-link>
      <button
        type="button"
        class="rounded-lg px-4 py-3 text-start text-sm text-muted"
        @click="toggleLocale"
      >
        {{ locale === 'en' ? 'العربية' : 'English' }}
      </button>
      <router-link to="/contact" class="btn-primary mt-2" @click="closeMenu">
        {{ t('nav.contact') }}
      </router-link>
    </div>
  </header>
</template>
