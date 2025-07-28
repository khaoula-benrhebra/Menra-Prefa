<template>
  <div class="min-h-screen bg-gray-50">
    <DashboardHeader />
    <div class="flex">
      <DashboardSidebar @navigate="handleNavigation" :active-section="activeSection" />
      <main class="flex-1 p-6">
       
        <div v-if="activeSection === 'dashboard'" class="grid grid-cols-1 max-w-4xl mx-auto">
          <CreateOrderSection @order-created="handleOrderCreated" />
        </div>
        
       
        <div v-if="activeSection === 'orders'">
          <OrdersTrackingSection 
            title="Mes Commandes" 
            :orders="mesCommandes" 
            :allowed-statuses="['en_attente', 'terminee']"
            @status-changed="handleStatusChanged"
          />
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted, watch } from 'vue'
import { useCommandes } from '@/composables/useCommandes'
import DashboardHeader from '../components/dashboardClient/DashboardHeader.vue'
import DashboardSidebar from '../components/dashboardClient/DashboardSidebar.vue'
import CreateOrderSection from '../components/dashboardClient/CreateOrderSection.vue'
import OrdersTrackingSection from '../components/dashboardClient/OrdersTrackingSection.vue'

export default {
  name: 'DashboardClientView',
  components: {
    DashboardHeader,
    DashboardSidebar,
    CreateOrderSection,
    OrdersTrackingSection
  },
  setup() {
    const activeSection = ref('dashboard')
    const { 
      commandes, 
      getOrdersForTracking, 
      loadCommandes, 
      isLoading,
      updateCommandeStatus 
    } = useCommandes()

    // Charger les commandes au montage
    onMounted(async () => {
      try {
        await loadCommandes()
      } catch (error) {
        console.error('Erreur lors du chargement des commandes:', error)
      }
    })

    // Mes Commandes
    const mesCommandes = computed(() => {
      return getOrdersForTracking.value
        .filter(order => ['en_attente', 'terminee'].includes(order.status))
        .sort((a, b) => new Date(b.date) - new Date(a.date))
    })

    const handleNavigation = (section) => {
      activeSection.value = section
    }

    // Gérer la création d'une nouvelle commande
    const handleOrderCreated = async (newOrder) => {
      try {
       
        await loadCommandes(true)
        
        
        console.log('Nouvelle commande créée:', newOrder)
        
        // Rediriger vers "Mes Commandes" pour voir la nouvelle commande
        activeSection.value = 'orders'
      } catch (error) {
        console.error('Erreur lors de la gestion de la nouvelle commande:', error)
      }
    }

    // Gérer les changements de statut
    const handleStatusChanged = async (orderId, newStatus) => {
      try {
        await updateCommandeStatus(orderId, newStatus)
        // Recharger les commandes pour mettre à jour l'affichage
        await loadCommandes(true)
      } catch (error) {
        console.error('Erreur lors du changement de statut:', error)
      }
    }

    // Surveiller les changements de commandes pour le debug
    watch(commandes, (newCommandes) => {
      console.log('Commandes mises à jour:', newCommandes.length)
      console.log('Mes commandes:', mesCommandes.value.length)
    }, { deep: true })

    return {
      activeSection,
      mesCommandes,
      handleNavigation,
      handleOrderCreated,
      handleStatusChanged,
      isLoading
    }
  }
}
</script>

<style scoped>

:root {
  --menara-blue: #1e40af;
  --menara-dark: #1e3a8a;
  --menara-red: #dc2626;
  --menara-gray: #6b7280;
}
</style>