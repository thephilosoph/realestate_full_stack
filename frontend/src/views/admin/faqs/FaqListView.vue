<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { adminFaqs } from '@/api/admin/resources'
import type { Faq } from '@/types'
import { useLocalized } from '@/composables/useLocalized'

const { tField } = useLocalized()
const items = ref<Faq[]>([])
const loading = ref(true)
const error = ref('')

async function load() {
  loading.value = true
  try {
    const res = await adminFaqs.list({ perPage: 50, sort: 'sort_order' })
    items.value = res.data
  } catch (e: unknown) {
    error.value = e instanceof Error ? e.message : 'Failed to load'
  } finally {
    loading.value = false
  }
}

async function remove(id: number) {
  if (!confirm('Delete this FAQ?')) return
  await adminFaqs.remove(id)
  await load()
}

onMounted(load)
</script>

<template>
  <div>
    <div class="mb-6 flex items-center justify-between gap-3">
      <h1 class="text-2xl font-semibold">FAQs</h1>
      <router-link to="/admin/faqs/new" class="btn-primary text-sm">Add FAQ</router-link>
    </div>
    <p v-if="loading" class="text-muted">Loading…</p>
    <p v-else-if="error" class="text-red-400">{{ error }}</p>
    <div v-else class="overflow-x-auto rounded-xl border border-border">
      <table class="w-full text-sm">
        <thead class="border-b border-border bg-bg-elevated text-muted">
          <tr>
            <th class="px-4 py-3 font-medium">Question</th>
            <th class="px-4 py-3 font-medium">Published</th>
            <th class="px-4 py-3 font-medium">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in items" :key="item.id" class="border-b border-border/60">
            <td class="px-4 py-3">{{ tField(item.question) }}</td>
            <td class="px-4 py-3">{{ item.is_published ? 'Yes' : 'No' }}</td>
            <td class="px-4 py-3">
              <router-link :to="`/admin/faqs/${item.id}`" class="text-purple hover:underline">Edit</router-link>
              <button type="button" class="ms-3 text-red-400 hover:underline" @click="remove(item.id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
