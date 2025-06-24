<template>
  <section class="bg-gradient-to-br from-menara-blue to-menara-dark text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Titre principal -->
      <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">
          Nos <span class="text-menara-red">Produits</span>
        </h1>
        <p class="text-xl md:text-2xl opacity-90 max-w-3xl mx-auto">
          Découvrez notre gamme complète de produits préfabriqués en béton, 
          conçus pour répondre à tous vos besoins de construction
        </p>
      </div>

      <!-- Statistiques -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="text-center bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
          <div class="text-3xl md:text-4xl font-bold text-menara-red mb-2">{{ stats.totalProducts }}</div>
          <div class="text-sm md:text-base opacity-90">Produits disponibles</div>
        </div>
        
        <div class="text-center bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
          <div class="text-3xl md:text-4xl font-bold text-menara-red mb-2">{{ stats.categories }}</div>
          <div class="text-sm md:text-base opacity-90">Catégories</div>
        </div>
        
        <div class="text-center bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
          <div class="text-3xl md:text-4xl font-bold text-menara-red mb-2">{{ stats.inStock }}%</div>
          <div class="text-sm md:text-base opacity-90">En stock</div>
        </div>
        
        <div class="text-center bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
          <div class="text-3xl md:text-4xl font-bold text-menara-red mb-2">{{ stats.totalStock }}+</div>
          <div class="text-sm md:text-base opacity-90">Unités en stock</div>
        </div>
      </div>

      <!-- Catégories populaires -->
      <div class="mt-12">
        <h3 class="text-xl font-semibold mb-6 text-center">Catégories principales</h3>
        <div class="flex flex-wrap justify-center gap-3">
          <span 
            v-for="category in popularCategories" 
            :key="category"
            class="px-4 py-2 bg-menara-red/20 border border-menara-red/30 rounded-full text-sm font-medium backdrop-blur-sm hover:bg-menara-red/30 transition-colors cursor-pointer"
          >
            {{ category }}
          </span>
        </div>
      </div>
    </div>

    <!-- Motif décoratif -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute -top-4 -right-4 w-32 h-32 bg-menara-red/10 rounded-full blur-xl"></div>
      <div class="absolute top-1/2 -left-8 w-24 h-24 bg-white/5 rounded-full blur-lg"></div>
      <div class="absolute bottom-8 right-1/4 w-16 h-16 bg-menara-red/20 rounded-full blur-md"></div>
    </div>
  </section>
</template>

<script>
import { ref, computed } from 'vue'

export default {
  name: 'ProductsHeader',
  setup() {
    // Statistiques simulées
    const stats = ref({
      totalProducts: 8,
      categories: 6,
      inStock: 88,
      totalStock: 25000
    })

    const popularCategories = ref([
      'Pavage',
      'Maçonnerie', 
      'Structure',
      'Canalisation',
      'Planchers',
      'Bordures'
    ])

    return {
      stats,
      popularCategories
    }
  }
}
</script>

<style scoped>
/* Classes personnalisées pour les couleurs Menara */
.from-menara-blue {
  --tw-gradient-from: #1e40af;
}

.to-menara-dark {
  --tw-gradient-to: #1e3a8a;
}

.text-menara-red {
  color: #dc2626;
}

.bg-menara-red\/20 {
  background-color: rgba(220, 38, 38, 0.2);
}

.border-menara-red\/30 {
  border-color: rgba(220, 38, 38, 0.3);
}

.hover\:bg-menara-red\/30:hover {
  background-color: rgba(220, 38, 38, 0.3);
}

/* Animation des éléments décoratifs */
@keyframes float {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-10px) rotate(180deg); }
}

.absolute.blur-xl,
.absolute.blur-lg,
.absolute.blur-md {
  animation: float 6s ease-in-out infinite;
}

.absolute.blur-lg {
  animation-delay: -2s;
}

.absolute.blur-md {
  animation-delay: -4s;
}

/* Responsive design */
@media (max-width: 768px) {
  .grid-cols-2.md\:grid-cols-4 {
    gap: 1rem;
  }
  
  .bg-white\/10.backdrop-blur-sm {
    padding: 1rem;
  }
}
</style>