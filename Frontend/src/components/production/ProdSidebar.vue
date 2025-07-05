<template>
  <div class="py-2 fixed inset-y-0 left-0 w-64 bg-white shadow-lg border-r border-gray-200">
    <!-- Logo Section -->
    <div class="flex items-center justify-center h-16 border-b border-gray-200">
      <div class="flex items-center">
        <img src="/assets/images/logo.png" alt="Menara Prefa Logo" class="h-8 w-auto" />
        <!-- <span class="ml-2 text-lg font-bold text-menara-dark">Production</span> -->
      </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="mt-6 px-4">
      <div class="space-y-2">
        <!-- Categories -->
        <button
          @click="$emit('section-changed', 'categories')"
          :class="[
            'w-full flex items-center px-4 py-3 text-left rounded-lg transition-colors duration-200',
            activeSection === 'categories' 
              ? 'bg-menara-red text-white' 
              : 'text-gray-700 hover:bg-gray-100'
          ]"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M19 11H5m14-5v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2z"></path>
          </svg>
          <span class="font-medium">Catégories</span>
        </button>

        <!-- Products -->
        <button
          @click="$emit('section-changed', 'products')"
          :class="[
            'w-full flex items-center px-4 py-3 text-left rounded-lg transition-colors duration-200',
            activeSection === 'products' 
              ? 'bg-menara-red text-white' 
              : 'text-gray-700 hover:bg-gray-100'
          ]"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
          </svg>
          <span class="font-medium">Produits</span>
        </button>
        
        <!-- Raw Materials -->
        <button
          @click="$emit('section-changed', 'raw-materials')"
          :class="[
            'w-full flex items-center px-4 py-3 text-left rounded-lg transition-colors duration-200',
            activeSection === 'raw-materials' 
              ? 'bg-menara-red text-white' 
              : 'text-gray-700 hover:bg-gray-100'
          ]"
        >
          <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.78 0-2.674-2.155-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
          </svg>
          <span class="font-medium">Matières Premières</span>
        </button>
      </div>
    </nav>

    <!-- User Info Section -->
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200">
      <div class="flex items-center">
        <div class="w-8 h-8 bg-menara-red rounded-full flex items-center justify-center">
          <span class="text-white text-sm font-medium">RP</span>
        </div>
        <div class="ml-3">
          <p class="text-sm font-medium text-gray-900">Responsable Production</p>
          <p class="text-xs text-gray-500">production@menara.ma</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProductionSidebar',
  props: {
    activeSection: {
      type: String,
      default: 'categories'
    }
  },
  emits: ['section-changed']
}
</script>

<style scoped>
.text-menara-dark {
  color: #1f2937;
}

.bg-menara-red {
  background-color: rgb(192, 15, 26);
}
</style>