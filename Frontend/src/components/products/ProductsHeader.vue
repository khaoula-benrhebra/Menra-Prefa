<template>
  <section class="relative bg-gradient-to-br from-menara-blue to-menara-dark text-white py-16 overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-20" 
         style="background-image: url('https://images.unsplash.com/photo-1504307651254-35680f356dfd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2076&q=80')">
    </div>
    
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black/20"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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

      <!-- Catégories principales -->
      <div class="mt-12">
        <h3 class="text-xl font-semibold mb-6 text-center">Catégories principales</h3>
        
        <!-- Skeleton loader pour les catégories -->
        <div v-if="isLoadingCategories" class="flex flex-wrap justify-center gap-3">
          <div 
            v-for="i in 6" 
            :key="i"
            class="px-4 py-2 bg-white/10 rounded-full animate-pulse"
          >
            <div class="h-4 bg-white/20 rounded w-16"></div>
          </div>
        </div>

        <!-- Catégories dynamiques -->
        <div v-else class="flex flex-wrap justify-center gap-3">
          <span 
            v-for="category in displayedCategories" 
            :key="category.id"
            class="px-4 py-2 bg-menara-red/20 border border-menara-red/30 rounded-full text-sm font-medium backdrop-blur-sm hover:bg-menara-red/30 transition-colors cursor-pointer"
            @click="$emit('category-selected', category.name)"
          >
            {{ category.name }}
          </span>
          
         
          <span 
            v-if="categories.length > maxDisplayedCategories"
            class="px-4 py-2 bg-white/10 border border-white/20 rounded-full text-sm font-medium backdrop-blur-sm"
          >
            +{{ categories.length - maxDisplayedCategories }} autres
          </span>
        </div>

        <!-- Message si aucune catégorie -->
        <div v-if="!isLoadingCategories && categories.length === 0" class="text-center">
          <p class="text-white/70 text-sm">
            Aucune catégorie disponible pour le moment
          </p>
        </div>
      </div>
    </div>

    
  </section>
</template>

<script>
import { ref, computed } from 'vue'

export default {
  name: 'ProductsHeader',
  props: {
    categories: {
      type: Array,
      default: () => []
    },
    isLoadingCategories: {
      type: Boolean,
      default: false
    }
  },
  emits: ['category-selected'],
  setup(props) {
    const maxDisplayedCategories = ref(6)

    // Catégories à afficher (limitées)
    const displayedCategories = computed(() => {
      return props.categories.slice(0, maxDisplayedCategories.value)
    })

    return {
      maxDisplayedCategories,
      displayedCategories
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

/* Animation de chargement */
@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Responsive design */
@media (max-width: 768px) {
  .bg-white\/10.backdrop-blur-sm {
    padding: 1rem;
  }
}
</style>