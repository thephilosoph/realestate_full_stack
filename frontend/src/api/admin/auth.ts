import apiClient from '@/api/client'

export interface AuthUser {
  id: number
  name: string
  email: string
}

export interface LoginResponse {
  token: string
  token_type: string
  user: AuthUser
}

export async function login(email: string, password: string) {
  const { data } = await apiClient.post<LoginResponse>('/admin/login', { email, password })
  return data
}

export async function me() {
  const { data } = await apiClient.get<{ user: AuthUser } | AuthUser>('/admin/me')
  if (data && typeof data === 'object' && 'user' in data) return (data as { user: AuthUser }).user
  return data as AuthUser
}

export async function logout() {
  await apiClient.post('/admin/logout')
}
