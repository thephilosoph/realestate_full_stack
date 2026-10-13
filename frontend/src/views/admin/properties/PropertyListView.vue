<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { adminProperties } from '@/api/admin/resources'
import type { Property } from '@/types'
import { useLocalized } from '@/composables/useLocalized'
import { formatPrice } from '@/utils/format'

const { tField } = useLocalized()
const items = ref<Property[]>([])
const loading = ref(true)
const error = ref('')
const search = ref('')

async function load() {
  loading.value = true
  error.value = ''
  try {
    const res = await adminProperties.list({
      search: search.value || undefined,
      perPage: 50,
      sort: 'created_at',
      direction: 'desc',
    })
    items.value = res.data
  } catch (e: unknown) {
    error.value = e instanceof Error ? e.message : 'Failed to load'
  } finally {
    loading.value = false
  }
}

async function remove(id: number) {
  if (!confirm('Delete this property and its images?')) return
  await adminProperties.remove(id)
  await load()
}

onMounted(load)
</script>

<template>
  <div>
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
      <h1 class="text-2xl font-semibold">Properties</h1>
      <router-link to="/admin/properties/new" class="btn-primary text-sm">Add property</router-link>
    </div>
    <div class="mb-4 flex gap-2">
      <input
        v-model="search"
        type="search"
        placeholder="Search…"
        class="rounded-lg border border-border-light bg-bg px-3 py-2 text-sm outline-none focus:border-purple"
        @keyup.enter="load"
      />
      <button type="button" class="btn-secondary py-2 text-sm" @click="load">Search</button>
    </div>
    <p v-if="loading" class="text-muted">Loading…</p>
    <p v-else-if="error" class="text-red-400">{{ error }}</p>
    <div v-else class="overflow-x-auto rounded-xl border border-border">
      <table class="w-full text-sm">
        <thead class="border-b border-border bg-bg-elevated text-muted">
          <tr>
            <th class="px-4 py-3 font-medium">Title</th>
            <th class="px-4 py-3 font-medium">Price</th>
            <th class="px-4 py-3 font-medium">Type</th>
            <th class="px-4 py-3 font-medium">Published</th>
            <th class="px-4 py-3 font-medium">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in items" :key="item.id" class="border-b border-border/60">
            <td class="px-4 py-3">{{ tField(item.title) }}</td>
            <td class="px-4 py-3">{{ formatPrice(item.price) }}</td>
            <td class="px-4 py-3">{{ item.type || '—' }}</td>
            <td class="px-4 py-3">{{ item.is_published ? 'Yes' : 'No' }}</td>
            <td class="px-4 py-3">
              <router-link :to="`/admin/properties/${item.id}`" class="text-purple hover:underline">Edit</router-link>
              <button type="button" class="ms-3 text-red-400 hover:underline" @click="remove(item.id)">Delete</button>
            </td>
          </tr>
          <tr v-if="!items.length">
            <td colspan="5" class="px-4 py-8 text-center text-muted">No properties yet.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
