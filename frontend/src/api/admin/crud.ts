import apiClient from '@/api/client'
import type { PaginatedResponse } from '@/types'

export type AdminListParams = {
  search?: string
  perPage?: number
  page?: number
  sort?: string
  direction?: 'asc' | 'desc'
  is_published?: boolean | number | string
  [key: string]: unknown
}

export function createAdminResource<T>(basePath: string) {
  return {
    async list(params: AdminListParams = {}) {
      const { data } = await apiClient.get<PaginatedResponse<T>>(basePath, { params })
      return data
    },
    async get(id: number | string) {
      const { data } = await apiClient.get<{ data: T } | T>(`${basePath}/${id}`)
      if (data && typeof data === 'object' && 'data' in data) return (data as { data: T }).data
      return data as T
    },
    async create(payload: FormData | Record<string, unknown>) {
      const { data } = await apiClient.post<{ data: T } | T>(basePath, payload)
      if (data && typeof data === 'object' && 'data' in data) return (data as { data: T }).data
      return data as T
    },
    async update(id: number | string, payload: FormData | Record<string, unknown>) {
      // FormData: use POST + _method=PUT for file uploads
      if (payload instanceof FormData) {
        payload.append('_method', 'PUT')
        const { data } = await apiClient.post<{ data: T } | T>(`${basePath}/${id}`, payload)
        if (data && typeof data === 'object' && 'data' in data) return (data as { data: T }).data
        return data as T
      }
      const { data } = await apiClient.put<{ data: T } | T>(`${basePath}/${id}`, payload)
      if (data && typeof data === 'object' && 'data' in data) return (data as { data: T }).data
      return data as T
    },
    async remove(id: number | string) {
      await apiClient.delete(`${basePath}/${id}`)
    },
  }
}
