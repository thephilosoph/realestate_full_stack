import { useI18n } from 'vue-i18n'
import { useLocaleStore } from '@/stores/locale'
import type { LocalizedString } from '@/types'
import type { AppLocale } from '@/i18n'

/**
 * tField — Spatie JSON from API ({ en, ar })
 * t — vue-i18n UI strings
 */
export function useLocalized() {
  const { t, locale } = useI18n()
  const store = useLocaleStore()

  function tField(field: LocalizedString | null | undefined, fallback = ''): string {
    if (field == null) return fallback
    if (typeof field === 'string') return field
    const code = (locale.value as AppLocale) || store.current
    return field[code] || field.en || field.ar || fallback
  }

  function setLocale(code: AppLocale) {
    store.setLocale(code)
    locale.value = code
  }

  return {
    t,
    tField,
    locale,
    setLocale,
  }
}
