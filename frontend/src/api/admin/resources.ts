import { createAdminResource } from './crud'
import type { Client, Faq, Property, TeamMember, Testimonial } from '@/types'
import type { LocalizedString } from '@/types'
import apiClient from '@/api/client'

export interface Feature {
  id: number
  name: LocalizedString
}

export const adminProperties = createAdminResource<Property>('/admin/properties')
export const adminFeatures = createAdminResource<Feature>('/admin/features')
export const adminFaqs = createAdminResource<Faq>('/admin/faqs')
export const adminTestimonials = createAdminResource<Testimonial>('/admin/testimonials')
export const adminClients = createAdminResource<Client>('/admin/clients')
export const adminTeam = createAdminResource<TeamMember>('/admin/team-members')

export async function deletePropertyImage(id: number) {
  await apiClient.delete(`/admin/property-images/${id}`)
}

export async function setPrimaryPropertyImage(id: number) {
  const { data } = await apiClient.post(`/admin/property-images/${id}/primary`)
  return data
}
