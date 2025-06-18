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
            <input
              v-model="searchTerm"
              type="text"
              placeholder="Rechercher un utilisateur..."
              class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-menara-red focus:border-transparent"
            >
            <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
          <button
            @click="openAddUserModal"
            class="bg-menara-red hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center space-x-2"
          >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            <span>Ajouter</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Tableau des utilisateurs -->
    <div class="overflow-x-auto">
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
              Rôle
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Statut
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Dernière connexion
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
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                    :class="getRoleClass(user.role)">
                {{ user.role }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                    :class="user.status === 'Actif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                {{ user.status }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ user.lastLogin }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <div class="flex space-x-2">
                <button
                  @click="editUser(user)"
                  class="text-blue-600 hover:text-blue-900 transition-colors"
                >
                  <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                </button>
                <button
                  @click="deleteUser(user)"
                  class="text-menara-red hover:text-red-700 transition-colors"
                >
                  <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200">
      <div class="flex items-center justify-between">
        <div class="text-sm text-gray-700">
          Affichage de <span class="font-medium">1</span> à <span class="font-medium">{{ filteredUsers.length }}</span>
          sur <span class="font-medium">{{ filteredUsers.length }}</span> utilisateurs
        </div>
        <div class="flex space-x-2">
          <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">
            Précédent
          </button>
          <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">
            Suivant
          </button>
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
        <form @submit.prevent="saveUser" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
            <input
              v-model="currentUser.name"
              type="text"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-menara-red"
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input
              v-model="currentUser.email"
              type="email"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-menara-red"
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
            <select
              v-model="currentUser.role"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-menara-red"
            >
              <option value="Admin">Admin</option>
              <option value="Manager">Manager</option>
              <option value="Utilisateur">Utilisateur</option>
            </select>
          </div>
          <div class="flex justify-end space-x-3 pt-4">
            <button
              type="button"
              @click="closeModal"
              class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
              Annuler
            </button>
            <button
              type="submit"
              class="px-4 py-2 bg-menara-red text-white rounded-md text-sm font-medium hover:bg-red-700"
            >
              {{ isEditing ? 'Modifier' : 'Ajouter' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue'

export default {
  name: 'UserManagement',
  setup() {
    const searchTerm = ref('')
    const showModal = ref(false)
    const isEditing = ref(false)
    const currentUser = ref({
      id: null,
      name: '',
      email: '',
      role: 'Utilisateur'
    })

    const users = ref([
      {
        id: 1,
        name: 'Ahmed Benali',
        email: 'ahmed.benali@menaraprefa.ma',
        role: 'Admin',
        status: 'Actif',
        lastLogin: '2024-01-15 14:30',
        initials: 'AB'
      },
      {
        id: 2,
        name: 'Fatima Zahra',
        email: 'fatima.zahra@menaraprefa.ma',
        role: 'Manager',
        status: 'Actif',
        lastLogin: '2024-01-15 09:15',
        initials: 'FZ'
      },
      {
        id: 3,
        name: 'Mohamed Alami',
        email: 'mohamed.alami@menaraprefa.ma',
        role: 'Utilisateur',
        status: 'Inactif',
        lastLogin: '2024-01-10 16:45',
        initials: 'MA'
      },
      {
        id: 4,
        name: 'Aicha Bensouda',
        email: 'aicha.bensouda@menaraprefa.ma',
        role: 'Manager',
        status: 'Actif',
        lastLogin: '2024-01-15 11:20',
        initials: 'AB'
      },
      {
        id: 5,
        name: 'Youssef Benjelloun',
        email: 'youssef.benjelloun@menaraprefa.ma',
        role: 'Utilisateur',
        status: 'Actif',
        lastLogin: '2024-01-14 13:30',
        initials: 'YB'
      }
    ])

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
        case 'Manager':
          return 'bg-blue-100 text-blue-800'
        default:
          return 'bg-gray-100 text-gray-800'
      }
    }

    const openAddUserModal = () => {
      isEditing.value = false
      currentUser.value = {
        id: null,
        name: '',
        email: '',
        role: 'Utilisateur'
      }
      showModal.value = true
    }

    const editUser = (user) => {
      isEditing.value = true
      currentUser.value = { ...user }
      showModal.value = true
    }

    const closeModal = () => {
      showModal.value = false
      currentUser.value = {
        id: null,
        name: '',
        email: '',
        role: 'Utilisateur'
      }
    }

    const saveUser = () => {
      if (isEditing.value) {
        const index = users.value.findIndex(u => u.id === currentUser.value.id)
        if (index !== -1) {
          users.value[index] = {
            ...currentUser.value,
            initials: currentUser.value.name.split(' ').map(n => n.charAt(0)).join(''),
            status: users.value[index].status,
            lastLogin: users.value[index].lastLogin
          }
        }
      } else {
        const newUser = {
          ...currentUser.value,
          id: Date.now(),
          initials: currentUser.value.name.split(' ').map(n => n.charAt(0)).join(''),
          status: 'Actif',
          lastLogin: 'Jamais connecté'
        }
        users.value.push(newUser)
      }
      closeModal()
    }

    const deleteUser = (user) => {
      if (confirm(`Êtes-vous sûr de vouloir supprimer ${user.name} ?`)) {
        const index = users.value.findIndex(u => u.id === user.id)
        if (index !== -1) {
          users.value.splice(index, 1)
        }
      }
    }

    return {
      searchTerm,
      showModal,
      isEditing,
      currentUser,
      users,
      filteredUsers,
      getRoleClass,
      openAddUserModal,
      editUser,
      closeModal,
      saveUser,
      deleteUser
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