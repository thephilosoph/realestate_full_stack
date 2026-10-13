<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { adminTestimonials } from '@/api/admin/resources'
import LocalizedFields from '@/components/admin/LocalizedFields.vue'
import FieldError from '@/components/ui/FieldError.vue'
import { useFormErrors } from '@/composables/useFormErrors'

const route = useRoute()
const router = useRouter()
const id = route.params.id as string | undefined
const isEdit = id && id !== 'new'
const { message: errorMessage, setFromError, clear: clearErrors, first } = useFormErrors()

const name = ref({ en: '', ar: '' })
const title = ref({ en: '', ar: '' })
const body = ref({ en: '', ar: '' })
const location = ref({ en: '', ar: '' })
const rating = ref(5)
const is_published = ref(true)
const sort_order = ref(0)
const avatar = ref<File | null>(null)
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
  const item = await adminTestimonials.get(id!)
  name.value = toLoc(item.name)
  title.value = toLoc(item.title)
  body.value = toLoc(item.body)
  location.value = toLoc(item.location)
  rating.value = item.rating || 5
  is_published.value = Boolean(item.is_published)
  sort_order.value = item.sort_order ?? 0
})

async function save() {
  saving.value = true
  clearErrors()
  try {
    const fd = new FormData()
    fd.append('name[en]', name.value.en)
    fd.append('name[ar]', name.value.ar)
    fd.append('title[en]', title.value.en)
    fd.append('title[ar]', title.value.ar)
    fd.append('body[en]', body.value.en)
    fd.append('body[ar]', body.value.ar)
    fd.append('location[en]', location.value.en)
    fd.append('location[ar]', location.value.ar)
    fd.append('rating', String(rating.value))
    fd.append('is_published', is_published.value ? '1' : '0')
    fd.append('sort_order', String(sort_order.value))
    if (avatar.value) fd.append('avatar', avatar.value)

    if (isEdit) await adminTestimonials.update(id!, fd)
    else await adminTestimonials.create(fd)
    await router.push('/admin/testimonials')
  } catch (e: unknown) {
    setFromError(e, 'Save failed')
  } finally {
    saving.value = false
  }
}
</script>

<template>
  <div>
    <h1 class="mb-6 text-2xl font-semibold">{{ isEdit ? 'Edit testimonial' : 'New testimonial' }}</h1>
    <p v-if="error" class="mb-4 text-red-400">{{ error }}</p>
    <p v-if="errorMessage" class="mb-4 rounded-lg border border-red-900/50 bg-red-950/40 px-3 py-2 text-sm text-red-300">
      {{ errorMessage }}
    </p>
    <form class="card max-w-3xl space-y-5 p-6" @submit.prevent="save">
      <LocalizedFields v-model="name" label="Name" required :error-en="first('name.en')" :error-ar="first('name.ar')" />
      <LocalizedFields v-model="title" label="Title" required :error-en="first('title.en')" :error-ar="first('title.ar')" />
      <LocalizedFields v-model="body" label="Body" multiline required :error-en="first('body.en')" :error-ar="first('body.ar')" />
      <LocalizedFields v-model="location" label="Location" :error-en="first('location.en')" :error-ar="first('location.ar')" />
      <label class="flex max-w-xs flex-col gap-1.5 text-sm">
        Rating (1–5)
        <input v-model.number="rating" type="number" min="1" max="5" class="rounded-lg border border-border-light bg-bg px-3 py-2" />
        <FieldError :message="first('rating')" />
      </label>
      <label class="flex flex-col gap-1.5 text-sm">
        Avatar
        <input type="file" accept="image/*" class="text-sm" @change="avatar = ($event.target as HTMLInputElement).files?.[0] || null" />
        <FieldError :message="first('avatar')" />
      </label>
      <label class="flex items-center gap-2 text-sm">
        <input v-model="is_published" type="checkbox" class="accent-purple" /> Published
      </label>
      <div class="flex gap-3">
        <button type="submit" class="btn-primary" :disabled="saving">{{ saving ? 'Saving…' : 'Save' }}</button>
        <router-link to="/admin/testimonials" class="btn-ghost">Cancel</router-link>
      </div>
    </form>
  </div>
</template>
