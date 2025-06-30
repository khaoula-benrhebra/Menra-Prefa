<template>
  <div class="min-h-screen bg-gray-50 flex">
    <!-- Sidebar -->
    <ProductionSidebar 
      :active-section="activeSection" 
      @section-changed="handleSectionChange" 
    />
    
    <!-- Main Content -->
    <div class="flex-1 ml-64">
      <!-- Header -->
      <ProductionHeader :current-section="activeSection" />
      
      <!-- Content Area -->
      <main class="p-6">
        <!-- Loading State -->
        <div v-if="isLoading" class="flex justify-center items-center h-64">
          <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-red-600"></div>
        </div>
        
        <!-- Error State -->
        <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
          <div class="text-red-800">{{ error }}</div>
        </div>
        
        <!-- Categories Section -->
        <CategoriesSection 
          v-else-if="activeSection === 'categories'"
          :categories="categories"
          @add-category="handleAddCategory"
          @edit-category="handleEditCategory"
          @delete-category="handleDeleteCategory"
        />
        
        <!-- Products Section -->
        <ProductsSection 
          v-else-if="activeSection === 'products'"
          :products="products"
          :categories="categories"
          @add-product="handleAddProduct"
          @edit-product="handleEditProduct"
          @delete-product="handleDeleteProduct"
        />
      </main>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { useCategories } from '@/composables/useCategories'
import ProductionSidebar from '@/components/production/ProdSidebar.vue'
import ProductionHeader from '@/components/production/ProdHeader.vue'
import CategoriesSection from '@/components/production/CategoryManagement.vue'
import ProductsSection from '@/components/production/ProductManagement.vue'

export default {
  name: 'ProductionDashboardView',
  components: {
    ProductionSidebar,
    ProductionHeader,
    CategoriesSection,
    ProductsSection
  },
  setup() {
    const activeSection = ref('categories')
    const error = ref(null)
    
    // Utiliser le composable des catégories
    const { 
      categories, 
      isLoading, 
      loadCategories, 
      createCategory, 
      updateCategory, 
      deleteCategory 
    } = useCategories()
    
    // Données temporaires pour les produits (à remplacer par un composable similaire)
    const products = ref([])

    // Charger les catégories au montage du composant
    onMounted(async () => {
      try {
        await loadCategories()
      } catch (err) {
        error.value = err.message
      }
    })

    const handleSectionChange = (section) => {
      activeSection.value = section
      error.value = null
    }

    const handleAddCategory = async (categoryData) => {
      try {
        error.value = null
        await createCategory(categoryData)
        // Optionnel: afficher un message de succès
        console.log('Catégorie créée avec succès')
      } catch (err) {
        error.value = err.message
        console.error('Erreur lors de la création:', err)
      }
    }

    const handleEditCategory = async (categoryData) => {
      try {
        error.value = null
        await updateCategory(categoryData)
        // Optionnel: afficher un message de succès
        console.log('Catégorie modifiée avec succès')
      } catch (err) {
        error.value = err.message
        console.error('Erreur lors de la modification:', err)
      }
    }

    const handleDeleteCategory = async (categoryId) => {
      try {
        error.value = null
        await deleteCategory(categoryId)
        // Optionnel: afficher un message de succès
        console.log('Catégorie supprimée avec succès')
      } catch (err) {
        error.value = err.message
        console.error('Erreur lors de la suppression:', err)
      }
    }

    const handleAddProduct = (product) => {
      // Logique d'ajout de produit (à implémenter)
      console.log('Ajouter produit:', product)
    }

    const handleEditProduct = (product) => {
      // Logique de modification de produit (à implémenter)
      console.log('Modifier produit:', product)
    }

    const handleDeleteProduct = (productId) => {
      // Logique de suppression de produit (à implémenter)
      console.log('Supprimer produit:', productId)
    }

    return {
      activeSection,
      categories,
      products,
      isLoading,
      error,
      handleSectionChange,
      handleAddCategory,
      handleEditCategory,
      handleDeleteCategory,
      handleAddProduct,
      handleEditProduct,
      handleDeleteProduct
    }
  }
}
</script>