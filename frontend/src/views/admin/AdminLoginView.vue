<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()
const router = useRouter()

const email = ref('admin@estatein.test')
const password = ref('password')
const error = ref('')
const loading = ref(false)

async function onSubmit() {
  error.value = ''
  loading.value = true
  try {
    await auth.login(email.value, password.value)
    await router.push('/admin')
  } catch (e: unknown) {
    const err = e as { response?: { data?: { message?: string; errors?: Record<string, string[]> } } }
    error.value =
      err.response?.data?.message ||
      err.response?.data?.errors?.email?.[0] ||
      'Login failed. Check credentials and API URL.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="flex min-h-screen items-center justify-center bg-bg px-4">
    <form
      class="w-full max-w-md rounded-2xl border border-border bg-bg-card p-8 shadow-xl"
      @submit.prevent="onSubmit"
    >
      <h1 class="mb-1 text-2xl font-semibold">Admin login</h1>
      <p class="mb-6 text-sm text-muted">Sign in to manage Estatein content</p>

      <div v-if="error" class="mb-4 rounded-lg border border-red-900/50 bg-red-950/40 px-3 py-2 text-sm text-red-300">
        {{ error }}
      </div>

      <label class="mb-4 flex flex-col gap-1.5 text-sm">
        Email
        <input
          v-model="email"
          type="email"
          required
          class="rounded-lg border border-border-light bg-bg px-3 py-2.5 outline-none focus:border-purple"
        />
      </label>
      <label class="mb-6 flex flex-col gap-1.5 text-sm">
        Password
        <input
          v-model="password"
          type="password"
          required
          class="rounded-lg border border-border-light bg-bg px-3 py-2.5 outline-none focus:border-purple"
        />
      </label>
      <button type="submit" class="btn-primary w-full" :disabled="loading">
        {{ loading ? 'Signing in…' : 'Sign in' }}
      </button>
    </form>
  </div>
</template>
