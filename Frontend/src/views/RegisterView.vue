<template>
  <div class="min-h-screen bg-gradient-to-br from-menara-blue to-menara-dark flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <div class="mx-auto h-12 w-auto flex justify-center">
          <img class="h-12 w-auto filter brightness-0 invert" src="/assets/images/logo.png" alt="Menara Prefa Logo" />
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-white">
          Créer un compte
        </h2>
        <p class="mt-2 text-center text-sm text-gray-300">
          Rejoignez la plateforme Ménara Préfa
        </p>
      </div>
      
      <form class="mt-8 space-y-6" @submit.prevent="handleRegister">
        <div class="bg-white rounded-xl shadow-lg p-8 space-y-6">
          
          <!-- Nom -->
          <div>
            <label for="nom" class="block text-sm font-medium text-menara-dark">
              Nom complet
            </label>
            <div class="mt-1">
              <input
                id="nom"
                name="nom"
                type="text"
                required
                v-model="form.nom"
                class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-menara-red focus:border-menara-red focus:z-10 sm:text-sm"
                placeholder="Entrez votre nom complet"
              />
            </div>
          </div>

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
                v-model="form.email"
                class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-menara-red focus:border-menara-red focus:z-10 sm:text-sm"
                placeholder="votre.email@exemple.com"
              />
            </div>
          </div>

          <!-- Téléphone -->
          <div>
            <label for="telephone" class="block text-sm font-medium text-menara-dark">
              Numéro de téléphone
            </label>
            <div class="mt-1">
              <input
                id="telephone"
                name="telephone"
                type="tel"
                required
                v-model="form.telephone"
                class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-menara-red focus:border-menara-red focus:z-10 sm:text-sm"
                placeholder="+212 6XX-XXXXXX"
              />
            </div>
          </div>

          <!-- Mot de passe -->
          <div>
            <label for="password" class="block text-sm font-medium text-menara-dark">
              Mot de passe
            </label>
            <div class="mt-1">
              <input
                id="password"
                name="password"
                type="password"
                required
                v-model="form.password"
                class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-menara-red focus:border-menara-red focus:z-10 sm:text-sm"
                placeholder="Mot de passe"
              />
            </div>
          </div>

          <!-- Confirmation mot de passe -->
          <div>
            <label for="confirmPassword" class="block text-sm font-medium text-menara-dark">
              Confirmer le mot de passe
            </label>
            <div class="mt-1">
              <input
                id="confirmPassword"
                name="confirmPassword"
                type="password"
                required
                v-model="form.confirmPassword"
                class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg focus:outline-none focus:ring-menara-red focus:border-menara-red focus:z-10 sm:text-sm"
                placeholder="Confirmez votre mot de passe"
              />
            </div>
          </div>

          <div>
            <button
              type="submit"
              class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-menara-red hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-menara-red transition-colors duration-200"
            >
              <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                <svg class="h-5 w-5 text-red-300 group-hover:text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                </svg>
              </span>
              Créer mon compte
            </button>
          </div>

          <div class="text-center">
            <p class="text-sm text-gray-600">
              Vous avez déjà un compte ? 
              <router-link to="/login" class="font-medium text-menara-red hover:text-red-700 transition-colors">
                Se connecter
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
  name: 'RegisterView',
  setup() {
    const router = useRouter()
    
    const form = ref({
      nom: '',
      email: '',
      telephone: '',
      role: '',
      password: '',
      confirmPassword: ''
    })

    const handleRegister = () => {
      // Validation basique
      if (form.value.password !== form.value.confirmPassword) {
        alert('Les mots de passe ne correspondent pas')
        return
      }

      // Ici vous pouvez ajouter la logique d'inscription
      console.log('Données d\'inscription:', form.value)
      
      // Simulation d'inscription réussie
      alert('Compte créé avec succès!')
      
      // Redirection vers la page de connexion
      router.push('/login')
    }

    return {
      form,
      handleRegister
    }
  }
}
</script>