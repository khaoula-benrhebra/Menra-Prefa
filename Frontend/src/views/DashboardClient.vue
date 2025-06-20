<template>
  <div class="min-h-screen bg-gray-50">
    <DashboardHeader />
    <div class="flex">
      <DashboardSidebar />
      <main class="flex-1 p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
          <CreateOrderSection />
          <OrdersTrackingSection />
        </div>
      </main>
    </div>
  </div>
</template>

<script>
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