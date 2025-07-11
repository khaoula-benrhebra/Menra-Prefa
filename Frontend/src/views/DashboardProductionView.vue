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
        
        <!-- Success Message -->
        <div v-if="successMessage" class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
          <div class="text-green-800">{{ successMessage }}</div>
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
          :raw-materials="rawMaterials"
          @add-product="handleAddProduct"
          @edit-product="handleEditProduct"
          @delete-product="handleDeleteProduct"
        />

        <!-- Raw Materials Section -->
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
import { useRawMaterials } from '@/composables/useRawMaterials'
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
    ProductsSection,
    RawMaterialsSection,
  },
  setup() {
    const activeSection = ref('categories')
    const error = ref(null)
    const successMessage = ref(null)
    
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

    const {
      rawMaterials,
      isLoading: rawMaterialsLoading,
      loadRawMaterials,
      createRawMaterial,
      updateRawMaterial,
      deleteRawMaterial
    } = useRawMaterials()

    // État de chargement global
    const isLoading = computed(() => 
      categoriesLoading.value || productsLoading.value || rawMaterialsLoading.value
    )

    // Fonction pour afficher un message de succès temporaire
    const showSuccessMessage = (message) => {
      successMessage.value = message
      setTimeout(() => {
        successMessage.value = null
      }, 3000)
    }

    // Charger les données au montage du composant
    onMounted(async () => {
      try {
        // Charger les matières premières en premier
        await loadRawMaterials()
        // Charger les catégories
        await loadCategories()
        // Charger les produits
        await loadProducts()
        
        // Debug: Vérifier si les matières premières sont chargées
        console.log('Raw materials loaded:', rawMaterials.value)
      } catch (err) {
        error.value = err.message
        console.error('Erreur lors du chargement des données:', err)
      }
    })

    const handleSectionChange = (section) => {
      activeSection.value = section
      error.value = null
      successMessage.value = null
    }

    // Gestionnaires pour les catégories
    const handleAddCategory = async (categoryData) => {
      try {
        error.value = null
        await createCategory(categoryData)
        showSuccessMessage('Catégorie créée avec succès')
      } catch (err) {
        error.value = err.message
        console.error('Erreur lors de la création:', err)
      }
    }

    const handleEditCategory = async (categoryData) => {
      try {
        error.value = null
        await updateCategory(categoryData)
        showSuccessMessage('Catégorie modifiée avec succès')
      } catch (err) {
        error.value = err.message
        console.error('Erreur lors de la modification:', err)
      }
    }

    const handleDeleteCategory = async (categoryId) => {
      try {
        error.value = null
        await deleteCategory(categoryId)
        showSuccessMessage('Catégorie supprimée avec succès')
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
        showSuccessMessage('Produit créé avec succès')
      } catch (err) {
        error.value = err.message
        console.error('Erreur lors de la création du produit:', err)
      }
    }

    const handleEditProduct = async (productData) => {
      try {
        error.value = null
        await updateProduct(productData)
        showSuccessMessage('Produit modifié avec succès')
      } catch (err) {
        error.value = err.message
        console.error('Erreur lors de la modification du produit:', err)
      }
    }

    const handleDeleteProduct = async (productId) => {
      try {
        error.value = null
        await deleteProduct(productId)
        showSuccessMessage('Produit supprimé avec succès')
      } catch (err) {
        error.value = err.message
        console.error('Erreur lors de la suppression du produit:', err)
      }
    }

    // Gestionnaires pour les matières premières
    const handleAddRawMaterial = async (rawMaterialData) => {
      try {
        error.value = null
        await createRawMaterial(rawMaterialData)
        showSuccessMessage('Matière première créée avec succès')
      } catch (err) {
        error.value = err.message
        console.error('Erreur lors de la création de la matière première:', err)
      }
    }

    const handleEditRawMaterial = async (rawMaterialData) => {
      try {
        error.value = null
        await updateRawMaterial(rawMaterialData)
        showSuccessMessage('Matière première modifiée avec succès')
      } catch (err) {
        error.value = err.message
        console.error('Erreur lors de la modification de la matière première:', err)
      }
    }

    const handleDeleteRawMaterial = async (rawMaterialId) => {
      try {
        error.value = null
        await deleteRawMaterial(rawMaterialId)
        showSuccessMessage('Matière première supprimée avec succès')
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
      successMessage,
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