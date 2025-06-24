<template>
  <div class="bg-white rounded-xl shadow-lg">
    <!-- En-tête -->
    <div class="p-6 border-b border-gray-200">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <h3 class="text-lg font-semibold text-menara-dark mb-4 sm:mb-0">
          Gestion des Utilisateurs
        </h3>
        <div class="flex flex-col sm:flex-row gap-3">
          <div class="relative">
            <input v-model="searchTerm" type="text" placeholder="Rechercher un utilisateur..."
              class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-menara-red focus:border-transparent">
            <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
          <button @click="openAddUserModal"
            class="bg-menara-red hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            <span>Ajouter</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Message de chargement -->
    <div v-if="isLoading" class="p-6 text-center">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-menara-blue mx-auto"></div>
      <p class="mt-2 text-gray-600">Chargement des utilisateurs...</p>
    </div>

    <!-- Message d'erreur -->
    <div v-if="errorMessage" class="p-6">
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        {{ errorMessage }}
      </div>
    </div>

    <!-- Tableau des utilisateurs -->
    <div v-if="!isLoading && !errorMessage" class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Utilisateur
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Email
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Téléphone
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Rôle
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Statut
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Créé le
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="user in filteredUsers" :key="user.id" class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="h-10 w-10 rounded-full bg-menara-red flex items-center justify-center">
                  <span class="text-white font-medium">{{ user.initials }}</span>
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-menara-dark">{{ user.name }}</div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ user.email }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
              {{ user.phone }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full" :class="getRoleClass(user.role)">
                {{ user.role }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                :class="user.email_verified ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                {{ user.email_verified ? 'Vérifié' : 'Non vérifié' }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ formatDate(user.created_at) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <div class="flex space-x-2">
                <button @click="editUser(user)" class="text-blue-600 hover:text-blue-900 transition-colors">
                  <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
                <button @click="deleteUser(user)" class="text-menara-red hover:text-red-700 transition-colors">
                  <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="!isLoading && !errorMessage" class="px-6 py-4 border-t border-gray-200">
      <div class="flex items-center justify-between">
        <div class="text-sm text-gray-700">
          Affichage de <span class="font-medium">{{ filteredUsers.length }}</span>
          utilisateur{{ filteredUsers.length > 1 ? 's' : '' }}
        </div>
      </div>
    </div>
  </div>

  <!-- Modal d'ajout/édition -->
  <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
      <div class="mt-3">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-medium text-menara-dark">
            {{ isEditing ? 'Modifier' : 'Ajouter' }} un utilisateur
          </h3>
          <button @click="closeModal" class="text-gray-400 hover:text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Message d'erreur du formulaire -->
        <div v-if="formError" class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded text-sm">
          {{ formError }}
        </div>

        <!-- Message de succès -->
        <div v-if="successMessage" class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded text-sm">
          {{ successMessage }}
        </div>

        <form @submit.prevent="saveUser" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
            <input v-model="currentUser.name" type="text" required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-menara-red">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input v-model="currentUser.email" type="email" required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-menara-red">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
            <input v-model="currentUser.phone" type="tel" required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-menara-red">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
            <input v-model="currentUser.password" type="password" required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-menara-red">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
            <select v-model="currentUser.role" required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-menara-red">
              <option value="">Sélectionner un rôle</option>
              <option value="Responsable production">Responsable production</option>
              <option value="Agent commercial">Agent commercial</option>
            </select>
          </div>
          <div class="flex justify-end space-x-3 pt-4">
            <button type="button" @click="closeModal"
              class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
              Annuler
            </button>
            <button type="submit" :disabled="isSubmitting"
              class="px-4 py-2 bg-menara-red text-white rounded-md text-sm font-medium hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed">
              {{ isSubmitting ? 'En cours...' : (isEditing ? 'Modifier' : 'Ajouter') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import { useAuth } from '@/composables/useAuth'
import { adminAPI } from '@/api/endpoints'

export default {
  name: 'UserManagement',
  setup() {
    const { token } = useAuth()
    
    const searchTerm = ref('')
    const showModal = ref(false)
    const isEditing = ref(false)
    const isLoading = ref(false)
    const isSubmitting = ref(false)
    const errorMessage = ref('')
    const formError = ref('')
    const successMessage = ref('')
    
    const currentUser = ref({
      id: null,
      name: '',
      email: '',
      phone: '',
      password: '',
      role: ''
    })

    const users = ref([])

    const filteredUsers = computed(() => {
      if (!searchTerm.value) return users.value
      return users.value.filter(user =>
        user.name.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
        user.email.toLowerCase().includes(searchTerm.value.toLowerCase())
      )
    })

    const getRoleClass = (role) => {
      switch (role) {
        case 'Admin':
          return 'bg-red-100 text-red-800'
        case 'Responsable production':
          return 'bg-blue-100 text-blue-800'
        case 'Agent commercial':
          return 'bg-green-100 text-green-800'
        default:
          return 'bg-gray-100 text-gray-800'
      }
    }

    const formatDate = (dateString) => {
      const date = new Date(dateString)
      return date.toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
      })
    }

    const getInitials = (name) => {
      return name.split(' ').map(n => n.charAt(0)).join('').toUpperCase()
    }

    const loadUsers = async () => {
      try {
        isLoading.value = true
        errorMessage.value = ''
        
        const response = await adminAPI.listUsers(token.value)
        
        // Transformer les données pour l'affichage
        users.value = response.users.map(user => ({
          ...user,
          initials: getInitials(user.name)
        }))
        
      } catch (error) {
        console.error('Erreur lors du chargement des utilisateurs:', error)
        errorMessage.value = error.message
      } finally {
        isLoading.value = false
      }
    }

    const openAddUserModal = () => {
      isEditing.value = false
      formError.value = ''
      successMessage.value = ''
      currentUser.value = {
        id: null,
        name: '',
        email: '',
        phone: '',
        password: '',
        role: ''
      }
      showModal.value = true
    }

    const editUser = (user) => {
      isEditing.value = true
      formError.value = ''
      successMessage.value = ''
      currentUser.value = { 
        ...user,
        password: '' // Ne pas pré-remplir le mot de passe
      }
      showModal.value = true
    }

    const closeModal = () => {
      showModal.value = false
      formError.value = ''
      successMessage.value = ''
      currentUser.value = {
        id: null,
        name: '',
        email: '',
        phone: '',
        password: '',
        role: ''
      }
    }

    const saveUser = async () => {
      try {
        isSubmitting.value = true
        formError.value = ''
        successMessage.value = ''

        if (isEditing.value) {
          // Pour l'instant, on ne gère que la création
          formError.value = 'La modification d\'utilisateur n\'est pas encore implémentée'
          return
        }

        // Créer un nouvel utilisateur
        const userData = {
          name: currentUser.value.name,
          email: currentUser.value.email,
          phone: currentUser.value.phone,
          password: currentUser.value.password,
          role: currentUser.value.role
        }

        const response = await adminAPI.createUser(userData, token.value)
        
        successMessage.value = response.message
        
        // Ajouter le nouvel utilisateur à la liste
        const newUser = {
          ...response.user,
          initials: getInitials(response.user.name)
        }
        users.value.push(newUser)

        // Fermer le modal après 2 secondes
        setTimeout(() => {
          closeModal()
        }, 2000)

      } catch (error) {
        console.error('Erreur lors de la création de l\'utilisateur:', error)
        formError.value = error.message
      } finally {
        isSubmitting.value = false
      }
    }

    const deleteUser = (user) => {
      if (confirm(`Êtes-vous sûr de vouloir supprimer ${user.name} ?`)) {
        // Pour l'instant, suppression locale uniquement
        const index = users.value.findIndex(u => u.id === user.id)
        if (index !== -1) {
          users.value.splice(index, 1)
        }
      }
    }

    // Charger les utilisateurs au montage du composant
    onMounted(() => {
      loadUsers()
    })

    return {
      searchTerm,
      showModal,
      isEditing,
      isLoading,
      isSubmitting,
      errorMessage,
      formError,
      successMessage,
      currentUser,
      users,
      filteredUsers,
      getRoleClass,
      formatDate,
      openAddUserModal,
      editUser,
      closeModal,
      saveUser,
      deleteUser,
      loadUsers
    }
  }
}
</script>

<style scoped>
.text-menara-red {
  color: rgb(192, 15, 26);
}

.bg-menara-red {
  background-color: rgb(192, 15, 26);
}

.text-menara-dark {
  color: #1f2937;
}

.focus\:ring-menara-red:focus {
  --tw-ring-color: rgb(192, 15, 26);
}
</style>