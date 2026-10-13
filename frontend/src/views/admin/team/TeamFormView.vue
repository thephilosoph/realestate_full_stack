<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { adminTeam } from '@/api/admin/resources'
import LocalizedFields from '@/components/admin/LocalizedFields.vue'
import FieldError from '@/components/ui/FieldError.vue'
import { useFormErrors } from '@/composables/useFormErrors'

const route = useRoute()
const router = useRouter()
const id = route.params.id as string | undefined
const isEdit = id && id !== 'new'
const { message: errorMessage, setFromError, clear: clearErrors, first } = useFormErrors()

const name = ref({ en: '', ar: '' })
const role = ref({ en: '', ar: '' })
const bio = ref({ en: '', ar: '' })
const is_published = ref(true)
const sort_order = ref(0)
const photo = ref<File | null>(null)
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
  const item = await adminTeam.get(id!)
  name.value = toLoc(item.name)
  role.value = toLoc(item.role)
  bio.value = toLoc(item.bio)
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
    fd.append('role[en]', role.value.en)
    fd.append('role[ar]', role.value.ar)
    fd.append('bio[en]', bio.value.en)
    fd.append('bio[ar]', bio.value.ar)
    fd.append('is_published', is_published.value ? '1' : '0')
    fd.append('sort_order', String(sort_order.value))
    if (photo.value) fd.append('photo', photo.value)
    if (isEdit) await adminTeam.update(id!, fd)
    else await adminTeam.create(fd)
    await router.push('/admin/team')
  } catch (e: unknown) {
    setFromError(e, 'Save failed')
  } finally {
    saving.value = false
  }
}
</script>

<template>
  <div>
    <h1 class="mb-6 text-2xl font-semibold">{{ isEdit ? 'Edit member' : 'New member' }}</h1>
    <p v-if="error" class="mb-4 text-red-400">{{ error }}</p>
    <p v-if="errorMessage" class="mb-4 rounded-lg border border-red-900/50 bg-red-950/40 px-3 py-2 text-sm text-red-300">
      {{ errorMessage }}
    </p>
    <form class="card max-w-3xl space-y-5 p-6" @submit.prevent="save">
      <LocalizedFields v-model="name" label="Name" required :error-en="first('name.en')" :error-ar="first('name.ar')" />
      <LocalizedFields v-model="role" label="Role" required :error-en="first('role.en')" :error-ar="first('role.ar')" />
      <LocalizedFields v-model="bio" label="Bio" multiline :error-en="first('bio.en')" :error-ar="first('bio.ar')" />
      <label class="flex flex-col gap-1.5 text-sm">
        Photo
        <input type="file" accept="image/*" @change="photo = ($event.target as HTMLInputElement).files?.[0] || null" />
        <FieldError :message="first('photo')" />
      </label>
      <label class="flex items-center gap-2 text-sm">
        <input v-model="is_published" type="checkbox" class="accent-purple" /> Published
      </label>
      <div class="flex gap-3">
        <button type="submit" class="btn-primary" :disabled="saving">{{ saving ? 'Saving…' : 'Save' }}</button>
        <router-link to="/admin/team" class="btn-ghost">Cancel</router-link>
      </div>
    </form>
  </div>
</template>
