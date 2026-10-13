<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { adminClients } from '@/api/admin/resources'
import LocalizedFields from '@/components/admin/LocalizedFields.vue'

const route = useRoute()
const router = useRouter()
const id = route.params.id as string | undefined
const isEdit = id && id !== 'new'

const name = ref({ en: '', ar: '' })
const website_url = ref('')
const is_published = ref(true)
const sort_order = ref(0)
const logo = ref<File | null>(null)
const saving = ref(false)
const error = ref('')

onMounted(async () => {
  if (!isEdit) return
  const item = await adminClients.get(id!)
  const n = item.name
  name.value = typeof n === 'string' ? { en: n, ar: n } : { en: n?.en || '', ar: n?.ar || '' }
  website_url.value = item.website_url || ''
  is_published.value = Boolean(item.is_published)
  sort_order.value = item.sort_order ?? 0
})

async function save() {
  saving.value = true
  error.value = ''
  try {
    const fd = new FormData()
    fd.append('name[en]', name.value.en)
    fd.append('name[ar]', name.value.ar)
    if (website_url.value) fd.append('website_url', website_url.value)
    fd.append('is_published', is_published.value ? '1' : '0')
    fd.append('sort_order', String(sort_order.value))
    if (logo.value) fd.append('logo', logo.value)
    if (isEdit) await adminClients.update(id!, fd)
    else {
      if (!logo.value) {
        error.value = 'Logo is required on create'
        saving.value = false
        return
      }
      await adminClients.create(fd)
    }
    await router.push('/admin/clients')
  } catch (e: unknown) {
    error.value = e instanceof Error ? e.message : 'Save failed'
  } finally {
    saving.value = false
  }
}
</script>

<template>
  <div>
    <h1 class="mb-6 text-2xl font-semibold">{{ isEdit ? 'Edit client' : 'New client' }}</h1>
    <p v-if="error" class="mb-4 text-red-400">{{ error }}</p>
    <form class="card max-w-3xl space-y-5 p-6" @submit.prevent="save">
      <LocalizedFields v-model="name" label="Name" />
      <label class="flex flex-col gap-1.5 text-sm">
        Website URL
        <input v-model="website_url" type="url" class="rounded-lg border border-border-light bg-bg px-3 py-2 outline-none focus:border-purple" />
      </label>
      <label class="flex flex-col gap-1.5 text-sm">
        Logo {{ isEdit ? '(optional)' : '' }}
        <input type="file" accept="image/*" @change="logo = ($event.target as HTMLInputElement).files?.[0] || null" />
      </label>
      <label class="flex items-center gap-2 text-sm">
        <input v-model="is_published" type="checkbox" class="accent-purple" /> Published
      </label>
      <div class="flex gap-3">
        <button type="submit" class="btn-primary" :disabled="saving">{{ saving ? 'Saving…' : 'Save' }}</button>
        <router-link to="/admin/clients" class="btn-ghost">Cancel</router-link>
      </div>
    </form>
  </div>
</template>
