<template>
  <header class="bg-gradient-to-r from-blue-700 to-blue-800 text-white shadow-lg">
    <div class="px-6 py-4">
      <div class="flex items-center justify-between">
        <!-- Logo et titre -->
        <div class="flex items-center space-x-4">
          <div>
            <h1 class="text-xl font-bold">Ménara Préfa</h1>
            <p class="text-blue-200 text-sm">Tableau de Bord Client</p>
          </div>
        </div>

        <!-- Navigation et profil -->
        <div class="flex items-center space-x-6">
          <!-- Notifications -->
          <button class="relative p-2 hover:bg-blue-600 rounded-lg transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zm-7.5-3h5v-2h-5v2zm0-4h8v-2h-8v2zm0-4h8V4h-8v2z"/>
            </svg>
            <span class="absolute -top-1 -right-1 bg-red-600 text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
          </button>

          <!-- Profil utilisateur -->
          <div class="flex items-center space-x-3">
            <div class="text-right">
              <p class="font-medium">{{ user?.name || 'Utilisateur' }}</p>
              <p class="text-blue-200 text-sm">{{ user?.email || 'email@example.com' }}</p>
            </div>
            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
            </div>
          </div>

          <!-- Menu déconnexion -->
          <button 
            @click="handleLogout" 
            :disabled="isLoading"
            class="p-2 hover:bg-blue-600 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            title="Se déconnecter"
          >
            <svg v-if="!isLoading" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3v1"/>
            </svg>
            <!-- Spinner de chargement -->
            <svg v-else class="w-6 h-6 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
import { useAuth } from '@/composables/useAuth'
import { useRouter } from 'vue-router'

export default {
  name: 'DashboardHeader',
  setup() {
    const { user, logout, isLoading } = useAuth()
    const router = useRouter()

    const handleLogout = async () => {
      try {
        await logout()
        router.push('/login')
      } catch (error) {
        console.error('Erreur lors de la déconnexion:', error)
        router.push('/login')
      }
    }

    return {
      user,
      isLoading,
      handleLogout
    }
  }
}
</script>