import axios, { AxiosError } from 'axios'

const baseURL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api/v1'

export const apiClient = axios.create({
  baseURL,
  headers: {
    Accept: 'application/json',
  },
})

apiClient.interceptors.request.use((config) => {
  const token = localStorage.getItem('admin_token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

const TOKEN_KEY = 'admin_token'

apiClient.interceptors.response.use(
  (response) => response,
  (error: AxiosError) => {
    const status = error.response?.status
    const url = error.config?.url ?? ''

    // Expired/invalid admin session: clear the stale token and send the
    // user back to the login page. Only while inside the admin area, and
    // never for the login request itself (that failure is handled by the form).
    if (
      status === 401 &&
      url.includes('/admin/') &&
      !url.endsWith('/admin/login') &&
      window.location.pathname.startsWith('/admin') &&
      !window.location.pathname.startsWith('/admin/login')
    ) {
      localStorage.removeItem(TOKEN_KEY)
      window.location.assign('/admin/login?expired=1')
    }

    return Promise.reject(error)
  },
)

export default apiClient
