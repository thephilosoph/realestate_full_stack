import { computed, type MaybeRefOrGetter, toValue } from 'vue'
import { useQuery } from '@tanstack/vue-query'
import { fetchProperties, fetchProperty } from '@/api/properties'
import type { PropertyFilters } from '@/types'

export function useProperties(filters: MaybeRefOrGetter<PropertyFilters> = {}) {
  return useQuery({
    queryKey: computed(() => ['properties', toValue(filters)]),
    queryFn: () => fetchProperties(toValue(filters)),
  })
}

export function useProperty(id: MaybeRefOrGetter<number | string | undefined>) {
  return useQuery({
    queryKey: computed(() => ['property', toValue(id)]),
    queryFn: () => fetchProperty(toValue(id)!),
    enabled: computed(() => {
      const v = toValue(id)
      return v !== undefined && v !== null && v !== ''
    }),
  })
}
