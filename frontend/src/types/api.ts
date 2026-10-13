export type LocaleCode = 'en' | 'ar'

export type LocalizedString = {
  en: string
  ar: string
} | string

export interface PaginationMeta {
  total: number
  per_page: number
  current_page: number
  last_page: number
}

export interface PaginatedResponse<T> {
  data: T[]
  meta?: PaginationMeta
}

export interface ListParams {
  search?: string
  perPage?: number
  page?: number
  sort?: string
  direction?: 'asc' | 'desc'
  type?: string
  bedrooms?: number
  bathrooms?: number
  relations?: string[]
  [key: string]: string | number | string[] | undefined
}
