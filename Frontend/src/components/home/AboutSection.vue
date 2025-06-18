<template>
  <section id="about" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
          À propos de <span class="text-red-600">Menara Prefa</span>
        </h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">
          Leader incontournable du BTP au Maroc depuis 2002
        </p>
      </div>

      <div class="grid md:grid-cols-2 gap-12 items-start">
        <!-- Colonne gauche : Texte principal -->
        <div>
          <h3 class="text-2xl font-bold text-gray-800 mb-6">
            Leader du BTP au Maroc
          </h3>
          <p class="text-gray-600 mb-6 leading-relaxed">
            Depuis 2002, Ménara Préfa s'est spécialisée dans la production de 
            produits en béton préfabriqués de haute qualité. Notre expertise couvre 
            les pavés, dalles, bordures, agglos, hourdis, poutres précontraintes et 
            béton prêt à l'emploi.
          </p>
          <p class="text-gray-600 mb-8 leading-relaxed">
            Cette plateforme digitale modernise notre approche en centralisant la 
            gestion des commandes et en optimisant nos processus internes.
          </p>

          <!-- Statistiques -->
          <div class="grid grid-cols-2 gap-4">
            <div class="text-center">
              <div class="text-3xl font-bold text-red-600 mb-1">20+</div>
              <div class="text-sm text-gray-600">Années d'expérience</div>
            </div>
            <div class="text-center">
              <div class="text-3xl font-bold text-red-600 mb-1">500+</div>
              <div class="text-sm text-gray-600">Collaborateurs</div>
            </div>
          </div>
        </div>

        <!-- Colonne droite : Carte Mission -->
        <div class="bg-white p-8 rounded-xl shadow-lg border-l-4 border-red-600">
          <h3 class="text-2xl font-bold text-gray-800 mb-6">
            Notre Mission
          </h3>
          <div class="space-y-4">
            <div class="flex items-start space-x-3">
              <div class="w-6 h-6 bg-red-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
              </div>
              <span class="text-gray-700 font-medium">Digitaliser les processus de commande</span>
            </div>
            <div class="flex items-start space-x-3">
              <div class="w-6 h-6 bg-red-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
              </div>
              <span class="text-gray-700 font-medium">Optimiser la gestion interne</span>
            </div>
            <div class="flex items-start space-x-3">
              <div class="w-6 h-6 bg-red-600 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
              </div>
              <span class="text-gray-700 font-medium">Améliorer l'expérience client</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'AboutSection'
}
</script>

<style scoped>
.text-red-600 {
  color: rgb(192, 15, 26);
}
</style>