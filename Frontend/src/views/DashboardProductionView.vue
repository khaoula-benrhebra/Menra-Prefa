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
        <!-- Categories Section -->
        <CategoriesSection 
          v-if="activeSection === 'categories'"
          :categories="categories"
          @add-category="handleAddCategory"
          @edit-category="handleEditCategory"
          @delete-category="handleDeleteCategory"
        />
        
        <!-- Products Section -->
        <ProductsSection 
          v-if="activeSection === 'products'"
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
    const categories = ref([
      { id: 1, name: 'Poutrelles', description: 'Poutrelles en béton précontraint', products_count: 12 },
      { id: 2, name: 'Dalles', description: 'Dalles alvéolaires préfabriquées', products_count: 8 },
      { id: 3, name: 'Poteaux', description: 'Poteaux en béton armé', products_count: 6 }
    ])
    
    const products = ref([
      { 
        id: 1, 
        name: 'Poutrelle 12m', 
        category_id: 1, 
        category_name: 'Poutrelles',
        description: 'Poutrelle béton précontraint 12 mètres',
        price: 250.00,
        stock: 45
      },
      { 
        id: 2, 
        name: 'Dalle alvéolaire 6m', 
        category_id: 2, 
        category_name: 'Dalles',
        description: 'Dalle alvéolaire préfabriquée 6 mètres',
        price: 180.00,
        stock: 32
      }
    ])

    const handleSectionChange = (section) => {
      activeSection.value = section
    }

    const handleAddCategory = (category) => {
      // Logique d'ajout de catégorie
      console.log('Ajouter catégorie:', category)
    }

    const handleEditCategory = (category) => {
      // Logique de modification de catégorie
      console.log('Modifier catégorie:', category)
    }

    const handleDeleteCategory = (categoryId) => {
      // Logique de suppression de catégorie
      console.log('Supprimer catégorie:', categoryId)
    }

    const handleAddProduct = (product) => {
      // Logique d'ajout de produit
      console.log('Ajouter produit:', product)
    }

    const handleEditProduct = (product) => {
      // Logique de modification de produit
      console.log('Modifier produit:', product)
    }

    const handleDeleteProduct = (productId) => {
      // Logique de suppression de produit
      console.log('Supprimer produit:', productId)
    }

    return {
      activeSection,
      categories,
      products,
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