<template>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Graphique des ventes -->
    <div class="bg-white rounded-xl shadow-lg p-6">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-menara-dark">Évolution des Ventes</h3>
        <select class="px-3 py-1 border border-gray-300 rounded-lg text-sm">
          <option>7 derniers jours</option>
          <option>30 derniers jours</option>
          <option>3 derniers mois</option>
        </select>
      </div>
      <div class="h-64 flex items-end justify-between space-x-2">
        <div 
          v-for="(bar, index) in salesData" 
          :key="index"
          class="bg-gradient-to-t from-menara-red to-red-400 rounded-t-md flex-1 transition-all duration-300 hover:opacity-80"
          :style="{ height: bar.height + '%' }"
          :title="bar.label + ': ' + bar.value + ' DH'"
        ></div>
      </div>
      <div class="flex justify-between mt-2 text-xs text-gray-500">
        <span>Lun</span>
        <span>Mar</span>
        <span>Mer</span>
        <span>Jeu</span>
        <span>Ven</span>
        <span>Sam</span>
        <span>Dim</span>
      </div>
    </div>

    <!-- Répartition des produits -->
    <div class="bg-white rounded-xl shadow-lg p-6">
      <h3 class="text-lg font-semibold text-menara-dark mb-6">Répartition des Produits</h3>
      <div class="space-y-4">
        <div 
          v-for="product in productsData" 
          :key="product.id"
          class="flex items-center justify-between"
        >
          <div class="flex items-center space-x-3">
            <div 
              class="w-4 h-4 rounded-full"
              :style="{ backgroundColor: product.color }"
            ></div>
            <span class="text-sm text-gray-700">{{ product.name }}</span>
          </div>
          <div class="flex items-center space-x-2">
            <span class="text-sm font-medium text-menara-dark">{{ product.percentage }}%</span>
            <div class="w-20 h-2 bg-gray-200 rounded-full">
              <div 
                class="h-2 rounded-full transition-all duration-300"
                :style="{ 
                  width: product.percentage + '%',
                  backgroundColor: product.color 
                }"
              ></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue'

export default {
  name: 'ChartsSection',
  setup() {
    const salesData = ref([
      { label: 'Lundi', value: 45000, height: 60 },
      { label: 'Mardi', value: 52000, height: 70 },
      { label: 'Mercredi', value: 48000, height: 65 },
      { label: 'Jeudi', value: 61000, height: 85 },
      { label: 'Vendredi', value: 55000, height: 75 },
      { label: 'Samedi', value: 67000, height: 95 },
      { label: 'Dimanche', value: 71000, height: 100 }
    ])

    const productsData = ref([
      { id: 1, name: 'Pavés', percentage: 35, color: 'rgb(192, 15, 26)' },
      { id: 2, name: 'Dalles', percentage: 28, color: '#ef4444' },
      { id: 3, name: 'Bordures', percentage: 18, color: '#f87171' },
      { id: 4, name: 'Agglos', percentage: 12, color: '#fca5a5' },
      { id: 5, name: 'Hourdis', percentage: 7, color: '#fecaca' }
    ])

    return {
      salesData,
      productsData
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

.from-menara-red {
  --tw-gradient-from: rgb(192, 15, 26);
}

.text-menara-dark {
  color: #1f2937;
}
</style>