<template>
  <header class="bg-white shadow-sm border-b border-gray-200">
    <div class="px-6 py-2">
      <div class="flex items-center justify-between">
        <!-- Title Section -->
        <div>
          <h1 class="text-2xl font-bold text-menara-dark">
            {{ getSectionTitle() }}
          </h1>
        </div>

        <!-- Actions Section -->
        <div class="flex items-center space-x-4">
          <!-- Notifications -->
          <button class="relative p-2 text-gray-400 hover:text-menara-red transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            <span class="absolute -top-1 -right-1 h-4 w-4 bg-menara-red rounded-full flex items-center justify-center">
              <span class="text-xs text-white font-medium">3</span>
            </span>
          </button>

          <!-- User Menu -->
          <div class="relative" ref="userMenu">
            <button 
              @click="toggleDropdown" 
              class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 transition-colors"
            >
              <div class="w-8 h-8 bg-menara-red rounded-full flex items-center justify-center">
                <span class="text-white text-sm font-medium">
                  {{ getUserInitials() }}
                </span>
              </div>
              <div class="text-left">
                <p class="text-sm font-medium text-gray-900">
                  {{ user?.name || 'Responsable' }}
                </p>
                <p class="text-xs text-gray-500">Production</p>
              </div>
              <svg 
                class="w-4 h-4 text-gray-400 transition-transform duration-200"
                :class="{ 'rotate-180': isDropdownOpen }"
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>

            <!-- Dropdown Menu -->
            <div 
              v-if="isDropdownOpen"
              class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50"
            >
              <!-- User Info -->
              <div class="px-4 py-3 border-b border-gray-100">
                <p class="text-sm font-medium text-gray-900">
                  {{ user?.name || 'Responsable Production' }}
                </p>
                <p class="text-sm text-gray-500">
                  {{ user?.email || 'production@example.com' }}
                </p>
              </div>

              <!-- Menu Items -->
              <div class="py-1">
                <button
                  @click="goToProfile"
                  class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                >
                  <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                  Mon Profil
                </button>

                <button
                  @click="goToSettings"
                  class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                >
                  <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  </svg>
                  Paramètres
                </button>
              </div>

              <!-- Logout Button -->
              <div class="border-t border-gray-100 py-1">
                <button
                  @click="handleLogout"
                  :disabled="isLoggingOut"
                  class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                  </svg>
                  <span v-if="!isLoggingOut">Se déconnecter</span>
                  <span v-else>Déconnexion...</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Overlay pour fermer le dropdown -->
    <div 
      v-if="isDropdownOpen" 
      @click="closeDropdown"
      class="fixed inset-0 z-40"
    ></div>
  </header>
</template>

<script>
import { useAuth } from '@/composables/useAuth'
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'

export default {
  name: 'ProductionHeader',
  props: {
    currentSection: {
      type: String,
      default: 'categories'
    }
  },
  setup() {
    const { user, logout } = useAuth()
    const router = useRouter()
    const isDropdownOpen = ref(false)
    const isLoggingOut = ref(false)
    const userMenu = ref(null)

    const toggleDropdown = () => {
      isDropdownOpen.value = !isDropdownOpen.value
    }

    const closeDropdown = () => {
      isDropdownOpen.value = false
    }

    const getUserInitials = () => {
      if (user.value?.name) {
        return user.value.name
          .split(' ')
          .map(name => name.charAt(0))
          .join('')
          .toUpperCase()
          .substring(0, 2)
      }
      return 'RP'
    }

    const handleLogout = async () => {
      try {
        isLoggingOut.value = true
        await logout()
        router.push('/login')
      } catch (error) {
        console.error('Erreur lors de la déconnexion:', error)
        // Même en cas d'erreur, rediriger vers login
        router.push('/login')
      } finally {
        isLoggingOut.value = false
        closeDropdown()
      }
    }

    const goToProfile = () => {
      closeDropdown()
      // TODO: Implémenter la navigation vers le profil
      console.log('Navigation vers le profil')
    }

    const goToSettings = () => {
      closeDropdown()
      // TODO: Implémenter la navigation vers les paramètres
      console.log('Navigation vers les paramètres')
    }

    // Fermer le dropdown quand on clique à l'extérieur
    const handleClickOutside = (event) => {
      if (userMenu.value && !userMenu.value.contains(event.target)) {
        closeDropdown()
      }
    }

    onMounted(() => {
      document.addEventListener('click', handleClickOutside)
    })

    onUnmounted(() => {
      document.removeEventListener('click', handleClickOutside)
    })

    return {
      user,
      isDropdownOpen,
      isLoggingOut,
      userMenu,
      toggleDropdown,
      closeDropdown,
      getUserInitials,
      handleLogout,
      goToProfile,
      goToSettings
    }
  },
  methods: {
    getSectionTitle() {
      const titles = {
        categories: 'Gestion des Catégories',
        products: 'Gestion des Produits'
      }
      return titles[this.currentSection] || 'Dashboard Production'
    }
  }
}
</script>

<style scoped>
.text-menara-dark {
  color: #1f2937;
}

.bg-menara-red {
  background-color: rgb(192, 15, 26);
}

.hover\:text-menara-red:hover {
  color: rgb(192, 15, 26);
}

.rotate-180 {
  transform: rotate(180deg);
}

/* Animation pour le dropdown */
.dropdown-enter-active, .dropdown-leave-active {
  transition: all 0.2s ease;
}

.dropdown-enter-from, .dropdown-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>