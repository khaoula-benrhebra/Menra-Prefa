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
          :disabled="isLoading"
        >
          <svg class="w-5 h-5" :class="{ 'animate-spin': isLoading }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
          </svg>
        </button>
      </div>
    </div>

    <!-- Message de chargement -->
    <div v-if="isLoading" class="flex items-center justify-center py-12">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
      <span class="ml-2 text-gray-600">Chargement des commandes...</span>
    </div>

    <!-- Message d'erreur -->
    <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
      <div class="flex items-center">
        <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="text-red-800">{{ error }}</span>
      </div>
    </div>

    <!-- Message si aucune commande -->
    <div v-else-if="displayedOrders.length === 0" class="text-center py-12">
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
              <span class="font-medium text-gray-900">{{ formatCurrency(order.amount) }}</span>
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
                  @click="viewOrder(order)"
                  title="Voir les détails"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                </button>
                <button 
                  class="p-1 text-gray-600 hover:text-gray-800 hover:bg-gray-50 rounded"
                  @click="downloadOrder(order)"
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

          <!-- Adresse de livraison -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Adresse de livraison</label>
            <p class="mt-1 text-sm text-gray-900">{{ selectedOrder.address }}</p>
          </div>

          <!-- Moyen de paiement -->
          <div>
            <label class="block text-sm font-medium text-gray-700">Moyen de paiement</label>
            <p class="mt-1 text-sm text-gray-900">{{ getMoyenPaiementText(selectedOrder.paymentMethod) }}</p>
          </div>

          <!-- Commentaire -->
          <div v-if="selectedOrder.comment">
            <label class="block text-sm font-medium text-gray-700">Commentaire</label>
            <p class="mt-1 text-sm text-gray-900">{{ selectedOrder.comment }}</p>
          </div>

          <!-- Progression -->
          <div v-if="selectedOrder.status !== 'livree' && selectedOrder.status !== 'annulee'">
            <label class="block text-sm font-medium text-gray-700 mb-2">Progression</label>
            <div class="flex items-center space-x-4">
              <div class="flex-1">
                <div class="flex justify-between text-sm text-gray-600 mb-1">
                  <span>En attente</span>
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
                <span class="font-medium text-gray-900">{{ formatCurrency(product.price * product.quantity) }}</span>
              </div>
            </div>
          </div>

          <!-- Total -->
          <div class="border-t pt-4">
            <div class="flex justify-between items-center">
              <span class="text-lg font-bold text-gray-900">Total</span>
              <span class="text-lg font-bold text-red-600">{{ formatCurrency(selectedOrder.amount) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue'
import { useCommandes } from '@/composables/useCommandes'

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
    const { 
      getCommandeStatusText, 
      getCommandeStatusColor,
      getMoyenPaiementText,
      formatDate,
      formatCurrency,
      refreshCommandes,
      isLoading,
      error
    } = useCommandes()

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

    const getStatusClass = (status) => {
      const color = getCommandeStatusColor(status)
      return `bg-${color}-100 text-${color}-800`
    }

    const getStatusDotClass = (status) => {
      const color = getCommandeStatusColor(status)
      return `bg-${color}-400`
    }

    const getStatusText = (status) => {
      return getCommandeStatusText(status)
    }

    const getProgressWidth = (status) => {
      const progressMap = {
        'en_attente': '25%',
        'en_production': '50%',
        'terminee': '75%',
        'livree': '100%'
      }
      return progressMap[status] || '0%'
    }

    const viewOrder = (order) => {
      selectedOrder.value = order
      showOrderModal.value = true
    }

    const closeModal = () => {
      showOrderModal.value = false
      selectedOrder.value = null
    }

    const downloadOrder = (order) => {
     
      const content = `
FACTURE - Commande #${order.number}
===============================

Date: ${formatDate(order.date)}
Statut: ${getStatusText(order.status)}
Adresse: ${order.address}
Moyen de paiement: ${getMoyenPaiementText(order.paymentMethod)}

PRODUITS:
${order.products.map(p => `- ${p.name} x${p.quantity} = ${formatCurrency(p.price * p.quantity)}`).join('\n')}

TOTAL: ${formatCurrency(order.amount)}
      `.trim()

    
      const blob = new Blob([content], { type: 'text/plain' })
      const url = URL.createObjectURL(blob)
      const a = document.createElement('a')
      a.href = url
      a.download = `commande-${order.number}.txt`
      document.body.appendChild(a)
      a.click()
      document.body.removeChild(a)
      URL.revokeObjectURL(url)
    }

    const refreshOrders = async () => {
      try {
        await refreshCommandes()
      } catch (error) {
        console.error('Erreur lors du rafraîchissement:', error)
      }
    }

    return {
      showOrderModal,
      selectedOrder,
      selectedStatusFilter,
      displayedOrders,
      isLoading,
      error,
      formatDate,
      formatCurrency,
      getStatusClass,
      getStatusDotClass,
      getStatusText,
      getProgressWidth,
      getMoyenPaiementText,
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

/* Animation de rotation pour le bouton refresh */
.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

/* Responsive table */
@media (max-width: 768px) {
  table {
    font-size: 0.875rem;
  }
}
</style>