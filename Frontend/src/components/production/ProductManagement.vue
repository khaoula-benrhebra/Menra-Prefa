<template>
  <div class="space-y-6">
    <!-- Action Bar -->
    <div class="flex justify-between items-center">
      <div class="flex items-center space-x-4">
        <div class="relative">
          <input
            type="text"
            placeholder="Rechercher un produit..."
            v-model="searchQuery"
            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-menara-red focus:border-transparent"
          />
          <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </div>
        <select
          v-model="selectedCategory"
          class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-menara-red focus:border-transparent"
        >
          <option value="">Toutes les catégories</option>
          <option v-for="category in categories" :key="category.id" :value="category.id">
            {{ category.name }}
          </option>
        </select>
      </div>
      <button
        @click="openAddModal"
        class="bg-menara-red text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center space-x-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        <span>Nouveau Produit</span>
      </button>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Produit
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Catégorie
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Prix
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Stock Actuel
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Stock Min
            </th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="product in filteredProducts" :key="product.id" class="hover:bg-gray-50">
            <td class="px-6 py-4">
              <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                  <div v-if="product.image" class="h-10 w-10 rounded-lg overflow-hidden">
                    <img :src="product.image" :alt="product.name" class="h-full w-full object-cover">
                  </div>
                  <div v-else class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                  </div>
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900">{{ product.name }}</div>
                  <div class="text-sm text-gray-500">{{ product.description }}</div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                {{ product.category_name }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ formatPrice(product.price) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span :class="[
                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                product.stock_actuel > product.stock_min ? 'bg-green-100 text-green-800' : 
                product.stock_actuel === product.stock_min ? 'bg-yellow-100 text-yellow-800' : 
                'bg-red-100 text-red-800'
              ]">
                {{ product.stock_actuel }} unités
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ product.stock_min }} unités
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <div class="flex justify-end space-x-2">
                <button
                  @click="openEditModal(product)"
                  class="text-indigo-600 hover:text-indigo-900 p-1 rounded"
                  title="Modifier"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                  </svg>
                </button>
                <button
                  @click="openDeleteModal(product)"
                  class="text-red-600 hover:text-red-900 p-1 rounded"
                  title="Supprimer"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Add/Edit Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">
            {{ isEditing ? 'Modifier le produit' : 'Nouveau produit' }}
          </h3>
          <form @submit.prevent="submitForm" class="space-y-6">
            <!-- Image Upload Section -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Image du produit</label>
              <div class="flex items-center space-x-4">
                <!-- Image Preview -->
                <div class="flex-shrink-0">
                  <div v-if="imagePreview" class="h-20 w-20 rounded-lg overflow-hidden border border-gray-300">
                    <img :src="imagePreview" alt="Aperçu" class="h-full w-full object-cover">
                  </div>
                  <div v-else class="h-20 w-20 rounded-lg bg-gray-100 flex items-center justify-center border border-gray-300">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                  </div>
                </div>
                
                <!-- Upload Button -->
                <div class="flex-1">
                  <input
                    type="file"
                    ref="fileInput"
                    @change="handleImageUpload"
                    accept="image/*"
                    class="hidden"
                  />
                  <button
                    type="button"
                    @click="$refs.fileInput.click()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-menara-red"
                  >
                    {{ imagePreview ? 'Changer l\'image' : 'Choisir une image' }}
                  </button>
                  <p class="text-xs text-gray-500 mt-1">
                    Formats acceptés: JPG, PNG, GIF (max 5MB)
                  </p>
                </div>
              </div>
            </div>

            <!-- Form Fields in Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Left Column -->
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                  <input
                    type="text"
                    v-model="formData.name"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-menara-red"
                  />
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie *</label>
                  <select
                    v-model="formData.category_id"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-menara-red"
                  >
                    <option value="">Sélectionner une catégorie</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">
                      {{ category.name }}
                    </option>
                  </select>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Prix (MAD) *</label>
                  <input
                    type="number"
                    step="0.01"
                    v-model="formData.price"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-menara-red"
                  />
                </div>
              </div>

              <!-- Right Column -->
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Stock Actuel *</label>
                  <input
                    type="number"
                    v-model="formData.stock_actuel"
                    required
                    min="0"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-menara-red"
                  />
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Stock Minimum *</label>
                  <input
                    type="number"
                    v-model="formData.stock_min"
                    required
                    min="0"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-menara-red"
                  />
                  <p class="text-xs text-gray-500 mt-1">
                    Seuil d'alerte pour le réapprovisionnement
                  </p>
                </div>
              </div>
            </div>

            <!-- Description Full Width -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
              <textarea
                v-model="formData.description"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-menara-red"
                placeholder="Description détaillée du produit..."
              ></textarea>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
              <button
                type="button"
                @click="closeModal"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors"
                :disabled="isSubmitting"
              >
                Annuler
              </button>
              <button
                type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-menara-red rounded-md hover:bg-red-700 transition-colors disabled:opacity-50"
                :disabled="isSubmitting"
              >
                {{ isSubmitting ? 'En cours...' : (isEditing ? 'Modifier' : 'Créer') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900 mb-2">Supprimer le produit</h3>
          <p class="text-sm text-gray-500 mb-4">
            Êtes-vous sûr de vouloir supprimer "{{ productToDelete?.name }}" ? Cette action est irréversible.
          </p>
          <div class="flex justify-center space-x-3">
            <button
              @click="closeDeleteModal"
              class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
              :disabled="isDeleting"
            >
              Annuler
            </button>
            <button
              @click="confirmDelete"
              class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 disabled:opacity-50"
              :disabled="isDeleting"
            >
              {{ isDeleting ? 'Suppression...' : 'Supprimer' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue'

export default {
  name: 'ProductsSection',
  props: {
    products: {
      type: Array,
      default: () => []
    },
    categories: {
      type: Array,
      default: () => []
    }
  },
  emits: ['add-product', 'edit-product', 'delete-product'],
  setup(props, { emit }) {
    const searchQuery = ref('')
    const selectedCategory = ref('')
    const showModal = ref(false)
    const showDeleteModal = ref(false)
    const isEditing = ref(false)
    const isSubmitting = ref(false)
    const isDeleting = ref(false)
    const productToDelete = ref(null)
    const imagePreview = ref(null)
    const selectedImageFile = ref(null)
    
    const formData = ref({
      id: null,
      name: '',
      category_id: '',
      description: '',
      price: 0,
      stock_actuel: 0,
      stock_min: 1,
      image: null
    })

    const filteredProducts = computed(() => {
      let filtered = props.products

      if (searchQuery.value) {
        filtered = filtered.filter(product =>
          product.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
          product.description.toLowerCase().includes(searchQuery.value.toLowerCase())
        )
      }

      if (selectedCategory.value) {
        filtered = filtered.filter(product => product.category_id === selectedCategory.value)
      }

      return filtered
    })

    const formatPrice = (price) => {
      return new Intl.NumberFormat('fr-MA', {
        style: 'currency',
        currency: 'MAD'
      }).format(price)
    }

    const handleImageUpload = (event) => {
      const file = event.target.files[0]
      if (file) {
        // Vérification la taille du fichier (5MB max)
        if (file.size > 5 * 1024 * 1024) {
          alert('La taille de l\'image ne doit pas dépasser 5MB')
          return
        }

        // Vérification le type de fichier
        if (!file.type.startsWith('image/')) {
          alert('Veuillez sélectionner un fichier image valide')
          return
        }

        selectedImageFile.value = file
        
        // Créer l'aperçu
        const reader = new FileReader()
        reader.onload = (e) => {
          imagePreview.value = e.target.result
        }
        reader.readAsDataURL(file)
      }
    }

    const resetImageUpload = () => {
      imagePreview.value = null
      selectedImageFile.value = null
    }

    const openAddModal = () => {
      isEditing.value = false
      formData.value = {
        id: null,
        name: '',
        category_id: '',
        description: '',
        price: 0,
        stock_actuel: 0,
        stock_min: 1,
        image: null
      }
      resetImageUpload()
      showModal.value = true
    }

    const openEditModal = (product) => {
      isEditing.value = true
      formData.value = { ...product }
      
      // Charger l'image existante si elle existe
      if (product.image) {
        imagePreview.value = product.image
      } else {
        resetImageUpload()
      }
      
      showModal.value = true
    }

    const closeModal = () => {
      showModal.value = false
      formData.value = {
        id: null,
        name: '',
        category_id: '',
        description: '',
        price: 0,
        stock_actuel: 0,
        stock_min: 1,
        image: null
      }
      resetImageUpload()
      isSubmitting.value = false
    }

    const openDeleteModal = (product) => {
      productToDelete.value = product
      showDeleteModal.value = true
    }

    const closeDeleteModal = () => {
      showDeleteModal.value = false
      productToDelete.value = null
      isDeleting.value = false
    }

    const submitForm = async () => {
      try {
        isSubmitting.value = true
        
        // Préparer les données du formulaire
        const productData = {
          ...formData.value,
          imageFile: selectedImageFile.value
        }

        if (isEditing.value) {
          await emit('edit-product', productData)
        } else {
          await emit('add-product', productData)
        }
        
        closeModal()
      } catch (error) {
        console.error('Erreur lors de la soumission:', error)
      } finally {
        isSubmitting.value = false
      }
    }

    const confirmDelete = async () => {
      if (!productToDelete.value) return
      
      try {
        isDeleting.value = true
        await emit('delete-product', productToDelete.value.id)
        closeDeleteModal()
      } catch (error) {
        console.error('Erreur lors de la suppression:', error)
      } finally {
        isDeleting.value = false
      }
    }

    return {
      searchQuery,
      selectedCategory,
      showModal,
      showDeleteModal,
      isEditing,
      isSubmitting,
      isDeleting,
      productToDelete,
      formData,
      imagePreview,
      filteredProducts,
      formatPrice,
      handleImageUpload,
      openAddModal,
      openEditModal,
      closeModal,
      openDeleteModal,
      closeDeleteModal,
      submitForm,
      confirmDelete
    }
  }
}
</script>

<style scoped>
.bg-menara-red {
  background-color: rgb(192, 15, 26);
}

.focus\:ring-menara-red:focus {
  --tw-ring-color: rgb(192, 15, 26);
}
</style>