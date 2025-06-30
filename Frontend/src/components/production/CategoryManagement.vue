<template>
  <div class="space-y-6">
    <!-- Action Bar -->
    <div class="flex justify-between items-center">
      <div class="flex items-center space-x-4">
        <div class="relative">
          <input
            type="text"
            placeholder="Rechercher une catégorie..."
            v-model="searchQuery"
            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-menara-red focus:border-transparent"
          />
          <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
        </div>
      </div>
      <button
        @click="openAddModal"
        class="bg-menara-red text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors flex items-center space-x-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        <span>Nouvelle Catégorie</span>
      </button>
    </div>

    <!-- Categories Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Nom
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Description
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Nb. Produits
            </th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-if="filteredCategories.length === 0">
            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
              {{ searchQuery ? 'Aucune catégorie trouvée' : 'Aucune catégorie disponible' }}
            </td>
          </tr>
          <tr v-for="category in filteredCategories" :key="category.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-gray-900">{{ category.name }}</div>
            </td>
            <td class="px-6 py-4">
              <div class="text-sm text-gray-500">{{ category.description }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                {{ category.products_count }} produits
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <div class="flex justify-end space-x-2">
                <button
                  @click="openEditModal(category)"
                  class="text-indigo-600 hover:text-indigo-900 p-1 rounded"
                  title="Modifier"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                  </svg>
                </button>
                <button
                  @click="openDeleteModal(category)"
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
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">
            {{ isEditing ? 'Modifier la catégorie' : 'Nouvelle catégorie' }}
          </h3>
          <form @submit.prevent="submitForm" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
              <input
                type="text"
                v-model="formData.name"
                required
                maxlength="255"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-menara-red"
                :class="{ 'border-red-500': formErrors.name }"
              />
              <p v-if="formErrors.name" class="text-red-500 text-sm mt-1">{{ formErrors.name }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
              <textarea
                v-model="formData.description"
                required
                rows="3"
                maxlength="1000"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-menara-red"
                :class="{ 'border-red-500': formErrors.description }"
              ></textarea>
              <p v-if="formErrors.description" class="text-red-500 text-sm mt-1">{{ formErrors.description }}</p>
            </div>
            <div class="flex justify-end space-x-3 pt-4">
              <button
                type="button"
                @click="closeModal"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200"
                :disabled="isSubmitting"
              >
                Annuler
              </button>
              <button
                type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-menara-red rounded-md hover:bg-red-700 disabled:opacity-50"
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
          <h3 class="text-lg font-medium text-gray-900 mb-2">Supprimer la catégorie</h3>
          <p class="text-sm text-gray-500 mb-4">
            Êtes-vous sûr de vouloir supprimer la catégorie "{{ categoryToDelete?.name }}" ? 
            Cette action est irréversible.
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
  name: 'CategoriesSection',
  props: {
    categories: {
      type: Array,
      default: () => []
    }
  },
  emits: ['add-category', 'edit-category', 'delete-category'],
  setup(props, { emit }) {
    const searchQuery = ref('')
    const showModal = ref(false)
    const showDeleteModal = ref(false)
    const isEditing = ref(false)
    const isSubmitting = ref(false)
    const isDeleting = ref(false)
    const categoryToDelete = ref(null)
    const formErrors = ref({})
    
    const formData = ref({
      id: null,
      name: '',
      description: ''
    })

    const filteredCategories = computed(() => {
      if (!searchQuery.value) return props.categories
      return props.categories.filter(category =>
        category.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        category.description.toLowerCase().includes(searchQuery.value.toLowerCase())
      )
    })

    const validateForm = () => {
      formErrors.value = {}
      
      if (!formData.value.name.trim()) {
        formErrors.value.name = 'Le nom est requis'
      } else if (formData.value.name.length > 255) {
        formErrors.value.name = 'Le nom ne peut pas dépasser 255 caractères'
      }
      
      if (!formData.value.description.trim()) {
        formErrors.value.description = 'La description est requise'
      } else if (formData.value.description.length > 1000) {
        formErrors.value.description = 'La description ne peut pas dépasser 1000 caractères'
      }
      
      return Object.keys(formErrors.value).length === 0
    }

    const openAddModal = () => {
      isEditing.value = false
      formData.value = { id: null, name: '', description: '' }
      formErrors.value = {}
      showModal.value = true
    }

    const openEditModal = (category) => {
      isEditing.value = true
      formData.value = { ...category }
      formErrors.value = {}
      showModal.value = true
    }

    const closeModal = () => {
      showModal.value = false
      formData.value = { id: null, name: '', description: '' }
      formErrors.value = {}
      isSubmitting.value = false
    }

    const openDeleteModal = (category) => {
      categoryToDelete.value = category
      showDeleteModal.value = true
    }

    const closeDeleteModal = () => {
      showDeleteModal.value = false
      categoryToDelete.value = null
      isDeleting.value = false
    }

    const submitForm = async () => {
      if (!validateForm()) return
      
      try {
        isSubmitting.value = true
        
        if (isEditing.value) {
          await emit('edit-category', formData.value)
        } else {
          await emit('add-category', formData.value)
        }
        
        closeModal()
      } catch (error) {
        console.error('Erreur lors de la soumission:', error)
      } finally {
        isSubmitting.value = false
      }
    }

    const confirmDelete = async () => {
      if (!categoryToDelete.value) return
      
      try {
        isDeleting.value = true
        await emit('delete-category', categoryToDelete.value.id)
        closeDeleteModal()
      } catch (error) {
        console.error('Erreur lors de la suppression:', error)
      } finally {
        isDeleting.value = false
      }
    }

    return {
      searchQuery,
      showModal,
      showDeleteModal,
      isEditing,
      isSubmitting,
      isDeleting,
      categoryToDelete,
      formData,
      formErrors,
      filteredCategories,
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