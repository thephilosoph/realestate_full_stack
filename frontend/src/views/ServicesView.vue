<script setup lang="ts">
const topFeatures = [
  { title: 'Find Your Dream Home', icon: 'home' },
  { title: 'Unlock Property Value', icon: 'value' },
  { title: 'Effortless Property Management', icon: 'manage' },
  { title: 'Smart Investments, Informed Decisions', icon: 'invest' },
]

const selling = [
  { title: 'Valuation Mastery', body: 'Discover the true worth of your property with our expert valuation services.' },
  { title: 'Strategic Marketing', body: 'Selling a property requires more than just a listing; it demands a strategic marketing approach.' },
  { title: 'Negotiation Wizardry', body: 'Negotiating the best deal is an art, and our negotiation experts are masters of it.' },
  { title: 'Closing Success', body: 'A successful sale is not complete until the closing. We guide you through the intricate closing process.' },
]

const management = [
  { title: 'Tenant Harmony', body: 'Our Tenant Management services ensure that your tenants have a smooth and reducing vacancies.' },
  { title: 'Maintenance Ease', body: 'Say goodbye to property maintenance headaches. We handle all aspects of property upkeep.' },
  { title: 'Financial Peace of Mind', body: 'Managing property finances can be complex. Our financial experts take care of rent collection' },
  { title: 'Legal Guardian', body: 'Stay compliant with property laws and regulations effortlessly' },
]

const investment = [
  { title: 'Market Insight', body: 'Stay ahead of market trends with our expert Market Analysis. We provide in-depth insights into real estate market conditions' },
  { title: 'ROI Assessment', body: 'Make investment decisions with confidence. Our ROI Assessment services evaluate the potential returns on your investments' },
  { title: 'Customized Strategies', body: 'Every investor is unique, and so are their goals. We develop Customized Investment Strategies tailored to your specific needs' },
  { title: 'Diversification Mastery', body: 'Diversify your real estate portfolio effectively. Our experts guide you in spreading your investments across various property types and locations' },
]
</script>

<template>
  <div>
    <section class="section pb-8 pt-10 md:pt-14">
      <div class="container-estate max-w-3xl">
        <h1 class="section-title">Elevate Your Real Estate Experience</h1>
        <p class="section-desc">
          Welcome to Estatein, where your real estate aspirations meet expert guidance. Explore our comprehensive range of services, each designed to cater to your unique needs and dreams.
        </p>
      </div>
    </section>

    <section class="pb-12">
      <div class="container-estate grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <div v-for="f in topFeatures" :key="f.title" class="card relative flex min-h-[120px] flex-col gap-3 p-6">
          <div class="flex size-11 items-center justify-center rounded-full bg-purple-soft text-purple text-lg">◆</div>
          <h3 class="max-w-[160px] text-[15px] font-medium leading-snug">{{ f.title }}</h3>
          <span class="absolute right-5 top-5 text-soft">↗</span>
        </div>
      </div>
    </section>

    <!-- Unlock Property Value -->
    <section class="section pt-0">
      <div class="container-estate">
        <div class="mb-3 text-purple">✦</div>
        <h2 class="section-title">Unlock Property Value</h2>
        <p class="section-desc mb-10">
          Selling your property should be a rewarding experience, and at Estatein, we make sure it is. Our Property Selling Service is designed to maximize the value of your property, ensuring you get the best deal possible. Explore the categories below to see how we can help you at every step of your selling journey
        </p>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
          <article v-for="s in selling" :key="s.title" class="card p-6">
            <div class="mb-3 flex items-center gap-3">
              <span class="flex size-10 items-center justify-center rounded-full bg-purple-soft text-purple">●</span>
              <h3 class="font-semibold">{{ s.title }}</h3>
            </div>
            <p class="text-sm text-muted">{{ s.body }}</p>
          </article>
          <article
            class="card flex flex-col justify-between p-6 md:col-span-2 xl:col-span-1"
            style="background-image: radial-gradient(ellipse at center, rgba(112,59,247,0.15), transparent 70%)"
          >
            <div>
              <h3 class="mb-2 text-lg font-semibold">Unlock the Value of Your Property Today</h3>
              <p class="text-sm text-muted">Ready to unlock the true value of your property? Explore our Property Selling Service categories and let us help you achieve the best deal possible for your valuable asset.</p>
            </div>
            <button type="button" class="btn-secondary mt-6 self-start">Learn More</button>
          </article>
        </div>
      </div>
    </section>

    <!-- Property Management -->
    <section class="section pt-0">
      <div class="container-estate">
        <div class="mb-3 text-purple">✦</div>
        <h2 class="section-title">Effortless Property Management</h2>
        <p class="section-desc mb-10">
          Owning a property should be a pleasure, not a hassle. Estatein's Property Management Service takes the stress out of property ownership, offering comprehensive solutions tailored to your needs. Explore the categories below to see how we can make property management effortless for you
        </p>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
          <article v-for="s in management" :key="s.title" class="card p-6">
            <div class="mb-3 flex items-center gap-3">
              <span class="flex size-10 items-center justify-center rounded-full bg-purple-soft text-purple">●</span>
              <h3 class="font-semibold">{{ s.title }}</h3>
            </div>
            <p class="text-sm text-muted">{{ s.body }}</p>
          </article>
          <article
            class="card flex flex-col justify-between p-6 md:col-span-2 xl:col-span-1"
            style="background-image: radial-gradient(ellipse at center, rgba(112,59,247,0.15), transparent 70%)"
          >
            <div>
              <h3 class="mb-2 text-lg font-semibold">Experience Effortless Property Management</h3>
              <p class="text-sm text-muted">Ready to experience hassle-free property management? Explore our Property Management Service categories and let us handle the complexities while you enjoy the benefits of property ownership.</p>
            </div>
            <button type="button" class="btn-secondary mt-6 self-start">Learn More</button>
          </article>
        </div>
      </div>
    </section>

    <!-- Smart Investments -->
    <section class="section pt-0">
      <div class="container-estate">
        <div class="mb-3 text-purple">✦</div>
        <div class="mb-10 grid items-start gap-8 lg:grid-cols-2">
          <div>
            <h2 class="section-title">Smart Investments, Informed Decisions</h2>
            <p class="section-desc">
              Building a real estate portfolio requires a strategic approach. Estatein's Investment Advisory Service empowers you to make smart investments and informed decisions.
            </p>
          </div>
          <article
            class="card p-6"
            style="background-image: radial-gradient(ellipse at center, rgba(112,59,247,0.15), transparent 70%)"
          >
            <h3 class="mb-2 font-semibold">Unlock Your Investment Potential</h3>
            <p class="mb-4 text-sm text-muted">Explore our Property Management Service categories and let us handle the complexities while you enjoy the benefits of property ownership.</p>
            <button type="button" class="btn-secondary">Learn More</button>
          </article>
        </div>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
          <article v-for="s in investment" :key="s.title" class="card p-6">
            <div class="mb-3 flex items-center gap-3">
              <span class="flex size-10 items-center justify-center rounded-full bg-purple-soft text-purple">●</span>
              <h3 class="font-semibold">{{ s.title }}</h3>
            </div>
            <p class="text-sm text-muted">{{ s.body }}</p>
          </article>
        </div>
      </div>
    </section>

    <section class="pb-16 md:pb-20">
      <div class="container-estate">
        <div class="flex flex-col items-start justify-between gap-6 rounded-2xl border border-border bg-bg-elevated p-8 md:flex-row md:items-center md:p-12"
          style="background-image: radial-gradient(ellipse at top right, rgba(112,59,247,0.12), transparent 50%)">
          <div>
            <h2 class="mb-2 text-2xl font-semibold">Start Your Real Estate Journey Today</h2>
            <p class="max-w-xl text-[15px] text-muted">Your dream property is just a click away.</p>
          </div>
          <router-link to="/properties" class="btn-primary">Explore Properties</router-link>
        </div>
      </div>
    </section>
  </div>
</template>
