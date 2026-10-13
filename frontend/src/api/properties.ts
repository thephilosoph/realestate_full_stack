import apiClient from './client'
import type { PaginatedResponse, Property, PropertyFilters } from '@/types'

function toQuery(params: PropertyFilters = {}) {
  const q: Record<string, string | number> = {}
  if (params.search) q.search = params.search
  if (params.type) q.type = params.type
  if (params.bedrooms != null) q.bedrooms = params.bedrooms
  if (params.bathrooms != null) q.bathrooms = params.bathrooms
  if (params.perPage) q.perPage = params.perPage
  if (params.page) q.page = params.page
  if (params.sort) q.sort = params.sort
  if (params.direction) q.direction = params.direction
  q['relations[]'] = 'images' as unknown as number
  return {
    ...q,
    'relations[]': ['images', 'features'],
  }
}

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
