<template>
  <header class="bg-white shadow-sm border-b border-gray-200">
    <div class="px-6 py-2">
      <div class="flex items-center justify-between">
        <!-- Title Section -->
        <div>
          <h1 class="text-2xl font-bold text-menara-dark">
            {{ getSectionTitle() }}
          </h1>
        </div>

        <!-- Actions Section -->
        <div class="flex items-center space-x-4">
          <!-- Notifications -->
          <button class="relative p-2 text-gray-400 hover:text-menara-red transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            <span class="absolute -top-1 -right-1 h-4 w-4 bg-menara-red rounded-full flex items-center justify-center">
              <span class="text-xs text-white font-medium">3</span>
            </span>
          </button>

          <!-- User Menu -->
          <div class="relative">
            <button class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 transition-colors">
              <div class="w-8 h-8 bg-menara-red rounded-full flex items-center justify-center">
                <span class="text-white text-sm font-medium">RP</span>
              </div>
              <div class="text-left">
                <p class="text-sm font-medium text-gray-900">Responsable</p>
                <p class="text-xs text-gray-500">Production</p>
              </div>
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
export default {
  name: 'ProductionHeader',
  props: {
    currentSection: {
      type: String,
      default: 'categories'
    }
  },
  methods: {
    getSectionTitle() {
      const titles = {
        categories: 'Gestion des Catégories',
        products: 'Gestion des Produits'
      }
      return titles[this.currentSection] || 'Dashboard Production'
    },

  }
}
</script>

<style scoped>
.text-menara-dark {
  color: #1f2937;
}

.bg-menara-red {
  background-color: rgb(192, 15, 26);
}

.hover\:text-menara-red:hover {
  color: rgb(192, 15, 26);
}
</style>