<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import {
  adminProperties,
  adminFeatures,
  type Feature,
  deletePropertyImage,
  setPrimaryPropertyImage,
} from '@/api/admin/resources'
import LocalizedFields from '@/components/admin/LocalizedFields.vue'
import FieldError from '@/components/ui/FieldError.vue'
import type { PropertyImage } from '@/types'
import { useLocalized } from '@/composables/useLocalized'
import { useFormErrors } from '@/composables/useFormErrors'

const route = useRoute()
const router = useRouter()
const { tField } = useLocalized()
const { message: errorMessage, setFromError, clear: clearErrors, first } = useFormErrors()
const id = route.params.id as string | undefined
const isEdit = id && id !== 'new'

const title = ref({ en: '', ar: '' })
const description = ref({ en: '', ar: '' })
const location = ref({ en: '', ar: '' })
const price = ref(0)
const bedrooms = ref(0)
const bathrooms = ref(0)
const type = ref('Villa')
const is_published = ref(true)
const sort_order = ref(0)
const featureIds = ref<number[]>([])
const allFeatures = ref<Feature[]>([])
const images = ref<PropertyImage[]>([])
const newImages = ref<FileList | null>(null)
const saving = ref(false)
const error = ref('')

function toLoc(v: unknown) {
  if (!v) return { en: '', ar: '' }
  if (typeof v === 'string') return { en: v, ar: v }
  const o = v as { en?: string; ar?: string }
  return { en: o.en || '', ar: o.ar || '' }
}

onMounted(async () => {
  try {
    allFeatures.value = (await adminFeatures.list({ perPage: 100 })).data
  } catch {
    /* optional */
  }
  if (!isEdit) return
  try {
    const item = await adminProperties.get(id!)
    title.value = toLoc(item.title)
    description.value = toLoc(item.description)
    location.value = toLoc(item.location)
    price.value = Number(item.price) || 0
    bedrooms.value = item.bedrooms
    bathrooms.value = item.bathrooms
    type.value = item.type || 'Villa'
    is_published.value = Boolean(item.is_published)
    sort_order.value = item.sort_order ?? 0
    featureIds.value = item.features?.map((f) => f.id) || []
    images.value = item.images || []
  } catch {
    error.value = 'Failed to load property'
  }
})

function toggleFeature(fid: number) {
  if (featureIds.value.includes(fid)) {
    featureIds.value = featureIds.value.filter((x) => x !== fid)
  } else {
    featureIds.value = [...featureIds.value, fid]
  }
}

async function save() {
  saving.value = true
  clearErrors()
  try {
    const fd = new FormData()
    fd.append('title[en]', title.value.en)
    fd.append('title[ar]', title.value.ar)
    fd.append('description[en]', description.value.en)
    fd.append('description[ar]', description.value.ar)
    fd.append('location[en]', location.value.en)
    fd.append('location[ar]', location.value.ar)
    fd.append('price', String(price.value))
    fd.append('bedrooms', String(bedrooms.value))
    fd.append('bathrooms', String(bathrooms.value))
    fd.append('type', type.value)
    fd.append('is_published', is_published.value ? '1' : '0')
    fd.append('sort_order', String(sort_order.value))
    featureIds.value.forEach((fid) => fd.append('feature_ids[]', String(fid)))
    if (newImages.value) {
      Array.from(newImages.value).forEach((file) => fd.append('images[]', file))
    }

    if (isEdit) await adminProperties.update(id!, fd)
    else await adminProperties.create(fd)
    await router.push('/admin/properties')
  } catch (e: unknown) {
    setFromError(e, 'Save failed')
  } finally {
    saving.value = false
  }
}

async function removeImage(imageId: number) {
  if (!confirm('Delete image?')) return
  await deletePropertyImage(imageId)
  images.value = images.value.filter((i) => i.id !== imageId)
}

async function makePrimary(imageId: number) {
  await setPrimaryPropertyImage(imageId)
  images.value = images.value.map((i) => ({
    ...i,
    is_primary: i.id === imageId,
  }))
}
</script>

<template>
  <div>
    <h1 class="mb-6 text-2xl font-semibold">{{ isEdit ? 'Edit property' : 'New property' }}</h1>
    <p v-if="error" class="mb-4 text-red-400">{{ error }}</p>
    <p v-if="errorMessage" class="mb-4 rounded-lg border border-red-900/50 bg-red-950/40 px-3 py-2 text-sm text-red-300">
      {{ errorMessage }}
    </p>
    <form class="card max-w-4xl space-y-5 p-6" @submit.prevent="save">
      <LocalizedFields v-model="title" label="Title" required :error-en="first('title.en')" :error-ar="first('title.ar')" />
      <LocalizedFields v-model="description" label="Description" multiline :error-en="first('description.en')" :error-ar="first('description.ar')" />
      <LocalizedFields v-model="location" label="Location" :error-en="first('location.en')" :error-ar="first('location.ar')" />

      <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-4">
        <label class="flex flex-col gap-1.5 text-sm">
          Price
          <input v-model.number="price" type="number" min="0" step="0.01" required class="rounded-lg border border-border-light bg-bg px-3 py-2 outline-none focus:border-purple" />
          <FieldError :message="first('price')" />
        </label>
        <label class="flex flex-col gap-1.5 text-sm">
          Bedrooms
          <input v-model.number="bedrooms" type="number" min="0" class="rounded-lg border border-border-light bg-bg px-3 py-2 outline-none focus:border-purple" />
          <FieldError :message="first('bedrooms')" />
        </label>
        <label class="flex flex-col gap-1.5 text-sm">
          Bathrooms
          <input v-model.number="bathrooms" type="number" min="0" class="rounded-lg border border-border-light bg-bg px-3 py-2 outline-none focus:border-purple" />
          <FieldError :message="first('bathrooms')" />
        </label>
        <label class="flex flex-col gap-1.5 text-sm">
          Type
          <select v-model="type" class="rounded-lg border border-border-light bg-bg px-3 py-2 outline-none focus:border-purple">
            <option>Villa</option>
            <option>Apartment</option>
            <option>Cottage</option>
            <option>House</option>
          </select>
          <FieldError :message="first('type')" />
        </label>
      </div>

      <div>
        <p class="mb-2 text-sm text-muted">Features</p>
        <div class="flex flex-wrap gap-2">
          <button
            v-for="f in allFeatures"
            :key="f.id"
            type="button"
            class="rounded-full border px-3 py-1.5 text-sm"
            :class="featureIds.includes(f.id) ? 'border-purple bg-purple-soft text-white' : 'border-border text-muted'"
            @click="toggleFeature(f.id)"
          >
            {{ tField(f.name) }}
          </button>
        </div>
        <FieldError :message="first('feature_ids.0') ?? first('feature_ids')" />
      </div>

      <div v-if="isEdit && images.length" class="space-y-2">
        <p class="text-sm text-muted">Existing images</p>
        <div class="flex flex-wrap gap-3">
          <div v-for="img in images" :key="img.id" class="relative w-28">
            <img :src="img.url" alt="" class="aspect-square w-full rounded-lg object-cover" />
            <span v-if="img.is_primary" class="absolute left-1 top-1 rounded bg-purple px-1.5 text-[10px]">Primary</span>
            <div class="mt-1 flex flex-col gap-0.5 text-[11px]">
              <button type="button" class="text-purple" @click="makePrimary(img.id)">Set primary</button>
              <button type="button" class="text-red-400" @click="removeImage(img.id)">Delete</button>
            </div>
          </div>
        </div>
      </div>

      <label class="flex flex-col gap-1.5 text-sm">
        {{ isEdit ? 'Add images' : 'Images' }}
        <input type="file" accept="image/*" multiple @change="newImages = ($event.target as HTMLInputElement).files" />
        <FieldError :message="first('images.0') ?? first('images')" />
      </label>

      <label class="flex items-center gap-2 text-sm">
        <input v-model="is_published" type="checkbox" class="accent-purple" /> Published
      </label>

      <label class="flex max-w-xs flex-col gap-1.5 text-sm">
        Sort order
        <input v-model.number="sort_order" type="number" min="0" class="rounded-lg border border-border-light bg-bg px-3 py-2 outline-none focus:border-purple" />
        <FieldError :message="first('sort_order')" />
      </label>

      <div class="flex gap-3">
        <button type="submit" class="btn-primary" :disabled="saving">{{ saving ? 'Saving…' : 'Save' }}</button>
        <router-link to="/admin/properties" class="btn-ghost">Cancel</router-link>
      </div>
    </form>
  </div>
</template>
