<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { adminClients } from '@/api/admin/resources'
import type { Client } from '@/types'
import { useLocalized } from '@/composables/useLocalized'

const { tField } = useLocalized()
const items = ref<Client[]>([])
const loading = ref(true)

async function load() {
  loading.value = true
  try {
    items.value = (await adminClients.list({ perPage: 50 })).data
  } finally {
    loading.value = false
  }
}
async function remove(id: number) {
  if (!confirm('Delete?')) return
  await adminClients.remove(id)
  await load()
}
onMounted(load)
</script>

<template>
  <div>
    <div class="mb-6 flex items-center justify-between">
      <h1 class="text-2xl font-semibold">Clients</h1>
      <router-link to="/admin/clients/new" class="btn-primary text-sm">Add</router-link>
    </div>
    <p v-if="loading" class="text-muted">Loading…</p>
    <div v-else class="overflow-x-auto rounded-xl border border-border">
      <table class="w-full text-sm">
        <thead class="border-b border-border bg-bg-elevated text-muted">
          <tr>
            <th class="px-4 py-3">Name</th>
            <th class="px-4 py-3">Published</th>
            <th class="px-4 py-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in items" :key="item.id" class="border-b border-border/60">
            <td class="px-4 py-3">{{ tField(item.name) }}</td>
            <td class="px-4 py-3">{{ item.is_published ? 'Yes' : 'No' }}</td>
            <td class="px-4 py-3">
              <router-link :to="`/admin/clients/${item.id}`" class="text-purple hover:underline">Edit</router-link>
              <button type="button" class="ms-3 text-red-400" @click="remove(item.id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
