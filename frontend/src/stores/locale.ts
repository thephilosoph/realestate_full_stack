import { defineStore } from 'pinia'
import { ref } from 'vue'
import type { AppLocale } from '@/i18n'

export const useLocaleStore = defineStore('locale', () => {
  const current = ref<AppLocale>(
    (localStorage.getItem('locale') as AppLocale) || 'en'
  )

  function setLocale(code: AppLocale) {
    current.value = code
    localStorage.setItem('locale', code)
    document.documentElement.lang = code
    document.documentElement.dir = code === 'ar' ? 'rtl' : 'ltr'
  }

  // apply on load
  document.documentElement.lang = current.value
  document.documentElement.dir = current.value === 'ar' ? 'rtl' : 'ltr'

  return { current, setLocale }
})
