<template>
  <main class="flex-1">
    <HeroSection />
    <AboutSection />
    <ServicesSection />
  </main>
</template>

<script>
import HeroSection from '../components/home/HeroSection.vue'
import AboutSection from '../components/home/AboutSection.vue'
import ServicesSection from '../components/home/ServicesSection.vue'

export default {
  name: 'HomeView',
  components: {
    HeroSection,
    AboutSection,
    ServicesSection
  }
}
</script>