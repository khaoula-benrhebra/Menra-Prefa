<template>
  <main class="flex-1">
    <ProductsHeader :categories="categories" />
    <ProductsSearch 
      @search="handleSearch" 
      @filter="handleFilter"
      :categories="categories" 
    />
    <!-- Affichage du loader pendant le chargement -->
    <div v-if="isLoadingProducts" class="flex justify-center items-center py-12">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-menara-red"></div>
    </div>
    <!-- Affichage des produits -->
    <ProductsList v-else :products="filteredProducts" />
  </main>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import { useCategories } from '@/composables/useCategories'
import { useProducts } from '@/composables/useProducts'
import ProductsHeader from '../components/products/ProductsHeader.vue'
import ProductsSearch from '../components/products/ProductsSearch.vue'
import ProductsList from '../components/products/ProductsList.vue'

export default {
  name: 'ProductsView',
  components: {
    ProductsHeader,
    ProductsSearch,
    ProductsList
  },
  setup() {
    const searchQuery = ref('')
    const activeFilter = ref('')
    
    // Utilisation des composables
    const { categories, loadCategories, isLoading } = useCategories()
    const { 
      products, 
      isLoading: isLoadingProducts, 
      loadProducts 
    } = useProducts()

    // Recherche et filtrage
    const filteredProducts = computed(() => {
      let result = products.value

      // Filtrage par recherche textuelle
      if (searchQuery.value) {
        result = result.filter(product => 
          product.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
          product.category_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
          product.description.toLowerCase().includes(searchQuery.value.toLowerCase())
        )
      }

      // Filtrage par catégorie
      if (activeFilter.value && activeFilter.value !== 'Tous') {
        result = result.filter(product => 
          product.category_name.toLowerCase() === activeFilter.value.toLowerCase()
        )
      }

      return result
    })

    const handleSearch = (query) => {
      searchQuery.value = query
      // Reset du filtre de catégorie si on fait une recherche textuelle
      if (query && !categories.value.some(cat => cat.name.toLowerCase() === query.toLowerCase())) {
        activeFilter.value = ''
      }
    }

    const handleFilter = (filter) => {
      if (filter.type === 'category') {
        activeFilter.value = filter.value
        searchQuery.value = ''
      } else if (filter.type === 'clear') {
        activeFilter.value = ''
        searchQuery.value = ''
      }
    }

    // Chargement des données au montage du composant
    onMounted(async () => {
      try {
        // Charger les catégories et les produits en parallèle
        await Promise.all([
          loadCategories(),
          loadProducts()
        ])
      } catch (error) {
        console.error('Erreur lors du chargement des données:', error)
      }
    })

    return {
      categories,
      products,
      filteredProducts,
      isLoading,
      isLoadingProducts,
      handleSearch,
      handleFilter
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

.border-menara-red {
  border-color: #dc2626;
}

.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>