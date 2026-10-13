import type { LocalizedString } from './api'

export interface Faq {
  id: number
  question: LocalizedString
  answer: LocalizedString
  is_published?: boolean
  sort_order?: number
}

export interface Testimonial {
  id: number
  name: LocalizedString
  title: LocalizedString
  body: LocalizedString
  location: LocalizedString
  rating: number
  avatar_url?: string | null
  is_published?: boolean
  sort_order?: number
}

export interface Client {
  id: number
  name: LocalizedString
  logo_url?: string | null
  website_url?: string | null
  is_published?: boolean
  sort_order?: number
}

export interface TeamMember {
  id: number
  name: LocalizedString
  role: LocalizedString
  bio?: LocalizedString
  photo_url?: string | null
  is_published?: boolean
  sort_order?: number
}
