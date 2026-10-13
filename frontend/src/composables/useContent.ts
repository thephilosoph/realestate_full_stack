import { useQuery } from '@tanstack/vue-query'
import {
  fetchClients,
  fetchFaqs,
  fetchTeamMembers,
  fetchTestimonials,
} from '@/api/content'

export function useFaqs(perPage = 6) {
  return useQuery({
    queryKey: ['faqs', perPage],
    queryFn: () => fetchFaqs(perPage),
  })
}

export function useTestimonials(perPage = 6) {
  return useQuery({
    queryKey: ['testimonials', perPage],
    queryFn: () => fetchTestimonials(perPage),
  })
}

export function useClients(perPage = 10) {
  return useQuery({
    queryKey: ['clients', perPage],
    queryFn: () => fetchClients(perPage),
  })
}

export function useTeamMembers(perPage = 12) {
  return useQuery({
    queryKey: ['team-members', perPage],
    queryFn: () => fetchTeamMembers(perPage),
  })
}
