<template>
  <section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold text-menara-dark mb-4">
          Fonctionnalités de la Plateforme
        </h2>
        <p class="text-lg text-menara-gray max-w-2xl mx-auto">
          Une solution complète pour gérer efficacement vos commandes et optimiser vos opérations
        </p>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div 
          v-for="service in services" 
          :key="service.id"
          class="bg-gray-50 p-6 rounded-xl hover:shadow-lg transition-shadow"
        >
          <div class="w-12 h-12 bg-menara-red rounded-lg flex items-center justify-center mb-4">
            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="service.icon"/>
            </svg>
          </div>
          <h3 class="text-xl font-semibold text-menara-dark mb-3">{{ service.title }}</h3>
          <p class="text-menara-gray">{{ service.description }}</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import { ref } from 'vue'

export default {
  name: 'ServicesSection',
  setup() {
    const services = ref([
      {
        id: 1,
        title: 'Gestion des Commandes',
        description: 'Suivez et gérez toutes vos commandes en temps réel avec un interface intuitive.',
        icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'
      },
      {
        id: 2,
        title: 'Tableau de Bord',
        description: 'Visualisez vos KPIs et statistiques grâce à des tableaux de bord personnalisables.',
        icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'
      },
      {
        id: 3,
        title: 'Suivi Production',
        description: 'Contrôlez la production en temps réel et optimisez vos processus industriels.',
        icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'
      },
      {
        id: 4,
        title: 'Rapports Avancés',
        description: 'Générez des rapports détaillés pour une analyse approfondie de vos activités.',
        icon: 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
      },
      {
        id: 5,
        title: 'Gestion Clients',
        description: 'Centralisez les informations clients et personnalisez leurs expériences.',
        icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z'
      },
      {
        id: 6,
        title: 'Notifications',
        description: 'Restez informé des événements importants grâce aux notifications intelligentes.',
        icon: 'M15 17h5l-5 5v-5zM10.27 2.27L4.27 8.27l1.41 1.41L11 4.35V19h2V4.35l5.32 5.33 1.41-1.41-6-6a.996.996 0 00-1.41 0z'
      }
    ])

    return {
      services
    }
  }
}
</script>