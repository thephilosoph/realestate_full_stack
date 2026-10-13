import apiClient from './client'
import type { Client, Faq, PaginatedResponse, TeamMember, Testimonial } from '@/types'

export async function fetchFaqs(perPage = 15) {
  const { data } = await apiClient.get<PaginatedResponse<Faq>>('/faqs', {
    params: { perPage, sort: 'sort_order', direction: 'asc' },
  })
  return data
}

export async function fetchTestimonials(perPage = 15) {
  const { data } = await apiClient.get<PaginatedResponse<Testimonial>>('/testimonials', {
    params: { perPage, sort: 'sort_order', direction: 'asc' },
  })
  return data
}

export async function fetchClients(perPage = 15) {
  const { data } = await apiClient.get<PaginatedResponse<Client>>('/clients', {
    params: { perPage, sort: 'sort_order', direction: 'asc' },
  })
  return data
}

export async function fetchTeamMembers(perPage = 15) {
  const { data } = await apiClient.get<PaginatedResponse<TeamMember>>('/team-members', {
    params: { perPage, sort: 'sort_order', direction: 'asc' },
  })
  return data
}
