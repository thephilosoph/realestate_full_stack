<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { adminTestimonials } from '@/api/admin/resources'
import type { Testimonial } from '@/types'
import { useLocalized } from '@/composables/useLocalized'

const { tField } = useLocalized()
const items = ref<Testimonial[]>([])
const loading = ref(true)
const error = ref('')

async function load() {
  loading.value = true
  try {
    items.value = (await adminTestimonials.list({ perPage: 50 })).data
  } catch (e: unknown) {
    error.value = e instanceof Error ? e.message : 'Failed'
  } finally {
    loading.value = false
  }
}
async function remove(id: number) {
  if (!confirm('Delete?')) return
  await adminTestimonials.remove(id)
  await load()
}
onMounted(load)
</script>

<template>
  <div>
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-semibold">Testimonials</h1>
      <router-link to="/admin/testimonials/new" class="btn-primary text-sm">Add</router-link>
    </div>
    <p v-if="loading" class="text-muted">Loading…</p>
    <p v-else-if="error" class="text-red-400">{{ error }}</p>
    <div v-else class="overflow-x-auto rounded-xl border border-border">
      <table class="w-full text-sm">
        <thead class="border-b border-border bg-bg-elevated text-muted">
          <tr>
            <th class="px-4 py-3">Name</th>
            <th class="px-4 py-3">Title</th>
            <th class="px-4 py-3">Published</th>
            <th class="px-4 py-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in items" :key="item.id" class="border-b border-border/60">
            <td class="px-4 py-3">{{ tField(item.name) }}</td>
            <td class="px-4 py-3">{{ tField(item.title) }}</td>
            <td class="px-4 py-3">{{ item.is_published ? 'Yes' : 'No' }}</td>
            <td class="px-4 py-3">
              <router-link :to="`/admin/testimonials/${item.id}`" class="text-purple hover:underline">Edit</router-link>
              <button type="button" class="ms-3 text-red-400" @click="remove(item.id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
