<template>
  <header class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Logo -->
        <div class="flex items-center">
          <router-link to="/" class="flex items-center">
            <img src="/assets/images/logo.png" alt="Menara Prefa Logo" class="h-10 w-auto" />
            <!-- <span class="ml-3 text-xl font-bold text-menara-dark">
              Ménara Préfa
            </span> -->
          </router-link>
        </div>

        <!-- Navigation -->
        <nav class="hidden md:flex space-x-8">
          <router-link to="/" class="text-menara-gray hover:text-menara-red transition-colors font-medium"
            :class="{ 'text-menara-red': $route.path === '/' }">
            Accueil
          </router-link>
          <router-link to="/products" class="text-menara-gray hover:text-menara-red transition-colors font-medium"
            :class="{ 'text-menara-red': $route.path === '/products' }">
            Nos Produits
          </router-link>
          <router-link to="/register" class="text-menara-gray hover:text-menara-red transition-colors font-medium"
            :class="{ 'text-menara-red': $route.path === '/register' }">
            S'inscrire
          </router-link>
          <router-link to="/login" class="text-menara-gray hover:text-menara-red transition-colors font-medium"
            :class="{ 'text-menara-red': $route.path === '/login' }">
            Se connecter
          </router-link>
        </nav>

        <!-- Menu mobile -->
        <div class="md:hidden">
          <button @click="toggleMobileMenu" class="text-menara-gray hover:text-menara-red p-2">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Menu mobile dropdown -->
      <div v-if="isMobileMenuOpen" class="md:hidden pb-4">
        <div class="flex flex-col space-y-2">
          <router-link to="/" class="text-menara-gray hover:text-menara-red py-2 font-medium"
            :class="{ 'text-menara-red': $route.path === '/' }" @click="closeMobileMenu">
            Accueil
          </router-link>
          <a href="#" class="text-menara-gray hover:text-menara-red py-2 font-medium">
            Nos Produits
          </a>
          <router-link to="/register" class="text-menara-gray hover:text-menara-red py-2 font-medium"
            :class="{ 'text-menara-red': $route.path === '/register' }" @click="closeMobileMenu">
            S'inscrire
          </router-link>
          <router-link to="/login" class="text-menara-gray hover:text-menara-red py-2 font-medium"
            :class="{ 'text-menara-red': $route.path === '/login' }" @click="closeMobileMenu">
            Se connecter
          </router-link>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
import { ref } from 'vue'

export default {
  name: 'AppHeader',
  setup() {
    const isMobileMenuOpen = ref(false)

    const toggleMobileMenu = () => {
      isMobileMenuOpen.value = !isMobileMenuOpen.value
    }

    const closeMobileMenu = () => {
      isMobileMenuOpen.value = false
    }

    return {
      isMobileMenuOpen,
      toggleMobileMenu,
      closeMobileMenu
    }
  }
}
</script>

<style scoped>
/* Définition des couleurs personnalisées */
.text-menara-dark {
  color: #1f2937;
}

.text-menara-gray {
  color: #6b7280;
}

.text-menara-red {
  color: rgb(192, 15, 26);
}

.hover\:text-menara-red:hover {
  color: rgb(192, 15, 26);
}

/* Styles pour les liens actifs */
.router-link-active {
  color: rgb(192, 15, 26);
}
</style>