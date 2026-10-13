<script setup lang="ts">
import { computed, reactive } from 'vue'
import { useProperties } from '@/composables/useProperties'
import { useLocalized } from '@/composables/useLocalized'
import PropertyCard from '@/components/PropertyCard.vue'
import SectionHeader from '@/components/ui/SectionHeader.vue'
import StateBlock from '@/components/ui/StateBlock.vue'
import CtaBanner from '@/components/ui/CtaBanner.vue'
import type { PropertyFilters } from '@/types'

const { t } = useLocalized()

const filters = reactive<PropertyFilters>({
  search: '',
  type: undefined,
  perPage: 12,
  sort: 'sort_order',
  direction: 'asc',
})

const queryFilters = computed(() => ({
  search: filters.search || undefined,
  type: filters.type || undefined,
  perPage: filters.perPage,
  sort: filters.sort,
  direction: filters.direction,
}))

const { data, isLoading, error, refetch } = useProperties(queryFilters)
const properties = computed(() => data.value?.data ?? [])
const meta = computed(() => data.value?.meta)
</script>

<template>
  <div>
    <section class="section pb-8 pt-10 md:pt-14">
      <div class="container-estate max-w-3xl">
        <h1 class="section-title">{{ t('properties.title') }}</h1>
        <p class="section-desc max-w-2xl">{{ t('properties.subtitle') }}</p>
      </div>
    </section>

    <section class="pb-10">
      <div class="container-estate">
        <div class="rounded-2xl border border-border bg-bg-card p-4 md:p-5">
          <div class="mb-4 flex flex-col gap-3 md:flex-row">
            <input
              v-model="filters.search"
              type="search"
              :placeholder="t('common.searchProperty')"
              class="flex-1 rounded-[10px] border border-border-light bg-bg px-4 py-3.5 text-[15px] outline-none focus:border-purple"
              @keyup.enter="refetch()"
            />
            <button type="button" class="btn-primary shrink-0" @click="refetch()">
              {{ t('common.findProperty') }}
            </button>
          </div>
          <div class="grid grid-cols-2 gap-3 md:grid-cols-3 xl:grid-cols-5">
            <select
              v-model="filters.type"
              class="rounded-[10px] border border-border-light bg-bg px-3 py-3 text-sm text-muted outline-none focus:border-purple"
              @change="refetch()"
            >
              <option :value="undefined">{{ t('common.propertyType') }}</option>
              <option value="Villa">Villa</option>
              <option value="Apartment">Apartment</option>
              <option value="Cottage">Cottage</option>
            </select>
          </div>
        </div>
      </div>
    </section>

    <section class="section pt-0">
      <div class="container-estate">
        <SectionHeader :title="t('properties.discoverTitle')" :description="t('properties.discoverDesc')" />
        <StateBlock
          :loading="isLoading"
          :error="error"
          :empty="!properties.length"
          :empty-text="t('properties.empty')"
          @retry="refetch()"
        >
          <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
            <PropertyCard v-for="p in properties" :key="p.id" :property="p" />
          </div>
          <p v-if="meta" class="mt-6 text-sm text-muted">
            {{
              t('properties.showing', {
                page: meta.current_page,
                last: meta.last_page,
                total: meta.total,
              })
            }}
          </p>
        </StateBlock>
      </div>
    </section>

    <CtaBanner />
  </div>
</template>
