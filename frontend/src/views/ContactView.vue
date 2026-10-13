<script setup lang="ts">
const contacts = [
  { label: 'info@estatein.com', sub: 'Email', icon: '✉' },
  { label: '+1 (123) 456-7890', sub: 'Phone', icon: '☎' },
  { label: 'Main Headquarters', sub: 'Location', icon: '📍' },
  { label: 'Instagram  LinkedIn  Facebook', sub: 'Social', icon: '◎' },
]

const offices = [
  {
    type: 'Main Headquarters',
    address: '123 Estatein Plaza, City Center, Metropolis',
    body: 'Our main headquarters serve as the heart of Estatein. Located in the bustling city center, this is where our core team of experts operates, driving the excellence and innovation that define us.',
  },
  {
    type: 'Regional Offices',
    address: '456 Urban Avenue, Downtown District, Metropolis',
    body: "Estatein's presence extends to multiple regions, each with its own dynamic real estate landscape. Discover our regional offices, staffed by local experts who understand the nuances of their respective markets.",
  },
]
</script>

<template>
  <div>
    <section class="section pb-8 pt-10 md:pt-14">
      <div class="container-estate max-w-3xl">
        <h1 class="section-title">Get in Touch with Estatein</h1>
        <p class="section-desc">
          Welcome to Estatein's Contact Us page. We're here to assist you with any inquiries, requests, or feedback you may have. Whether you're looking to buy or sell a property, explore investment opportunities, or simply want to connect, we're just a message away. Reach out to us, and let's start a conversation.
        </p>
      </div>
    </section>

    <section class="pb-12">
      <div class="container-estate grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <div v-for="c in contacts" :key="c.label" class="card relative flex min-h-[110px] flex-col justify-center gap-2 p-6">
          <span class="text-purple">{{ c.icon }}</span>
          <strong class="text-[15px] font-medium">{{ c.label }}</strong>
          <span class="absolute right-5 top-5 text-soft">↗</span>
        </div>
      </div>
    </section>

    <!-- Form -->
    <section class="section pt-0">
      <div class="container-estate">
        <div class="mb-3 text-purple">✦</div>
        <h2 class="section-title">Let's Connect</h2>
        <p class="section-desc mb-10">
          We're excited to connect with you and learn more about your real estate goals. Use the form below to get in touch with Estatein. Whether you're a prospective client, partner, or simply curious about our services, we're here to answer your questions and provide the assistance you need.
        </p>
        <form class="card grid grid-cols-1 gap-4 p-6 md:grid-cols-3 md:p-8" @submit.prevent>
          <label class="flex flex-col gap-2 text-sm">First Name
            <input type="text" placeholder="Enter First Name" class="rounded-[10px] border border-border-light bg-bg px-4 py-3 outline-none focus:border-purple" />
          </label>
          <label class="flex flex-col gap-2 text-sm">Last Name
            <input type="text" placeholder="Enter Last Name" class="rounded-[10px] border border-border-light bg-bg px-4 py-3 outline-none focus:border-purple" />
          </label>
          <label class="flex flex-col gap-2 text-sm">Email
            <input type="email" placeholder="Enter your Email" class="rounded-[10px] border border-border-light bg-bg px-4 py-3 outline-none focus:border-purple" />
          </label>
          <label class="flex flex-col gap-2 text-sm">Phone
            <input type="tel" placeholder="Enter Phone Number" class="rounded-[10px] border border-border-light bg-bg px-4 py-3 outline-none focus:border-purple" />
          </label>
          <label class="flex flex-col gap-2 text-sm">Inquiry Type
            <select class="rounded-[10px] border border-border-light bg-bg px-4 py-3 text-muted outline-none focus:border-purple">
              <option>Select Inquiry Type</option>
            </select>
          </label>
          <label class="flex flex-col gap-2 text-sm">How Did You Hear About Us?
            <select class="rounded-[10px] border border-border-light bg-bg px-4 py-3 text-muted outline-none focus:border-purple">
              <option>Select</option>
            </select>
          </label>
          <label class="flex flex-col gap-2 text-sm md:col-span-3">Message
            <textarea rows="5" placeholder="Enter your Message here." class="rounded-[10px] border border-border-light bg-bg px-4 py-3 outline-none focus:border-purple" />
          </label>
          <div class="flex flex-col gap-4 md:col-span-3 md:flex-row md:items-center md:justify-between">
            <label class="flex items-center gap-2 text-sm text-muted">
              <input type="checkbox" class="accent-purple" /> I agree with Terms of Use and Privacy Policy
            </label>
            <button type="submit" class="btn-primary">Send Your Message</button>
          </div>
        </form>
      </div>
    </section>

    <!-- Offices -->
    <section class="section pt-0">
      <div class="container-estate">
        <div class="mb-3 text-purple">✦</div>
        <h2 class="section-title">Discover Our Office Locations</h2>
        <p class="section-desc mb-6">
          Estatein is here to serve you across multiple locations. Whether you're looking to meet our team, discuss real estate opportunities, or simply drop by for a chat, we have offices conveniently located to serve your needs. Explore the categories below to find the Estatein office nearest to you
        </p>
        <div class="mb-6 flex flex-wrap gap-2">
          <button type="button" class="btn-primary py-2 px-4 text-sm">All</button>
          <button type="button" class="btn-ghost py-2 px-4 text-sm">Regional</button>
          <button type="button" class="btn-ghost py-2 px-4 text-sm">International</button>
        </div>
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
          <article v-for="o in offices" :key="o.type" class="card p-7">
            <span class="mb-2 block text-sm text-muted">{{ o.type }}</span>
            <h3 class="mb-3 text-xl font-semibold">{{ o.address }}</h3>
            <p class="mb-5 text-sm leading-relaxed text-muted">{{ o.body }}</p>
            <div class="mb-5 flex flex-wrap gap-2 text-xs text-muted">
              <span class="chip">info@estatein.com</span>
              <span class="chip">+1 (123) 456-7890</span>
              <span class="chip">Metropolis</span>
            </div>
            <button type="button" class="btn-primary w-full">Get Direction</button>
          </article>
        </div>
      </div>
    </section>

    <!-- Gallery -->
    <section class="section pt-0">
      <div class="container-estate">
        <div class="grid grid-cols-2 gap-3 md:grid-cols-4 md:grid-rows-2">
          <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=600&q=80" alt="" class="col-span-1 row-span-1 aspect-video rounded-xl object-cover md:col-span-1" />
          <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=600&q=80" alt="" class="aspect-video rounded-xl object-cover" />
          <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=600&q=80" alt="" class="aspect-video rounded-xl object-cover md:row-span-2 md:aspect-auto md:h-full" />
          <img src="https://images.unsplash.com/photo-1556761175-5973dc0f32e7?w=600&q=80" alt="" class="aspect-video rounded-xl object-cover" />
          <div class="col-span-2 flex flex-col justify-end rounded-xl border border-border bg-bg-card p-6 md:col-span-2">
            <div class="mb-2 text-purple">✦</div>
            <h3 class="mb-2 text-xl font-semibold">Explore Estatein's World</h3>
            <p class="text-sm text-muted">Step inside the world of Estatein, where professionalism meets warmth, and expertise meets passion. Our gallery offers a glimpse into our team and workspaces, inviting you to get to know us better.</p>
          </div>
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
