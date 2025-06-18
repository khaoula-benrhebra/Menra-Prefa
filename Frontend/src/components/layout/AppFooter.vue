<template>
  <footer class="bg-menara-dark text-white py-12 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Logo et description -->
        <div>
          <div class="flex items-center mb-4">
            <router-link to="/" class="flex items-center">
              <img 
                src="/assets/images/logo.png" 
                alt="Menara Prefa Logo" 
                class="h-8 w-auto filter brightness-0 invert"
              />
              <span class="ml-3 text-xl font-bold">Ménara Préfa</span>
            </router-link>
          </div>
          <p class="text-gray-300 mb-4">
            Leader dans le secteur du BTP au Maroc, spécialisé dans les produits 
            préfabriqués en béton et solutions digitales.
          </p>
          <div class="text-sm text-gray-400">
            © 2024 Ménara Préfa. Tous droits réservés.
          </div>
        </div>

        <!-- Liens utiles -->
        <div>
          <h3 class="text-lg font-semibold mb-4">Liens Utiles</h3>
          <ul class="space-y-2">
            <li>
              <router-link to="/" class="text-gray-300 hover:text-menara-red transition-colors">
                Accueil
              </router-link>
            </li>
            <li>
              <a href="#" class="text-gray-300 hover:text-menara-red transition-colors">
                Nos Produits
              </a>
            </li>
            <li>
              <router-link to="/register" class="text-gray-300 hover:text-menara-red transition-colors">
                S'inscrire
              </router-link>
            </li>
            <li>
              <router-link to="/login" class="text-gray-300 hover:text-menara-red transition-colors">
                Se connecter
              </router-link>
            </li>
            <li>
              <a href="#" class="text-gray-300 hover:text-menara-red transition-colors">
                Support Technique
              </a>
            </li>
          </ul>
        </div>

        <!-- Informations de contact -->
        <div>
          <h3 class="text-lg font-semibold mb-4">Contact</h3>
          <div class="space-y-3">
            <div class="flex items-center">
              <svg class="h-5 w-5 text-menara-red mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
              </svg>
              <span class="text-gray-300">Targa, Marrakech, Maroc</span>
            </div>
            <div class="flex items-center">
              <svg class="h-5 w-5 text-menara-red mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
              </svg>
              <span class="text-gray-300">+212 5XX-XXXXXX</span>
            </div>
            <div class="flex items-center">
              <svg class="h-5 w-5 text-menara-red mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
              </svg>
              <span class="text-gray-300">contact@menaraprefa.ma</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'AppFooter'
}
</script>

<style scoped>
/* Définition des couleurs personnalisées */
.bg-menara-dark {
  background-color: #1f2937;
}

.text-menara-red {
  color: rgb(192, 15, 26);
}

.hover\:text-menara-red:hover {
  color: rgb(192, 15, 26);
}
</style>