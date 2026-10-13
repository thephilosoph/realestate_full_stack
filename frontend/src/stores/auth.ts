import { defineStore } from 'pinia'
import { computed, ref } from 'vue'
import { login as apiLogin, logout as apiLogout, me as apiMe, type AuthUser } from '@/api/admin/auth'

const TOKEN_KEY = 'admin_token'

export const useAuthStore = defineStore('auth', () => {
  const token = ref<string | null>(localStorage.getItem(TOKEN_KEY))
  const user = ref<AuthUser | null>(null)
  const bootstrapped = ref(false)

  const isAuthenticated = computed(() => Boolean(token.value))

  function setToken(value: string | null) {
    token.value = value
    if (value) localStorage.setItem(TOKEN_KEY, value)
    else localStorage.removeItem(TOKEN_KEY)
  }

  async function login(email: string, password: string) {
    const data = await apiLogin(email, password)
    setToken(data.token)
    user.value = data.user
    return data
  }

  async function fetchMe() {
    if (!token.value) {
      user.value = null
      return null
    }
    try {
      const u = await apiMe()
      user.value = u
      return u
    } catch {
      setToken(null)
      user.value = null
      return null
    }
  }

  async function logout() {
    try {
      if (token.value) await apiLogout()
    } catch {
      // ignore network errors on logout
    } finally {
      setToken(null)
      user.value = null
    }
  }

  async function bootstrap() {
    if (bootstrapped.value) return
    if (token.value) await fetchMe()
    bootstrapped.value = true
  }

  return {
    token,
    user,
    bootstrapped,
    isAuthenticated,
    login,
    logout,
    fetchMe,
    bootstrap,
    setToken,
  }
})
