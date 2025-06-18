<template>
  <div class="min-h-screen bg-gradient-to-br from-menara-blue to-menara-dark flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <div class="mx-auto h-12 w-auto flex justify-center">
          <img class="h-12 w-auto filter brightness-0 invert" src="/assets/images/logo.png" alt="Menara Prefa Logo" />
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-white">
          Se connecter
        </h2>
        <p class="mt-2 text-center text-sm text-gray-300">
          Accédez à votre espace Ménara Préfa
        </p>
      </div>
      
      <form class="mt-8 space-y-6" @submit.prevent="handleLogin">
        <div class="bg-white rounded-xl shadow-lg p-8 space-y-6">
          
          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-menara-dark">
              Adresse email
            </label>
            <div class="mt-1">
              <input
                id="email"
                name="email"
                type="email"
                required
                v-model="loginForm.email"
                class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-menara-red focus:border-menara-red focus:z-10 sm:text-sm"
                placeholder="votre.email@exemple.com"
              />
            </div>
          </div>

          <!-- Mot de passe -->
          <div>
            <label for="password" class="block text-sm font-medium text-menara-dark">
              Mot de passe
            </label>
            <div class="mt-1 relative">
              <input
                id="password"
                name="password"
                :type="showPassword ? 'text' : 'password'"
                required
                v-model="loginForm.password"
                class="appearance-none relative block w-full px-3 py-2 pr-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-menara-red focus:border-menara-red focus:z-10 sm:text-sm"
                placeholder="Votre mot de passe"
              />
              <button
                type="button"
                @click="togglePasswordVisibility"
                class="absolute inset-y-0 right-0 pr-3 flex items-center"
              >
                <svg v-if="!showPassword" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg v-else class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                </svg>
              </button>
            </div>
          </div>

          <!-- Se souvenir de moi et mot de passe oublié -->
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input
                id="remember-me"
                name="remember-me"
                type="checkbox"
                v-model="loginForm.rememberMe"
                class="h-4 w-4 text-menara-red focus:ring-menara-red border-gray-300 rounded"
              />
              <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                Se souvenir de moi
              </label>
            </div>

            <div class="text-sm">
              <a href="#" class="font-medium text-menara-red hover:text-red-700 transition-colors">
                Mot de passe oublié ?
              </a>
            </div>
          </div>

          <div>
            <button
              type="submit"
              :disabled="isLoading"
              class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-menara-red hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-menara-red transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                <svg v-if="!isLoading" class="h-5 w-5 text-red-300 group-hover:text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                </svg>
                <svg v-else class="animate-spin h-5 w-5 text-red-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
              </span>
              {{ isLoading ? 'Connexion...' : 'Se connecter' }}
            </button>
          </div>

          <div class="text-center">
            <p class="text-sm text-gray-600">
              Vous n'avez pas de compte ? 
              <router-link to="/register" class="font-medium text-menara-red hover:text-red-700 transition-colors">
                Créer un compte
              </router-link>
            </p>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

export default {
  name: 'LoginView',
  setup() {
    const router = useRouter()
    
    const loginForm = ref({
      email: '',
      password: '',
      rememberMe: false
    })

    const showPassword = ref(false)
    const isLoading = ref(false)

    const togglePasswordVisibility = () => {
      showPassword.value = !showPassword.value
    }

    const handleLogin = async () => {
      isLoading.value = true
      
      try {
        // Simulation d'une requête de connexion
        await new Promise(resolve => setTimeout(resolve, 1500))
        
        // Ici vous pouvez ajouter la logique de connexion
        console.log('Données de connexion:', loginForm.value)
        
        // Simulation de connexion réussie
        alert('Connexion réussie!')
        
        // Redirection vers le dashboard ou la page d'accueil
        router.push('/')
        
      } catch (error) {
        console.error('Erreur de connexion:', error)
        alert('Erreur de connexion. Veuillez réessayer.')
      } finally {
        isLoading.value = false
      }
    }

    return {
      loginForm,
      showPassword,
      isLoading,
      togglePasswordVisibility,
      handleLogin
    }
  }
}
</script>