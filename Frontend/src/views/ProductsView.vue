<template>
  <main class="flex-1">
    <ProductsHeader :categories="categories" />
    <ProductsSearch 
      @search="handleSearch" 
      @filter="handleFilter"
      :categories="categories" 
    />
    <ProductsList :products="filteredProducts" />
  </main>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import { useCategories } from '@/composables/useCategories'
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
    const stockFilter = ref(null)
    
    // Utilisation du composable des catégories
    const { categories, loadCategories, isLoading } = useCategories()
    
    // Données des produits (à terme, ces données viendront aussi d'une API)
    const products = ref([
      {
        id: 1,
        name: 'Pavés Autobloquants',
        category: 'Pavage',
        description: 'Pavés en béton haute résistance pour aménagements extérieurs',
        price: 45,
        unit: 'm²',
        image: '/assets/images/paves.jpg',
        specifications: ['Épaisseur: 6-8cm', 'Résistance: 35 MPa', 'Formats variés'],
        inStock: true,
        stockQuantity: 2500
      },
      {
        id: 2,
        name: 'Dalles de Terrasse',
        category: 'Dallage',
        description: 'Dalles décorative pour terrasses et espaces extérieurs',
        price: 38,
        unit: 'm²',
        image: '/assets/images/dalles.jpg',
        specifications: ['Épaisseur: 4cm', 'Finition lisse', 'Anti-dérapant'],
        inStock: true,
        stockQuantity: 1800
      },
      {
        id: 3,
        name: 'Bordures de Trottoir',
        category: 'Bordures',
        description: 'Bordures préfabriquées pour délimitation de voiries',
        price: 25,
        unit: 'ml',
        image: '/assets/images/bordures.jpg',
        specifications: ['Longueur: 1m', 'Hauteur: 20cm', 'Classe T'],
        inStock: true,
        stockQuantity: 5000
      },
      {
        id: 4,
        name: 'Agglos Creux',
        category: 'Maçonnerie',
        description: 'Blocs creux en béton pour construction',
        price: 2.5,
        unit: 'pièce',
        image: '/assets/images/agglos.jpg',
        specifications: ['20x20x40cm', 'Résistance: 15 MPa', 'Isolation thermique'],
        inStock: true,
        stockQuantity: 15000
      },
      {
        id: 5,
        name: 'Hourdis Béton',
        category: 'Planchers',
        description: 'Éléments de plancher préfabriqués',
        price: 12,
        unit: 'm²',
        image: '/assets/images/hourdis.jpg',
        specifications: ['Épaisseur: 16cm', 'Portée: 4-6m', 'Pré-contrainte'],
        inStock: false,
        stockQuantity: 0
      },
      {
        id: 6,
        name: 'Poutres Précontraintes',
        category: 'Structure',
        description: 'Poutres en béton précontraint pour gros œuvre',
        price: 85,
        unit: 'ml',
        image: '/assets/images/poutres.jpg',
        specifications: ['Section: 20x40cm', 'Longueur: 3-12m', 'Haute résistance'],
        inStock: true,
        stockQuantity: 200
      },
      {
        id: 7,
        name: 'Tuyaux Assainissement',
        category: 'Canalisation',
        description: 'Tuyaux en béton pour réseaux d assainissement',
        price: 35,
        unit: 'ml',
        image: '/assets/images/tuyaux.jpg',
        specifications: ['Diamètre: Ø300-Ø1200', 'Classe: 135A', 'Étanchéité'],
        inStock: true,
        stockQuantity: 800
      },
      {
        id: 8,
        name: 'Regards de Visite',
        category: 'Canalisation',
        description: 'Regards préfabriqués pour réseaux enterrés',
        price: 450,
        unit: 'pièce',
        image: '/assets/images/regards.jpg',
        specifications: ['Ø1000mm', 'Hauteur variable', 'Avec couvercle'],
        inStock: true,
        stockQuantity: 150
      }
    ])

    // Recherche et filtrage
    const filteredProducts = computed(() => {
      let result = products.value

      // Filtrage par recherche textuelle
      if (searchQuery.value) {
        result = result.filter(product => 
          product.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
          product.category.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
          product.description.toLowerCase().includes(searchQuery.value.toLowerCase())
        )
      }

      // Filtrage par catégorie
      if (activeFilter.value && activeFilter.value !== 'Tous') {
        result = result.filter(product => 
          product.category.toLowerCase() === activeFilter.value.toLowerCase()
        )
      }

      // Filtrage par stock
      if (stockFilter.value !== null) {
        result = result.filter(product => product.inStock === stockFilter.value)
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
      } else if (filter.type === 'stock') {
        stockFilter.value = filter.value
      } else if (filter.type === 'clear') {
        activeFilter.value = ''
        searchQuery.value = ''
        stockFilter.value = null
      }
    }

    // Chargement des catégories au montage du composant
    onMounted(async () => {
      try {
        await loadCategories()
      } catch (error) {
        console.error('Erreur lors du chargement des catégories:', error)
      }
    })

    return {
      categories,
      products,
      filteredProducts,
      isLoading,
      handleSearch,
      handleFilter
    }
  }
}
</script>