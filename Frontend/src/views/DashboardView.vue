<template>

<div v-if="isLoading" class="min-h-screen bg-gray-50 flex items-center justify-center">
  <div class="text-center">
    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-menara-blue mx-auto"></div>
    <p class="mt-4 text-gray-600">Chargement...</p>
  </div>
</div>

  <div v-else class="min-h-screen bg-gray-50">
    <!-- Navigation Header -->
    <nav class="bg-gradient-to-r from-menara-blue to-menara-dark shadow-lg">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <!-- Logo -->
          <div class="flex items-center">
            <h1 class="text-xl font-bold text-white">
              Dashboard <span class="text-menara-red">MenaraPrefa</span>
            </h1>
          </div>

          

          <!-- User Menu -->
          <div class="relative">
            <button
              @click="toggleUserMenu"
              class="max-w-xs bg-menara-dark rounded-full flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-menara-red"
            >
              <div class="h-8 w-8 rounded-full bg-menara-red flex items-center justify-center">
                <span class="text-white font-medium text-sm">AD</span>
              </div>
              <span class="ml-2 text-white text-sm">{{ user?.name || 'Admin' }}</span>
            </button>
            
            <!-- Dropdown Menu -->
            <div
              v-if="showUserMenu"
              class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
            >
              <div class="py-1">
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Paramètres</a>
                <div class="border-t border-gray-100"></div>
<button @click="handleLogout" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
  Déconnexion
</button>              </div>
            </div>
          </div>

          <!-- Mobile menu button -->
          <div class="md:hidden">
            <button
              @click="toggleMobileMenu"
              class="bg-menara-dark inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-menara-red"
            >
              <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Mobile Menu -->
        <div v-if="showMobileMenu" class="md:hidden">
          <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <router-link
              to="/dashboard"
              class="text-white hover:text-menara-red block px-3 py-2 rounded-md text-base font-medium"
            >
              Tableau de bord
            </router-link>
            <router-link
              to="/orders"
              class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium"
            >
              Commandes
            </router-link>
            <router-link
              to="/"
              class="text-gray-300 hover:text-white block px-3 py-2 rounded-md text-base font-medium"
            >
              Retour à l'accueil
            </router-link>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <!-- Page Header -->
      <div class="px-4 py-6 sm:px-0">
        <div class="flex items-center justify-between mb-6">
          <div>
            <h1 class="text-2xl font-bold text-menara-dark">Tableau de Bord</h1>
            <p class="text-gray-600">Vue d'ensemble de votre activité</p>
          </div>
          <div class="text-sm text-gray-500">
            Dernière mise à jour: {{ lastUpdate }}
          </div>
        </div>

        <!-- Stats Cards -->
        <StatsCards />

        <!-- Charts Section -->
        <ChartsSection />

        <!-- User Management Section -->
        <UserManagement />
      </div>
    </main>
  </div>
</template>

<script>
// Mise à jour de la section <script> dans DashboardView.vue

import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '@/composables/useAuth'
import StatsCards from '../components/dashboard/StatsCards.vue'
import ChartsSection from '../components/dashboard/ChartsSection.vue'
import UserManagement from '../components/dashboard/UserManagement.vue'

export default {
  name: 'DashboardView',
  components: {
    StatsCards,
    ChartsSection,
    UserManagement
  },
  setup() {
    const router = useRouter()
    const { user, isAdmin, checkAuth, logout } = useAuth()
    
    const showUserMenu = ref(false)
    const showMobileMenu = ref(false)
    const lastUpdate = ref('')
    const isLoading = ref(true)

    const toggleUserMenu = () => {
      showUserMenu.value = !showUserMenu.value
    }

    const toggleMobileMenu = () => {
      showMobileMenu.value = !showMobileMenu.value
    }

    const handleLogout = async () => {
      try {
        await logout()
        router.push('/login')
      } catch (error) {
        console.error('Erreur lors de la déconnexion:', error)
        // Rediriger même en cas d'erreur
        router.push('/login')
      }
    }

    const updateLastUpdate = () => {
      const now = new Date()
      lastUpdate.value = now.toLocaleString('fr-FR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      })
    }

    // Vérifier l'authentification au montage
    const initializeDashboard = async () => {
      try {
        const isAuthenticated = await checkAuth()
        
        if (!isAuthenticated || !isAdmin.value) {
          router.push('/login')
          return
        }
        
        updateLastUpdate()
      } catch (error) {
        console.error('Erreur d\'initialisation:', error)
        router.push('/login')
      } finally {
        isLoading.value = false
      }
    }

 
    const handleClickOutside = (event) => {
      if (!event.target.closest('.relative')) {
        showUserMenu.value = false
      }
    }

    onMounted(() => {
      initializeDashboard()
      document.addEventListener('click', handleClickOutside)
    })

    return {
      user,
      showUserMenu,
      showMobileMenu,
      lastUpdate,
      isLoading,
      toggleUserMenu,
      toggleMobileMenu,
      handleLogout
    }
  }
}
</script>

<style scoped>
.from-menara-blue {
  --tw-gradient-from: #1e40af;
}

.to-menara-dark {
  --tw-gradient-to: #1f2937;
}

.bg-menara-blue {
  background-color: #1e40af;
}

.bg-menara-dark {
  background-color: #1f2937;
}

.text-menara-red {
  color: rgb(192, 15, 26);
}

.bg-menara-red {
  background-color: rgb(192, 15, 26);
}

.text-menara-dark {
  color: #1f2937;
}

.hover\:text-menara-red:hover {
  color: rgb(192, 15, 26);
}

.focus\:ring-menara-red:focus {
  --tw-ring-color: rgb(192, 15, 26);
}

/* Animation pour les transitions */
.transition-colors {
  transition-property: color, background-color, border-color, text-decoration-color, fill, stroke;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}
</style>