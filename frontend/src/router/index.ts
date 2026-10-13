import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

import HomeView from '@/views/HomeView.vue'
import AboutView from '@/views/AboutView.vue'
import PropertiesView from '@/views/PropertiesView.vue'
import ServicesView from '@/views/ServicesView.vue'
import ContactView from '@/views/ContactView.vue'
import PropertyDetailView from '@/views/PropertyDetailView.vue'

import AdminLoginView from '@/views/admin/AdminLoginView.vue'
import AdminLayout from '@/views/admin/AdminLayout.vue'
import DashboardView from '@/views/admin/DashboardView.vue'

import PropertyListView from '@/views/admin/properties/PropertyListView.vue'
import PropertyFormView from '@/views/admin/properties/PropertyFormView.vue'
import FeatureListView from '@/views/admin/features/FeatureListView.vue'
import FeatureFormView from '@/views/admin/features/FeatureFormView.vue'
import FaqListView from '@/views/admin/faqs/FaqListView.vue'
import FaqFormView from '@/views/admin/faqs/FaqFormView.vue'
import TestimonialListView from '@/views/admin/testimonials/TestimonialListView.vue'
import TestimonialFormView from '@/views/admin/testimonials/TestimonialFormView.vue'
import ClientListView from '@/views/admin/clients/ClientListView.vue'
import ClientFormView from '@/views/admin/clients/ClientFormView.vue'
import TeamListView from '@/views/admin/team/TeamListView.vue'
import TeamFormView from '@/views/admin/team/TeamFormView.vue'

export const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/', name: 'home', component: HomeView },
    { path: '/about', name: 'about', component: AboutView },
    { path: '/properties', name: 'properties', component: PropertiesView },
    { path: '/properties/:id', name: 'property-detail', component: PropertyDetailView },
    { path: '/services', name: 'services', component: ServicesView },
    { path: '/contact', name: 'contact', component: ContactView },

    {
      path: '/admin/login',
      name: 'admin-login',
      component: AdminLoginView,
      meta: { guest: true },
    },
    {
      path: '/admin',
      component: AdminLayout,
      meta: { requiresAuth: true },
      children: [
        { path: '', name: 'admin-dashboard', component: DashboardView },
        { path: 'properties', name: 'admin-properties', component: PropertyListView },
        { path: 'properties/new', name: 'admin-property-new', component: PropertyFormView },
        { path: 'properties/:id', name: 'admin-property-edit', component: PropertyFormView },
        { path: 'features', name: 'admin-features', component: FeatureListView },
        { path: 'features/new', name: 'admin-feature-new', component: FeatureFormView },
        { path: 'features/:id', name: 'admin-feature-edit', component: FeatureFormView },
        { path: 'faqs', name: 'admin-faqs', component: FaqListView },
        { path: 'faqs/new', name: 'admin-faq-new', component: FaqFormView },
        { path: 'faqs/:id', name: 'admin-faq-edit', component: FaqFormView },
        { path: 'testimonials', name: 'admin-testimonials', component: TestimonialListView },
        { path: 'testimonials/new', name: 'admin-testimonial-new', component: TestimonialFormView },
        { path: 'testimonials/:id', name: 'admin-testimonial-edit', component: TestimonialFormView },
        { path: 'clients', name: 'admin-clients', component: ClientListView },
        { path: 'clients/new', name: 'admin-client-new', component: ClientFormView },
        { path: 'clients/:id', name: 'admin-client-edit', component: ClientFormView },
        { path: 'team', name: 'admin-team', component: TeamListView },
        { path: 'team/new', name: 'admin-team-new', component: TeamFormView },
        { path: 'team/:id', name: 'admin-team-edit', component: TeamFormView },
      ],
    },
  ],
  scrollBehavior() {
    return { top: 0 }
  },
})

router.beforeEach(async (to) => {
  const auth = useAuthStore()
  if (!auth.bootstrapped) {
    await auth.bootstrap()
  }

  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return { name: 'admin-login', query: { redirect: to.fullPath } }
  }

  if (to.meta.guest && auth.isAuthenticated) {
    return { name: 'admin-dashboard' }
  }

  return true
})
