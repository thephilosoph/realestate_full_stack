import { createI18n } from 'vue-i18n'
import en from '@/locales/en.json'
import ar from '@/locales/ar.json'

const saved = (localStorage.getItem('locale') as 'en' | 'ar') || 'en'

export const i18n = createI18n({
  legacy: false,
  locale: saved,
  fallbackLocale: 'en',
  messages: { en, ar },
})

export type AppLocale = 'en' | 'ar'
