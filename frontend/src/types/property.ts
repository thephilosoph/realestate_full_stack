import type { LocalizedString } from './api'

export interface PropertyImage {
  id: number
  url: string
  path?: string
  is_primary: boolean
  sort_order: number
}

export interface PropertyFeature {
  id: number
  name: LocalizedString
  sort_order?: number
}

export interface Property {
  id: number
  title: LocalizedString
  description: LocalizedString
  location: LocalizedString
  price: number | string
  bedrooms: number
  bathrooms: number
  type: string | null
  is_published?: boolean
  sort_order?: number
  primary_image?: { id: number; url: string } | null
  images?: PropertyImage[]
  features?: PropertyFeature[]
  meta?: {
    created_at?: string
    updated_at?: string
  }
}

export interface PropertyFilters {
  search?: string
  type?: string
  bedrooms?: number
  bathrooms?: number
  perPage?: number
  page?: number
  sort?: string
  direction?: 'asc' | 'desc'
}
