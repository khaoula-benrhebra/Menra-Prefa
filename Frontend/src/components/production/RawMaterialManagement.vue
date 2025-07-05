<template>
  <div class="space-y-6">
    <!-- Header avec bouton d'ajout -->
    <div class="flex justify-between items-center">
      <h2 class="text-2xl font-bold text-gray-900">Gestion des Matières Premières</h2>
      <button
        @click="openAddModal"
        class="bg-menara-red text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200 flex items-center"
      >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Ajouter une Matière Première
      </button>
    </div>

    <!-- Liste des matières premières -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Nom
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Prix Unitaire
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Stock Min
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Stock Actuel
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Statut
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="material in rawMaterials" :key="material.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ material.nom }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ material.prix_unitaire }} MAD</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ material.stock_min }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ material.stock_actuel }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span 
                  :class="[
                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                    material.stock_actuel <= material.stock_min 
                      ? 'bg-red-100 text-red-800' 
                      : 'bg-green-100 text-green-800'
                  ]"
                >
                  {{ material.stock_actuel <= material.stock_min ? 'Stock bas' : 'Stock OK' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button
                  @click="openEditModal(material)"
                  class="text-indigo-600 hover:text-indigo-900 mr-3"
                >
                  Modifier
                </button>
                <button
                  @click="confirmDelete(material)"
                  class="text-red-600 hover:text-red-900"
                >
                  Supprimer
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal d'ajout/modification -->
    <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">
            {{ isEditing ? 'Modifier' : 'Ajouter' }} une Matière Première
          </h3>
          
          <form @submit.prevent="handleSubmit">
            <!-- Nom -->
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Nom de la matière première
              </label>
              <input
                v-model="formData.nom"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-menara-red"
                placeholder="Ex: Ciment, Acier..."
              />
            </div>

            <!-- Prix unitaire -->
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Prix unitaire (MAD)
              </label>
              <input
                v-model.number="formData.prix_unitaire"
                type="number"
                step="0.01"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-menara-red"
                placeholder="0.00"
              />
            </div>

            <!-- Stock minimum -->
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Stock minimum
              </label>
              <input
                v-model.number="formData.stock_min"
                type="number"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-menara-red"
                placeholder="0"
              />
            </div>

            <!-- Stock actuel -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Stock actuel
              </label>
              <input
                v-model.number="formData.stock_actuel"
                type="number"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-menara-red"
                placeholder="0"
              />
            </div>

            <!-- Boutons -->
            <div class="flex justify-end space-x-3">
              <button
                type="button"
                @click="closeModal"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300"
              >
                Annuler
              </button>
              <button
                type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-menara-red rounded-md hover:bg-red-700"
              >
                {{ isEditing ? 'Modifier' : 'Ajouter' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
          <h3 class="text-lg font-medium text-gray-900 mb-4">
            Confirmer la suppression
          </h3>
          <p class="text-sm text-gray-500 mb-6">
            Êtes-vous sûr de vouloir supprimer cette matière première ? Cette action est irréversible.
          </p>
          <div class="flex justify-center space-x-3">
            <button
              @click="closeDeleteModal"
              class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300"
            >
              Annuler
            </button>
            <button
              @click="handleDelete"
              class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700"
            >
              Supprimer
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, reactive } from 'vue'

export default {
  name: 'RawMaterialManagement',
  props: {
    rawMaterials: {
      type: Array,
      default: () => []
    }
  },
  emits: ['add-raw-material', 'edit-raw-material', 'delete-raw-material'],
  setup(props, { emit }) {
    // État des modales
    const showModal = ref(false)
    const showDeleteModal = ref(false)
    const isEditing = ref(false)
    const materialToDelete = ref(null)

    // Données du formulaire
    const formData = reactive({
      id: null,
      nom: '',
      prix_unitaire: 0,
      stock_min: 0,
      stock_actuel: 0
    })

    // Réinitialiser le formulaire
    const resetForm = () => {
      formData.id = null
      formData.nom = ''
      formData.prix_unitaire = 0
      formData.stock_min = 0
      formData.stock_actuel = 0
    }

    // Ouvrir le modal d'ajout
    const openAddModal = () => {
      resetForm()
      isEditing.value = false
      showModal.value = true
    }

    // Ouvrir le modal de modification
    const openEditModal = (material) => {
      formData.id = material.id
      formData.nom = material.nom
      formData.prix_unitaire = material.prix_unitaire
      formData.stock_min = material.stock_min
      formData.stock_actuel = material.stock_actuel
      isEditing.value = true
      showModal.value = true
    }

    // Fermer le modal
    const closeModal = () => {
      showModal.value = false
      resetForm()
    }

    // Gérer la soumission du formulaire
    const handleSubmit = () => {
      if (isEditing.value) {
        // Émettre l'événement de modification
        emit('edit-raw-material', { ...formData })
      } else {
        // Émettre l'événement d'ajout
        emit('add-raw-material', { ...formData })
      }
      closeModal()
    }

    // Confirmer la suppression
    const confirmDelete = (material) => {
      materialToDelete.value = material
      showDeleteModal.value = true
    }

    // Fermer le modal de suppression
    const closeDeleteModal = () => {
      showDeleteModal.value = false
      materialToDelete.value = null
    }

    // Gérer la suppression
    const handleDelete = () => {
      if (materialToDelete.value) {
        // Émettre l'événement de suppression
        emit('delete-raw-material', materialToDelete.value.id)
      }
      closeDeleteModal()
    }

    return {
      showModal,
      showDeleteModal,
      isEditing,
      formData,
      openAddModal,
      openEditModal,
      closeModal,
      handleSubmit,
      confirmDelete,
      closeDeleteModal,
      handleDelete
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