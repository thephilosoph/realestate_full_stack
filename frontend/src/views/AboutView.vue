<script setup lang="ts">
import { computed } from 'vue'
import { useLocalized } from '@/composables/useLocalized'
import { useTeamMembers, useClients } from '@/composables/useContent'
import TeamMemberCard from '@/components/TeamMemberCard.vue'
import ClientCard from '@/components/ClientCard.vue'
import SectionHeader from '@/components/ui/SectionHeader.vue'
import StateBlock from '@/components/ui/StateBlock.vue'
import CtaBanner from '@/components/ui/CtaBanner.vue'

const { t } = useLocalized()

const stats = [
  { value: '200+', label: 'Happy Customers' },
  { value: '10k+', label: 'Properties For Clients' },
  { value: '16+', label: 'Years of Experience' },
]

const values = [
  { title: 'Trust', body: 'Trust is the cornerstone of every successful real estate transaction.' },
  { title: 'Excellence', body: 'We set the bar high for ourselves. From the properties we list to the services we provide.' },
  { title: 'Client-Centric', body: 'Your dreams and needs are at the center of our universe. We listen, understand.' },
  { title: 'Our Commitment', body: 'We are dedicated to providing you with the highest level of service, professionalism and support.' },
]

const achievements = [
  { title: '3+ Years of Excellence', body: "With over 3 years in the industry, we've amassed a wealth of knowledge and experience." },
  { title: 'Happy Clients', body: 'Our greatest achievement is the satisfaction of our clients.' },
  { title: 'Industry Recognition', body: "We've earned the respect of our peers and industry leaders." },
]

const steps = [
  { n: '01', title: 'Discover a World of Possibilities', body: 'Explore our carefully curated property listings with intuitive search tools.' },
  { n: '02', title: 'Narrowing Down Your Choices', body: 'Save favorites and compare as you make your decision.' },
  { n: '03', title: 'Personalized Guidance', body: 'Our experts are a call or message away.' },
  { n: '04', title: 'See It for Yourself', body: 'Arrange viewings of the properties you are interested in.' },
  { n: '05', title: 'Making Informed Decisions', body: 'We assist with due diligence, inspections, and market analysis.' },
  { n: '06', title: 'Getting the Best Deal', body: 'Negotiate terms and secure the property at the right price.' },
]

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
          <h1 class="section-title">Our Journey</h1>
          <p class="section-desc mb-8 max-w-lg">
            Our story is one of continuous growth and evolution. We started as a small team with big dreams, determined to create a real estate platform that transcended the ordinary.
          </p>
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
            alt="Architectural model"
            class="aspect-4/3 w-full object-cover"
          />
        </div>
      </div>
    </section>

    <section class="section pt-0">
      <div class="container-estate grid items-start gap-10 lg:grid-cols-2">
        <div>
          <div class="mb-3 text-purple">✦</div>
          <h2 class="section-title">Our Values</h2>
          <p class="section-desc">Our story is one of continuous growth and evolution.</p>
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
        <SectionHeader title="Our Achievements" description="Highlights from our journey." />
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
        <SectionHeader
          title="Navigating the Estatein Experience"
          description="A straightforward process to find and purchase your dream property."
        />
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
          <article v-for="s in steps" :key="s.n" class="card p-6">
            <span class="mb-3 block text-sm text-muted">Step {{ s.n }}</span>
            <h3 class="mb-2 text-lg font-semibold">{{ s.title }}</h3>
            <p class="text-sm leading-relaxed text-muted">{{ s.body }}</p>
          </article>
        </div>
      </div>
    </section>

    <section class="section pt-0">
      <div class="container-estate">
        <SectionHeader
          title="Meet the Estatein Team"
          description="Get to know the people behind our mission."
        />
        <StateBlock :loading="teamLoading" :error="teamError" :empty="!team.length" :empty-text="t('about.emptyTeam')"
          @retry="refetchTeam()">
          <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
            <TeamMemberCard v-for="m in team" :key="m.id" :member="m" />
          </div>
        </StateBlock>
      </div>
    </section>

    <section class="section pt-0">
      <div class="container-estate">
        <SectionHeader
          title="Our Valued Clients"
          description="Some of the clients we've had the pleasure of serving."
        />
        <StateBlock :loading="clientsLoading" :error="clientsError" :empty="!clients.length" :empty-text="t('about.emptyClients')"
          @retry="refetchClients()">
          <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <ClientCard v-for="c in clients" :key="c.id" :client="c" />
          </div>
        </StateBlock>
      </div>
    </section>

    <CtaBanner />
  </div>
</template>
