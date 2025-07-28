<template>
  <aside class="w-64 bg-white shadow-lg border-r border-gray-200 min-h-screen">
    <nav class="p-4 h-full flex flex-col">
      <ul class="space-y-2 flex-1">
        <li>
          <button 
            @click="$emit('navigate', 'dashboard')"
            class="w-full flex items-center space-x-3 p-3 rounded-lg transition-colors"
            :class="activeSection === 'dashboard' ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-50'"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"/>
            </svg>
            <span>Tableau de Bord</span>
          </button>
        </li>
        
        <li>
          <button 
            @click="$emit('navigate', 'orders')"
            class="w-full flex items-center space-x-3 p-3 rounded-lg transition-colors"
            :class="activeSection === 'orders' ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-700 hover:bg-gray-50'"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span>Mes Commandes</span>
          </button>
        </li>
      </ul>
      
      <!-- Section support -->
      <div class="mt-auto p-4 bg-gray-50 rounded-lg">
        <h3 class="text-sm font-medium text-gray-800 mb-2">Besoin d'aide ?</h3>
        <p class="text-xs text-gray-600 mb-3">Contactez notre équipe support</p>
        <button class="w-full bg-red-600 text-white text-sm py-2 px-3 rounded-lg hover:bg-red-700 transition-colors">
          Contacter Support
        </button>
      </div>
    </nav>
  </aside>
</template>

<script>
export default {
  name: 'DashboardSidebar',
  props: {
    activeSection: {
      type: String,
      default: 'dashboard'
    }
  },
  emits: ['navigate']
}
</script>