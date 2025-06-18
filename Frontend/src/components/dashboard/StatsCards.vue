<template>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div 
      v-for="stat in stats" 
      :key="stat.id"
      class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-menara-red hover:shadow-xl transition-shadow"
    >
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-medium text-gray-600 mb-1">{{ stat.label }}</p>
          <p class="text-3xl font-bold text-menara-dark">{{ stat.value }}</p>
          <p class="text-sm text-gray-500 mt-1">
            <span :class="stat.trend === 'up' ? 'text-green-600' : 'text-red-600'">
              {{ stat.change }}
            </span>
            vs mois dernier
          </p>
        </div>
        <div class="w-12 h-12 bg-menara-red rounded-lg flex items-center justify-center">
          <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="stat.icon"/>
          </svg>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue'

export default {
  name: 'StatsCards',
  setup() {
    const stats = ref([
      {
        id: 1,
        label: 'Utilisateurs Actifs',
        value: '247',
        change: '+12%',
        trend: 'up',
        icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z'
      },
      {
        id: 2,
        label: 'Commandes Totales',
        value: '1,249',
        change: '+23%',
        trend: 'up',
        icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'
      },
      {
        id: 3,
        label: 'Chiffre d\'Affaires',
        value: '2.4M DH',
        change: '+8%',
        trend: 'up',
        icon: 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1'
      },
      {
        id: 4,
        label: 'Taux de Satisfaction',
        value: '98.5%',
        change: '+2%',
        trend: 'up',
        icon: 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'
      }
    ])

    return {
      stats
    }
  }
}
</script>

<style scoped>
.text-menara-red {
  color: rgb(192, 15, 26);
}

.border-menara-red {
  border-color: rgb(192, 15, 26);
}

.bg-menara-red {
  background-color: rgb(192, 15, 26);
}

.text-menara-dark {
  color: #1f2937;
}
</style>