<template>
  <div class="min-h-screen bg-gray-50">
    <DashboardHeader />
    <div class="flex">
      <DashboardSidebar @navigate="handleNavigation" :active-section="activeSection" />
      <main class="flex-1 p-6">
        <!-- Vue par défaut: Créer une commande -->
        <div v-if="activeSection === 'dashboard'" class="grid grid-cols-1 max-w-4xl mx-auto">
          <CreateOrderSection />
        </div>
        
        <!-- Vue Mes Commandes: statuts en_attente, en_production, terminee -->
        <div v-if="activeSection === 'orders'">
          <OrdersTrackingSection 
            title="Mes Commandes" 
            :orders="filteredOrders" 
            :allowed-statuses="['en_attente', 'en_production', 'terminee']"
          />
        </div>
        
        <!-- Vue Historique: statut livree -->
        <div v-if="activeSection === 'history'">
          <OrdersTrackingSection 
            title="Historique des Commandes" 
            :orders="historyOrders" 
            :allowed-statuses="['livree']"
          />
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue'
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
    
    // Données simulées des commandes
    const allOrders = ref([
      {
        id: 1,
        number: 'CMD-2024-001',
        date: new Date('2024-01-15'),
        mainProduct: 'Pavé autobloquant 20x10',
        otherProducts: 2,
        amount: 12450,
        status: 'livree',
        products: [
          { id: 1, name: 'Pavé autobloquant 20x10', quantity: 150, unit: 'm²', price: 45 },
          { id: 2, name: 'Bordure T2 50x20x15', quantity: 80, unit: 'ml', price: 25 },
          { id: 3, name: 'Sable de pose', quantity: 5, unit: 'm³', price: 180 }
        ]
      },
      {
        id: 2,
        number: 'CMD-2024-002',
        date: new Date('2024-01-18'),
        mainProduct: 'Agglo creux 20x20x40',
        otherProducts: 0,
        amount: 3200,
        status: 'en_production',
        products: [
          { id: 4, name: 'Agglo creux 20x20x40', quantity: 400, unit: 'unité', price: 8 }
        ]
      },
      {
        id: 3,
        number: 'CMD-2024-003',
        date: new Date('2024-01-20'),
        mainProduct: 'Hourdis béton 16+4',
        otherProducts: 1,
        amount: 18600,
        status: 'terminee',
        products: [
          { id: 5, name: 'Hourdis béton 16+4', quantity: 180, unit: 'm²', price: 95 },
          { id: 6, name: 'Poutre précontrainte 4m', quantity: 12, unit: 'unité', price: 180 }
        ]
      },
      {
        id: 4,
        number: 'CMD-2024-004',
        date: new Date('2024-01-22'),
        mainProduct: 'Dalle béton 40x40',
        otherProducts: 0,
        amount: 2800,
        status: 'en_attente',
        products: [
          { id: 7, name: 'Dalle béton 40x40', quantity: 80, unit: 'm²', price: 35 }
        ]
      },
      {
        id: 5,
        number: 'CMD-2024-005',
        date: new Date('2024-01-25'),
        mainProduct: 'Bordure jardinière 100x20',
        otherProducts: 0,
        amount: 1900,
        status: 'livree',
        products: [
          { id: 8, name: 'Bordure jardinière 100x20', quantity: 50, unit: 'ml', price: 38 }
        ]
      },
      {
        id: 6,
        number: 'CMD-2024-006',
        date: new Date('2024-01-28'),
        mainProduct: 'Bloc béton plein 15x20x40',
        otherProducts: 0,
        amount: 4800,
        status: 'en_attente',
        products: [
          { id: 9, name: 'Bloc béton plein 15x20x40', quantity: 400, unit: 'unité', price: 12 }
        ]
      }
    ])

    // Commandes filtrées pour "Mes Commandes" (en_attente, en_production, terminee)
    const filteredOrders = computed(() => {
      return allOrders.value.filter(order => 
        ['en_attente', 'en_production', 'terminee'].includes(order.status)
      )
    })

    // Commandes pour l'historique (livree)
    const historyOrders = computed(() => {
      return allOrders.value.filter(order => order.status === 'livree')
    })

    const handleNavigation = (section) => {
      activeSection.value = section
    }

    return {
      activeSection,
      filteredOrders,
      historyOrders,
      handleNavigation
    }
  }
}
</script>

<style scoped>
/* Couleurs Menara Prefa */
:root {
  --menara-blue: #1e40af;
  --menara-dark: #1e3a8a;
  --menara-red: #dc2626;
  --menara-gray: #6b7280;
}
</style>