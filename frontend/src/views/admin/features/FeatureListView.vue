<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { adminFeatures, type Feature } from '@/api/admin/resources'
import { useLocalized } from '@/composables/useLocalized'

const { tField } = useLocalized()
const items = ref<Feature[]>([])
const loading = ref(true)
const error = ref('')
const search = ref('')

async function load() {
  loading.value = true
  error.value = ''
  try {
    const res = await adminFeatures.list({ search: search.value || undefined, perPage: 50 })
    items.value = res.data
  } catch (e: unknown) {
    error.value = e instanceof Error ? e.message : 'Failed to load'
  } finally {
    loading.value = false
  }
}

async function remove(id: number) {
  if (!confirm('Delete this feature?')) return
  await adminFeatures.remove(id)
  await load()
}

onMounted(load)
</script>

<template>
  <div>
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
      <h1 class="text-2xl font-semibold">Features</h1>
      <router-link to="/admin/features/new" class="btn-primary text-sm">Add feature</router-link>
    </div>
    <div class="mb-4 flex gap-2">
      <input v-model="search" type="search" placeholder="Search…" class="rounded-lg border border-border-light bg-bg px-3 py-2 text-sm outline-none focus:border-purple" @keyup.enter="load" />
      <button type="button" class="btn-secondary text-sm py-2" @click="load">Search</button>
    </div>
    <p v-if="loading" class="text-muted">Loading…</p>
    <p v-else-if="error" class="text-red-400">{{ error }}</p>
    <div v-else class="overflow-x-auto rounded-xl border border-border">
      <table class="w-full text-start text-sm">
        <thead class="border-b border-border bg-bg-elevated text-muted">
          <tr>
            <th class="px-4 py-3 font-medium">ID</th>
            <th class="px-4 py-3 font-medium">Name</th>
            <th class="px-4 py-3 font-medium">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in items" :key="item.id" class="border-b border-border/60">
            <td class="px-4 py-3">{{ item.id }}</td>
            <td class="px-4 py-3">{{ tField(item.name) }}</td>
            <td class="px-4 py-3">
              <router-link :to="`/admin/features/${item.id}`" class="text-purple hover:underline">Edit</router-link>
              <button type="button" class="ms-3 text-red-400 hover:underline" @click="remove(item.id)">Delete</button>
            </td>
          </tr>
          <tr v-if="!items.length">
            <td colspan="3" class="px-4 py-8 text-center text-muted">No features yet.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
