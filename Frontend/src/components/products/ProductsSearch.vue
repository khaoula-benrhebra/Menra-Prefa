<template>
  <section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="max-w-2xl mx-auto">
        <!-- Barre de recherche -->
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
          <input
            v-model="searchQuery"
            @input="handleSearch"
            type="text"
            placeholder="Rechercher un produit, une catégorie..."
            class="block w-full pl-10 pr-12 py-4 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-menara-red focus:border-menara-red text-lg shadow-sm"
          />
          <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
            <button
              v-if="searchQuery"
              @click="clearSearch"
              class="text-gray-400 hover:text-gray-600 transition-colors"
            >
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Filtres rapides -->
        <div class="mt-6">
          <div class="flex flex-wrap justify-center gap-2">
            <button
              v-for="filter in quickFilters"
              :key="filter"
              @click="applyQuickFilter(filter)"
              :class="[
                'px-4 py-2 rounded-full text-sm font-medium transition-all duration-200',
                activeFilter === filter 
                  ? 'bg-menara-red text-white shadow-md' 
                  : 'bg-white text-gray-700 border border-gray-300 hover:border-menara-red hover:text-menara-red'
              ]"
            >
              {{ filter }}
            </button>
            <button
              @click="clearFilters"
              v-if="activeFilter"
              class="px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors"
            >
              Effacer
            </button>
          </div>
        </div>

        <!-- Indicateur de résultats -->
        <div v-if="searchQuery || activeFilter" class="mt-4 text-center">
          <p class="text-sm text-gray-600">
            <span v-if="searchQuery">
              Recherche pour "<strong class="text-menara-dark">{{ searchQuery }}</strong>"
            </span>
            <span v-if="searchQuery && activeFilter"> dans </span>
            <span v-if="activeFilter">
              <strong class="text-menara-dark">{{ activeFilter }}</strong>
            </span>
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { ref } from 'vue'

export default {
  name: 'ProductsSearch',
  emits: ['search', 'filter'],
  setup(props, { emit }) {
    const searchQuery = ref('')
    const activeFilter = ref('')
    
    const quickFilters = ref([
      'Tous',
      'Pavage',
      'Maçonnerie',
      'Structure',
      'Canalisation',
      'Planchers',
      'Bordures',
      'En stock'
    ])

    const handleSearch = () => {
      emit('search', searchQuery.value)
    }

    const clearSearch = () => {
      searchQuery.value = ''
      emit('search', '')
    }

    const applyQuickFilter = (filter) => {
      if (filter === 'Tous') {
        activeFilter.value = ''
        searchQuery.value = ''
        emit('search', '')
      } else {
        activeFilter.value = filter
        if (filter === 'En stock') {
          searchQuery.value = ''
          emit('filter', { type: 'stock', value: true })
        } else {
          searchQuery.value = filter
          emit('search', filter)
        }
      }
    }

    const clearFilters = () => {
      activeFilter.value = ''
      searchQuery.value = ''
      emit('search', '')
    }

    return {
      searchQuery,
      activeFilter,
      quickFilters,
      handleSearch,
      clearSearch,
      applyQuickFilter,
      clearFilters
    }
  }
}
</script>

<style scoped>
.focus\:ring-menara-red:focus {
  --tw-ring-color: #dc2626;
}

.focus\:border-menara-red:focus {
  --tw-border-opacity: 1;
  border-color: #dc2626;
}

.bg-menara-red {
  background-color: #dc2626;
}

.text-menara-red {
  color: #dc2626;
}

.hover\:border-menara-red:hover {
  --tw-border-opacity: 1;
  border-color: #dc2626;
}

.hover\:text-menara-red:hover {
  --tw-text-opacity: 1;
  color: #dc2626;
}

.text-menara-dark {
  color: #1e3a8a;
}

/* Animation pour les boutons */
.transition-all {
  transition-property: all;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 200ms;
}

/* Effet de focus amélioré */
input:focus {
  box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

/* Responsive design */
@media (max-width: 640px) {
  .flex-wrap {
    justify-content: flex-start;
  }
  
  .px-4 {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
  }
}
</style>