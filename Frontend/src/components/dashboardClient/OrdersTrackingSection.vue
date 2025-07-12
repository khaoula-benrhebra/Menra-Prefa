<template>
  <div class="bg-white rounded-xl shadow-lg p-6">
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl font-bold text-gray-800">{{ title }}</h2>
      <div class="flex space-x-2">
        <select 
          v-model="selectedStatusFilter"
          class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        >
          <option value="">Tous les statuts</option>
          <option v-for="status in allowedStatuses" :key="status" :value="status">
            {{ getStatusText(status) }}
          </option>
        </select>
        <button 
          @click="refreshOrders"
          class="p-2 text-gray-500 hover:text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
          </svg>
        </button>
      </div>
    </div>

    <!-- Message si aucune commande -->
    <div v-if="displayedOrders.length === 0" class="text-center py-12">
      <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
      </svg>
      <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune commande trouvée</h3>
      <p class="text-gray-500">
        {{ title === 'Historique des Commandes' ? 
          'Vous n\'avez encore aucune commande livrée.' : 
          'Vous n\'avez aucune commande en cours.' }}
      </p>
    </div>

    <!-- Tableau des commandes -->
    <div v-else class="overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="border-b border-gray-200">
            <th class="text-left pb-3 text-sm font-medium text-gray-600">N° Commande</th>
            <th class="text-left pb-3 text-sm font-medium text-gray-600">Date</th>
            <th class="text-left pb-3 text-sm font-medium text-gray-600">Produits</th>
            <th class="text-left pb-3 text-sm font-medium text-gray-600">Montant</th>
            <th class="text-left pb-3 text-sm font-medium text-gray-600">Statut</th>
            <th class="text-left pb-3 text-sm font-medium text-gray-600">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr 
            v-for="order in displayedOrders" 
            :key="order.id"
            class="hover:bg-gray-50 transition-colors"
          >
            <td class="py-4">
              <span class="font-medium text-gray-900">#{{ order.number }}</span>
            </td>
            <td class="py-4 text-sm text-gray-600">
              {{ formatDate(order.date) }}
            </td>
            <td class="py-4">
              <div class="text-sm">
                <span class="font-medium text-gray-900">{{ order.mainProduct }}</span>
                <span v-if="order.otherProducts > 0" class="text-gray-500">
                  +{{ order.otherProducts }} autre(s)
                </span>
              </div>
            </td>
            <td class="py-4">
              <span class="font-medium text-gray-900">{{ order.amount.toLocaleString() }} MAD</span>
            </td>
            <td class="py-4">
              <span 
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                :class="getStatusClass(order.status)"
              >
                <span class="w-2 h-2 rounded-full mr-1.5" :class="getStatusDotClass(order.status)"></span>
                {{ getStatusText(order.status) }}
              </span>
            </td>
            <td class="py-4">
              <div class="flex space-x-2">
                <button 
                  class="p-1 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded"
                  @click="viewOrder(order.id)"
                  title="Voir les détails"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                </button>
                <button 
                  class="p-1 text-gray-600 hover:text-gray-800 hover:bg-gray-50 rounded"
                  @click="downloadOrder(order.id)"
                  title="Télécharger"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="displayedOrders.length > 0" class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
      <div class="text-sm text-gray-600">
        Affichage de {{ displayedOrders.length }} commande(s)
      </div>
      <div class="flex space-x-2">
        <button class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-50 disabled:opacity-50" disabled>
          Précédent
        </button>
        <button class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
          1
        </button>
        <button class="px-3 py-1 text-sm border border-gray-300 rounded hover:bg-gray-50 disabled:opacity-50" disabled>
          Suivant
        </button>
      </div>
    </div>

    <!-- Modal de détail -->
    <div v-if="showOrderModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click="closeModal">
      <div class="bg-white rounded-xl p-6 max-w-2xl w-full mx-4 max-h-[80vh] overflow-y-auto" @click.stop>
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-bold text-gray-900">Détail Commande #{{ selectedOrder?.number }}</h3>
          <button @click="closeModal" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
        
        <div v-if="selectedOrder" class="space-y-4">
          <!-- Informations générales -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Date de commande</label>
              <p class="mt-1 text-sm text-gray-900">{{ formatDate(selectedOrder.date) }}</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700">Statut</label>
              <span 
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1"
                :class="getStatusClass(selectedOrder.status)"
              >
                <span class="w-2 h-2 rounded-full mr-1.5" :class="getStatusDotClass(selectedOrder.status)"></span>
                {{ getStatusText(selectedOrder.status) }}
              </span>
            </div>
          </div>

          <!-- Progression (seulement pour les commandes non livrées) -->
          <div v-if="selectedOrder.status !== 'livree'">
            <label class="block text-sm font-medium text-gray-700 mb-2">Progression</label>
            <div class="flex items-center space-x-4">
              <div class="flex-1">
                <div class="flex justify-between text-sm text-gray-600 mb-1">
                  <span>Commande validée</span>
                  <span>En production</span>
                  <span>Terminée</span>
                  <span>Livrée</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                  <div 
                    class="bg-blue-600 h-2 rounded-full transition-all duration-500"
                    :style="{ width: getProgressWidth(selectedOrder.status) }"
                  ></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Produits -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Produits commandés</label>
            <div class="space-y-2">
              <div v-for="product in selectedOrder.products" :key="product.id" 
                   class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                <div>
                  <p class="font-medium text-gray-900">{{ product.name }}</p>
                  <p class="text-sm text-gray-600">{{ product.quantity }} {{ product.unit }}</p>
                </div>
                <span class="font-medium text-gray-900">{{ (product.price * product.quantity).toLocaleString() }} MAD</span>
              </div>
            </div>
          </div>

          <!-- Total -->
          <div class="border-t pt-4">
            <div class="flex justify-between items-center">
              <span class="text-lg font-bold text-gray-900">Total</span>
              <span class="text-lg font-bold text-red-600">{{ selectedOrder.amount.toLocaleString() }} MAD</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue'

export default {
  name: 'OrdersTrackingSection',
  props: {
    title: {
      type: String,
      default: 'Suivi des Commandes'
    },
    orders: {
      type: Array,
      default: () => []
    },
    allowedStatuses: {
      type: Array,
      default: () => ['en_attente', 'en_production', 'terminee', 'livree']
    }
  },
  setup(props) {
    const showOrderModal = ref(false)
    const selectedOrder = ref(null)
    const selectedStatusFilter = ref('')

    const displayedOrders = computed(() => {
      let filtered = props.orders
      
      if (selectedStatusFilter.value) {
        filtered = filtered.filter(order => order.status === selectedStatusFilter.value)
      }
      
      return filtered.sort((a, b) => new Date(b.date) - new Date(a.date))
    })

    const formatDate = (date) => {
      return date.toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
      })
    }

    const getStatusClass = (status) => {
      const classes = {
        'en_attente': 'bg-yellow-100 text-yellow-800',
        'en_production': 'bg-blue-100 text-blue-800',
        'terminee': 'bg-green-100 text-green-800',
        'livree': 'bg-gray-100 text-gray-800'
      }
      return classes[status] || 'bg-gray-100 text-gray-800'
    }

    const getStatusDotClass = (status) => {
      const classes = {
        'en_attente': 'bg-yellow-400',
        'en_production': 'bg-blue-400',
        'terminee': 'bg-green-400',
        'livree': 'bg-gray-400'
      }
      return classes[status] || 'bg-gray-400'
    }

    const getStatusText = (status) => {
      const texts = {
        'en_attente': 'En attente',
        'en_production': 'En production',
        'terminee': 'Terminée',
        'livree': 'Livrée'
      }
      return texts[status] || 'Inconnu'
    }

    const getProgressWidth = (status) => {
      const progress = {
        'en_attente': '25%',
        'en_production': '50%',
        'terminee': '75%',
        'livree': '100%'
      }
      return progress[status] || '0%'
    }

    const viewOrder = (orderId) => {
      selectedOrder.value = props.orders.find(order => order.id === orderId)
      showOrderModal.value = true
    }

    const closeModal = () => {
      showOrderModal.value = false
      selectedOrder.value = null
    }

    const downloadOrder = (orderId) => {
      // Simulation du téléchargement
      alert(`Téléchargement de la commande #${orderId} en cours...`)
    }

    const refreshOrders = () => {
      // Simulation du rafraîchissement
      alert('Données mises à jour')
    }

    return {
      showOrderModal,
      selectedOrder,
      selectedStatusFilter,
      displayedOrders,
      formatDate,
      getStatusClass,
      getStatusDotClass,
      getStatusText,
      getProgressWidth,
      viewOrder,
      closeModal,
      downloadOrder,
      refreshOrders
    }
  }
}
</script>

<style scoped>
/* Animation pour le modal */
.fixed {
  animation: fadeIn 0.2s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

/* Responsive table */
@media (max-width: 768px) {
  table {
    font-size: 0.875rem;
  }
}
</style>