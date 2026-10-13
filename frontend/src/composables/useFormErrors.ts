import { computed, ref } from 'vue'
import type { AxiosError } from 'axios'

type ValidationErrors = Record<string, string[]>

/**
 * Extracts Laravel's 422 validation errors so they can be shown next to
 * the related fields, plus a fallback message for other failures.
 */
export function useFormErrors() {
  const errors = ref<ValidationErrors>({})
  const message = ref('')

  const hasErrors = computed(() => Object.keys(errors.value).length > 0)

  function clear() {
    errors.value = {}
    message.value = ''
  }

  function setFromError(e: unknown, fallback = 'Something went wrong. Please try again.') {
    const axiosError = e as AxiosError<{ message?: string; errors?: ValidationErrors }> | undefined

    if (axiosError?.response?.status === 422 && axiosError.response.data?.errors) {
      errors.value = axiosError.response.data.errors
      message.value = axiosError.response.data.message || 'Please fix the highlighted fields.'
      return
    }

    errors.value = {}
    message.value =
      axiosError?.response?.data?.message || axiosError?.message || fallback
  }

  /** First error message for a field, e.g. `first('title.en')`. */
  function first(field: string): string | undefined {
    return errors.value[field]?.[0]
  }

  return { errors, message, hasErrors, clear, setFromError, first }
}
