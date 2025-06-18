<template>
  <section class="relative bg-gradient-to-br from-menara-blue to-menara-dark text-white overflow-hidden">
    <!-- Navigation -->
    <nav class="relative z-20 bg-gradient-to-br from-menara-blue to-menara-dark">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Menu mobile -->
        <div v-if="mobileMenuOpen" class="md:hidden bg-menara-dark/90 rounded-lg mt-2 p-4">
          <div class="flex flex-col space-y-4">
            <router-link to="/" class="hover:text-menara-red transition-colors">Accueil</router-link>
            <router-link to="/about" class="hover:text-menara-red transition-colors">À propos</router-link>
            <router-link to="/services" class="hover:text-menara-red transition-colors">Services</router-link>
            <router-link to="/contact" class="hover:text-menara-red transition-colors">Contact</router-link>
          </div>
        </div>
      </div>
    </nav>

    <!-- Image de fond -->
    <div class="absolute inset-0 z-0">
      <img 
        src="/assets/images/preaf1.png" 
        alt="Menara Background" 
        class="w-full h-full object-cover opacity-40"
      />
      <!-- Overlay gradient pour améliorer la lisibilité -->
      <div class="absolute inset-0 bg-gradient-to-br from-menara-blue/10 to-menara-dark/30"></div>
    </div>

    <!-- Contenu principal -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
      <div class="text-center">
        <h1 class="text-4xl md:text-6xl font-bold mb-6 drop-shadow-lg">
          Plateforme de Gestion
          <span class="text-menara-red block">Ménara Préfa</span>
        </h1>
        <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto opacity-90 drop-shadow-md">
          Votre solution digitale pour optimiser la gestion des commandes 
          et le suivi de production de produits préfabriqués en béton
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <router-link 
            to="/login"
            class="bg-menara-red hover:bg-red-700 px-8 py-3 rounded-lg font-semibold text-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl"
          >
            Accéder à la Plateforme
          </router-link>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'HeroSection',
  data() {
    return {
      mobileMenuOpen: false
    }
  },
  methods: {
    toggleMobileMenu() {
      this.mobileMenuOpen = !this.mobileMenuOpen
    }
  }
}
</script>

<style scoped>
/* Transitions pour les liens */
.router-link-active {
  color: #dc2626; /* menara-red */
}

/* Animation pour les éléments décoratifs */
@keyframes pulse {
  0%, 100% {
    opacity: 0.4;
    transform: scale(1);
  }
  50% {
    opacity: 0.8;
    transform: scale(1.2);
  }
}

.animate-pulse {
  animation: pulse 2s ease-in-out infinite;
}

/* Amélioration de l'accessibilité */
@media (prefers-reduced-motion: reduce) {
  .animate-pulse,
  .transform,
  .hover\:scale-105 {
    animation: none;
    transform: none;
  }
}

/* Navigation responsive */
@media (max-width: 768px) {
  .text-4xl {
    font-size: 2.5rem;
  }
  
  .md\:text-6xl {
    font-size: 3rem;
  }
}
</style>