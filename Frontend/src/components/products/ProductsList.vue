<template>
  <section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- En-tête de la liste -->
      <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-bold text-menara-dark">
          {{ products.length }} produit{{ products.length > 1 ? 's' : '' }} trouvé{{ products.length > 1 ? 's' : '' }}
        </h2>
        
        <!-- Options d'affichage -->
        <div class="flex items-center space-x-4">
          <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-600">Affichage:</span>
            <button
              @click="viewMode = 'grid'"
              :class="[
                'p-2 rounded-lg transition-colors',
                viewMode === 'grid' ? 'bg-menara-red text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
              ]"
            >
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
              </svg>
            </button>
            <button
              @click="viewMode = 'list'"
              :class="[
                'p-2 rounded-lg transition-colors',
                viewMode === 'list' ? 'bg-menara-red text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
              ]"
            >
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Grille des produits -->
      <div v-if="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div
          v-for="product in products"
          :key="product.id"
          class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 group"
        >
          <!-- Image du produit -->
          <div class="relative h-32 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
            <img 
              v-if="product.image" 
              :src="product.image" 
              :alt="product.name"
              class="w-full h-full object-cover"
            />
            <div v-else class="absolute inset-0 flex items-center justify-center">
              <div class="text-6xl text-gray-400">
                <!-- Icône basée sur la catégorie -->
                <svg v-if="product.category_name?.toLowerCase().includes('pavage')" class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" />
                </svg>
                <svg v-else-if="product.category_name?.toLowerCase().includes('structure')" class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                <svg v-else class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v8H4V6z" clip-rule="evenodd" />
                </svg>
              </div>
            </div>
            <!-- Badge catégorie -->
            <div class="absolute top-3 left-3">
              <span class="px-2 py-1 bg-menara-red text-white rounded-full text-xs font-medium">
                {{ product.category_name }}
              </span>
            </div>
          </div>

          <!-- Contenu de la carte -->
          <div class="p-6">
            <h3 class="text-lg font-semibold text-menara-dark mb-2 group-hover:text-menara-red transition-colors">
              {{ product.name }}
            </h3>
            <p class="text-gray-600 text-sm mb-4 line-clamp-2">
              {{ product.description }}
            </p>

            <!-- Prix -->
            <div class="flex justify-between items-center mb-4">
              <div>
                <span class="text-2xl font-bold text-menara-red">{{ formatPrice(product.price) }}€</span>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex space-x-2">
              <button class="flex-1 py-2 px-4 rounded-lg font-medium transition-colors bg-menara-red hover:bg-red-700 text-white">
                Commander
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Vue liste -->
      <div v-else class="space-y-4">
        <div
          v-for="product in products"
          :key="product.id"
          class="bg-white rounded-xl shadow-md border border-gray-200 p-6 hover:shadow-lg transition-shadow"
        >
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-6">
              <!-- Image produit -->
              <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center overflow-hidden">
                <img 
                  v-if="product.image" 
                  :src="product.image" 
                  :alt="product.name"
                  class="w-full h-full object-cover"
                />
                <svg v-else class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v8H4V6z" clip-rule="evenodd" />
                </svg>
              </div>

              <!-- Informations produit -->
              <div class="flex-1">
                <div class="flex items-center space-x-3 mb-2">
                  <h3 class="text-lg font-semibold text-menara-dark">{{ product.name }}</h3>
                  <span class="px-2 py-1 bg-menara-red text-white rounded-full text-xs font-medium">
                    {{ product.category_name }}
                  </span>
                </div>
                <p class="text-gray-600 mb-2">{{ product.description }}</p>
              </div>
            </div>

            <!-- Prix et actions -->
            <div class="text-right">
              <div class="mb-2">
                <span class="text-2xl font-bold text-menara-red">{{ formatPrice(product.price) }}€</span>
              </div>
              <div class="flex space-x-2">
                <button class="py-2 px-4 rounded-lg font-medium transition-colors bg-menara-red hover:bg-red-700 text-white">
                  Commander
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Message si aucun produit -->
      <div v-if="products.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m0 0v5a2 2 0 002 2h8a2 2 0 002-2v-5m-10 0V9a2 2 0 012-2h4a2 2 0 012 2v4m-6 0a2 2 0 002 2h2a2 2 0 002-2m-6 0h6" />
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun produit trouvé</h3>
        <p class="text-gray-500">Essayez de modifier vos critères de recherche ou de navigation.</p>
      </div>
    </div>
  </section>
</template>

<script>
import { ref } from 'vue'

export default {
  name: 'ProductsList',
  props: {
    products: {
      type: Array,
      default: () => []
    }
  },
  setup() {
    const viewMode = ref('grid')

    const formatPrice = (price) => {
      return new Intl.NumberFormat('fr-FR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
      }).format(price)
    }

    return {
      viewMode,
      formatPrice
    }
  }
}
</script>

<style scoped>
.bg-menara-red {
  background-color: #dc2626;
}

.text-menara-red {
  color: #dc2626;
}

.text-menara-dark {
  color: #1e3a8a;
}

.hover\:bg-red-700:hover {
  background-color: #b91c1c;
}

.hover\:text-menara-red:hover {
  color: #dc2626;
}

.hover\:border-menara-red:hover {
  border-color: #dc2626;
}

.group:hover .group-hover\:text-menara-red {
  color: #dc2626;
}

/* Limitation du texte */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Animations */
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 300ms;
}

.transition-colors {
  transition-property: color, background-color, border-color;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 200ms;
}

.transition-shadow {
  transition-property: box-shadow;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 200ms;
}

/* Responsive design */
@media (max-width: 768px) {
  .grid-cols-1.md\:grid-cols-2.lg\:grid-cols-3.xl\:grid-cols-4 {
    grid-template-columns: repeat(1, minmax(0, 1fr));
  }
  
  .flex.items-center.justify-between {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }
  
  .text-right {
    text-align: left;
    width: 100%;
  }
  
  .flex.space-x-2 {
    width: 100%;
  }
  
  .flex-1 {
    flex: 1;
  }
}
</style>