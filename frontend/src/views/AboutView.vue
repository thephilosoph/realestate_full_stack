<script setup lang="ts">
import { computed } from 'vue'
import { useTeamMembers, useClients } from '@/composables/useContent'
import { useLocalized } from '@/composables/useLocalized'
import TeamMemberCard from '@/components/TeamMemberCard.vue'
import ClientCard from '@/components/ClientCard.vue'
import SectionHeader from '@/components/ui/SectionHeader.vue'
import StateBlock from '@/components/ui/StateBlock.vue'
import CtaBanner from '@/components/ui/CtaBanner.vue'

const { t } = useLocalized()

const stats = computed(() => [
  { value: '200+', label: t('about.statCustomers') },
  { value: '10k+', label: t('about.statProperties') },
  { value: '16+', label: t('about.statYears') },
])

const values = computed(() => [
  { title: t('about.valueTrust'), body: t('about.valueTrustBody') },
  { title: t('about.valueExcellence'), body: t('about.valueExcellenceBody') },
  { title: t('about.valueClient'), body: t('about.valueClientBody') },
  { title: t('about.valueCommitment'), body: t('about.valueCommitmentBody') },
])

const achievements = computed(() => [
  { title: t('about.ach1Title'), body: t('about.ach1Body') },
  { title: t('about.ach2Title'), body: t('about.ach2Body') },
  { title: t('about.ach3Title'), body: t('about.ach3Body') },
])

const steps = computed(() => [
  { n: '01', title: t('about.step1Title'), body: t('about.step1Body') },
  { n: '02', title: t('about.step2Title'), body: t('about.step2Body') },
  { n: '03', title: t('about.step3Title'), body: t('about.step3Body') },
  { n: '04', title: t('about.step4Title'), body: t('about.step4Body') },
  { n: '05', title: t('about.step5Title'), body: t('about.step5Body') },
  { n: '06', title: t('about.step6Title'), body: t('about.step6Body') },
])

const { data: teamRes, isLoading: teamLoading, error: teamError, refetch: refetchTeam } = useTeamMembers(8)
const team = computed(() => teamRes.value?.data ?? [])

const { data: clientsRes, isLoading: clientsLoading, error: clientsError, refetch: refetchClients } = useClients(6)
const clients = computed(() => clientsRes.value?.data ?? [])
</script>

<template>
  <div>
    <section class="section pt-10 md:pt-14">
      <div class="container-estate grid items-center gap-10 lg:grid-cols-2">
        <div>
          <div class="mb-3 text-purple">✦</div>
          <h1 class="section-title">{{ t('about.journeyTitle') }}</h1>
          <p class="section-desc mb-8 max-w-lg">{{ t('about.journeyBody') }}</p>
          <div class="flex flex-wrap gap-3">
            <div
              v-for="s in stats"
              :key="s.label"
              class="min-w-[130px] rounded-xl border border-border bg-bg-elevated px-5 py-4"
            >
              <strong class="mb-1 block text-2xl font-semibold">{{ s.value }}</strong>
              <span class="text-[13px] text-muted">{{ s.label }}</span>
            </div>
          </div>
        </div>
        <div class="overflow-hidden rounded-2xl border border-border">
          <img
            src="https://images.unsplash.com/photo-1582407947304-fd86f028f716?w=900&q=80"
            alt=""
            class="aspect-4/3 w-full object-cover"
          />
        </div>
      </div>
    </section>

    <section class="section pt-0">
      <div class="container-estate grid items-start gap-10 lg:grid-cols-2">
        <div>
          <div class="mb-3 text-purple">✦</div>
          <h2 class="section-title">{{ t('about.valuesTitle') }}</h2>
          <p class="section-desc">{{ t('about.valuesBody') }}</p>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <article v-for="v in values" :key="v.title" class="rounded-2xl border border-border bg-bg-card p-6">
            <div class="mb-3 flex items-center gap-2">
              <span class="flex size-9 items-center justify-center rounded-full bg-purple-soft text-purple">★</span>
              <h3 class="font-semibold">{{ v.title }}</h3>
            </div>
            <p class="text-sm leading-relaxed text-muted">{{ v.body }}</p>
          </article>
        </div>
      </div>
    </section>

    <section class="section pt-0">
      <div class="container-estate">
        <SectionHeader :title="t('about.achievementsTitle')" :description="t('about.achievementsDesc')" />
        <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
          <article v-for="a in achievements" :key="a.title" class="card p-7">
            <h3 class="mb-3 text-xl font-semibold">{{ a.title }}</h3>
            <p class="text-sm leading-relaxed text-muted">{{ a.body }}</p>
          </article>
        </div>
      </div>
    </section>

    <section class="section pt-0">
      <div class="container-estate">
        <SectionHeader :title="t('about.stepsTitle')" :description="t('about.stepsDesc')" />
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
          <article v-for="s in steps" :key="s.n" class="card p-6">
            <span class="mb-3 block text-sm text-muted">{{ t('about.stepLabel') }} {{ s.n }}</span>
            <h3 class="mb-2 text-lg font-semibold">{{ s.title }}</h3>
            <p class="text-sm leading-relaxed text-muted">{{ s.body }}</p>
          </article>
        </div>
      </div>
    </section>

    <section class="section pt-0">
      <div class="container-estate">
        <SectionHeader :title="t('about.teamTitle')" :description="t('about.teamDesc')" />
        <StateBlock
          :loading="teamLoading"
          :error="teamError"
          :empty="!team.length"
          :empty-text="t('about.emptyTeam')"
          @retry="refetchTeam()"
        >
          <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
            <TeamMemberCard v-for="m in team" :key="m.id" :member="m" />
          </div>
        </StateBlock>
      </div>
    </section>

    <section class="section pt-0">
      <div class="container-estate">
        <SectionHeader :title="t('about.clientsTitle')" :description="t('about.clientsDesc')" />
        <StateBlock
          :loading="clientsLoading"
          :error="clientsError"
          :empty="!clients.length"
          :empty-text="t('about.emptyClients')"
          @retry="refetchClients()"
        >
          <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <ClientCard v-for="c in clients" :key="c.id" :client="c" />
          </div>
        </StateBlock>
      </div>
    </section>

    <CtaBanner />
  </div>
</template>