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

        <RawMaterialsSection 
          v-else-if="activeSection === 'raw-materials'"
          :raw-materials="rawMaterials"
          @add-raw-material="handleAddRawMaterial"
          @edit-raw-material="handleEditRawMaterial"
          @delete-raw-material="handleDeleteRawMaterial"
        />
      </main>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue'
import { useCategories } from '@/composables/useCategories'
import { useProducts } from '@/composables/useProducts'
import ProductionSidebar from '@/components/production/ProdSidebar.vue'
import ProductionHeader from '@/components/production/ProdHeader.vue'
import CategoriesSection from '@/components/production/CategoryManagement.vue'
import ProductsSection from '@/components/production/ProductManagement.vue'
import RawMaterialsSection from '@/components/production/RawMaterialManagement.vue'


export default {
  name: 'ProductionDashboardView',
  components: {
    ProductionSidebar,
    ProductionHeader,
    CategoriesSection,
    ProductsSection ,
    RawMaterialsSection,
  },
  setup() {
    const activeSection = ref('categories')
    const error = ref(null)
    const rawMaterials = ref([])
    
    // Utiliser les composables
    const { 
      categories, 
      isLoading: categoriesLoading, 
      loadCategories, 
      createCategory, 
      updateCategory, 
      deleteCategory 
    } = useCategories()
    
    const {
      products,
      isLoading: productsLoading,
      loadProducts,
      createProduct,
      updateProduct,
      deleteProduct
    } = useProducts()

    // État de chargement global
    const isLoading = computed(() => categoriesLoading.value || productsLoading.value)

    // Charger les données au montage du composant
    onMounted(async () => {
      try {
        // Charger les catégories en premier (nécessaires pour les produits)
        await loadCategories()
        // Charger les produits
        await loadProducts()
      } catch (err) {
        error.value = err.message
      }
    })

    const handleSectionChange = (section) => {
      activeSection.value = section
      error.value = null
    }

    // Gestionnaires pour les catégories
    const handleAddCategory = async (categoryData) => {
      try {
        error.value = null
        await createCategory(categoryData)
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
        console.log('Catégorie supprimée avec succès')
      } catch (err) {
        error.value = err.message
        console.error('Erreur lors de la suppression:', err)
      }
    }

    // Gestionnaires pour les produits
    const handleAddProduct = async (productData) => {
      try {
        error.value = null
        await createProduct(productData)
        console.log('Produit créé avec succès')
      } catch (err) {
        error.value = err.message
        console.error('Erreur lors de la création du produit:', err)
      }
    }

    const handleEditProduct = async (productData) => {
      try {
        error.value = null
        await updateProduct(productData)
        console.log('Produit modifié avec succès')
      } catch (err) {
        error.value = err.message
        console.error('Erreur lors de la modification du produit:', err)
      }
    }

    const handleDeleteProduct = async (productId) => {
      try {
        error.value = null
        await deleteProduct(productId)
        console.log('Produit supprimé avec succès')
      } catch (err) {
        error.value = err.message
        console.error('Erreur lors de la suppression du produit:', err)
      }
    }

    // Gestionnaires pour les matières premières
    const handleAddRawMaterial = async (rawMaterialData) => {
      try {
        error.value = null
        // Logique d'ajout à implémenter côté backend
        console.log('Matière première créée:', rawMaterialData)
      } catch (err) {
        error.value = err.message
        console.error('Erreur lors de la création de la matière première:', err)
      }
    }

    const handleEditRawMaterial = async (rawMaterialData) => {
      try {
        error.value = null
        // Logique de modification à implémenter côté backend
        console.log('Matière première modifiée:', rawMaterialData)
      } catch (err) {
        error.value = err.message
        console.error('Erreur lors de la modification de la matière première:', err)
      }
    }

    const handleDeleteRawMaterial = async (rawMaterialId) => {
      try {
        error.value = null
        // Logique de suppression à implémenter côté backend
        console.log('Matière première supprimée:', rawMaterialId)
      } catch (err) {
        error.value = err.message
        console.error('Erreur lors de la suppression de la matière première:', err)
      }
    }

    return {
      activeSection,
      categories,
      products,
      rawMaterials,
      isLoading,
      error,
      handleSectionChange,
      handleAddCategory,
      handleEditCategory,
      handleDeleteCategory,
      handleAddProduct,
      handleEditProduct,
      handleDeleteProduct,
       handleAddRawMaterial,
      handleEditRawMaterial,
      handleDeleteRawMaterial
    }
  }
}
</script>