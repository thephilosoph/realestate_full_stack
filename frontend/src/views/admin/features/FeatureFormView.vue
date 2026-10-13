<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { adminFeatures } from '@/api/admin/resources'
import LocalizedFields from '@/components/admin/LocalizedFields.vue'

const route = useRoute()
const router = useRouter()
const id = route.params.id as string | undefined
const isEdit = id && id !== 'new'

const name = ref({ en: '', ar: '' })
const saving = ref(false)
const error = ref('')

onMounted(async () => {
  if (!isEdit) return
  try {
    const item = await adminFeatures.get(id!)
    const n = item.name
    name.value = typeof n === 'string' ? { en: n, ar: n } : { en: n.en || '', ar: n.ar || '' }
  } catch {
    error.value = 'Failed to load feature'
  }
})

async function save() {
  saving.value = true
  error.value = ''
  try {
    if (isEdit) await adminFeatures.update(id!, { name: name.value })
    else await adminFeatures.create({ name: name.value })
    await router.push('/admin/features')
  } catch (e: unknown) {
    error.value = e instanceof Error ? e.message : 'Save failed'
  } finally {
    saving.value = false
  }
}
</script>

<template>
  <div>
    <h1 class="mb-6 text-2xl font-semibold">{{ isEdit ? 'Edit feature' : 'New feature' }}</h1>
    <p v-if="error" class="mb-4 text-red-400">{{ error }}</p>
    <form class="card max-w-3xl space-y-5 p-6" @submit.prevent="save">
      <LocalizedFields v-model="name" label="Name" required />
      <div class="flex gap-3">
        <button type="submit" class="btn-primary" :disabled="saving">{{ saving ? 'Saving…' : 'Save' }}</button>
        <router-link to="/admin/features" class="btn-ghost">Cancel</router-link>
      </div>
    </form>
  </div>
</template>
