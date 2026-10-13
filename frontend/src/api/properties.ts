import apiClient from './client'
import type { PaginatedResponse, Property, PropertyFilters } from '@/types'

export async function fetchProperties(params: PropertyFilters = {}) {
  const { data } = await apiClient.get<PaginatedResponse<Property>>('/properties', {
    params: {
      search: params.search,
      type: params.type,
      bedrooms: params.bedrooms,
      bathrooms: params.bathrooms,
      perPage: params.perPage ?? 15,
      page: params.page,
      sort: params.sort ?? 'sort_order',
      direction: params.direction ?? 'asc',
      'relations[]': ['images', 'features'],
    },
  })
  return data
}

export async function fetchProperty(id: number | string) {
  const { data } = await apiClient.get<{ data: Property } | Property>(`/properties/${id}`)
  // Support both wrapped and unwrapped resource
  if (data && typeof data === 'object' && 'data' in data && (data as { data: Property }).data) {
    return (data as { data: Property }).data
  }
  return data as Property
}
