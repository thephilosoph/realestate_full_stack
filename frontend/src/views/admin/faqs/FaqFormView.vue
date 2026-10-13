<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { adminFaqs } from '@/api/admin/resources'
import LocalizedFields from '@/components/admin/LocalizedFields.vue'

const route = useRoute()
const router = useRouter()
const id = route.params.id as string | undefined
const isEdit = id && id !== 'new'

const question = ref({ en: '', ar: '' })
const answer = ref({ en: '', ar: '' })
const is_published = ref(true)
const sort_order = ref(0)
const saving = ref(false)
const error = ref('')

function toLoc(v: unknown) {
  if (!v) return { en: '', ar: '' }
  if (typeof v === 'string') return { en: v, ar: v }
  const o = v as { en?: string; ar?: string }
  return { en: o.en || '', ar: o.ar || '' }
}

onMounted(async () => {
  if (!isEdit) return
  try {
    const item = await adminFaqs.get(id!)
    question.value = toLoc(item.question)
    answer.value = toLoc(item.answer)
    is_published.value = Boolean(item.is_published)
    sort_order.value = item.sort_order ?? 0
  } catch {
    error.value = 'Failed to load'
  }
})

async function save() {
  saving.value = true
  error.value = ''
  const payload = {
    question: question.value,
    answer: answer.value,
    is_published: is_published.value,
    sort_order: sort_order.value,
  }
  try {
    if (isEdit) await adminFaqs.update(id!, payload)
    else await adminFaqs.create(payload)
    await router.push('/admin/faqs')
  } catch (e: unknown) {
    error.value = e instanceof Error ? e.message : 'Save failed'
  } finally {
    saving.value = false
  }
}
</script>

<template>
  <div>
    <h1 class="mb-6 text-2xl font-semibold">{{ isEdit ? 'Edit FAQ' : 'New FAQ' }}</h1>
    <p v-if="error" class="mb-4 text-red-400">{{ error }}</p>
    <form class="card max-w-3xl space-y-5 p-6" @submit.prevent="save">
      <LocalizedFields v-model="question" label="Question" required />
      <LocalizedFields v-model="answer" label="Answer" multiline required />
      <label class="flex items-center gap-2 text-sm">
        <input v-model="is_published" type="checkbox" class="accent-purple" />
        Published
      </label>
      <label class="flex max-w-xs flex-col gap-1.5 text-sm">
        Sort order
        <input v-model.number="sort_order" type="number" min="0" class="rounded-lg border border-border-light bg-bg px-3 py-2 outline-none focus:border-purple" />
      </label>
      <div class="flex gap-3">
        <button type="submit" class="btn-primary" :disabled="saving">{{ saving ? 'Saving…' : 'Save' }}</button>
        <router-link to="/admin/faqs" class="btn-ghost">Cancel</router-link>
      </div>
    </form>
  </div>
</template>
