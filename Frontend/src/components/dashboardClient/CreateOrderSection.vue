<template>
  <div class="bg-white rounded-xl shadow-lg p-6">
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl font-bold text-gray-800">Créer une Commande</h2>
      <button 
        class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center"
        @click="openCustomerModal"
      >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
        </svg>
        Nouvelle Commande
      </button>
    </div>

    <!-- Message d'instruction -->
    <div v-if="!customerInfo.isValid" class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
      <div class="flex items-center">
        <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-blue-800">
          Cliquez sur "Nouvelle Commande" pour commencer en saisissant vos informations personnelles.
        </p>
      </div>
    </div>

    <!-- Informations client validées -->
    <div v-if="customerInfo.isValid" class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <div>
            <p class="text-green-800 font-medium">{{ customerInfo.prenom }} {{ customerInfo.nom }}</p>
            <p class="text-green-600 text-sm">{{ customerInfo.email }}</p>
          </div>
        </div>
        <button 
          @click="openCustomerModal"
          class="text-green-600 hover:text-green-800 text-sm underline"
        >
          Modifier
        </button>
      </div>
    </div>

    <!-- Étapes de commande (seulement si les infos client sont validées) -->
    <div v-if="customerInfo.isValid" class="space-y-4">
      <!-- Étape 1: Sélection catégorie -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          1. Sélectionnez une catégorie
        </label>
        <div class="grid grid-cols-2 gap-3">
          <div 
            v-for="category in categories" 
            :key="category.id"
            class="p-4 border-2 rounded-lg cursor-pointer transition-all hover:shadow-md"
            :class="{
              'border-blue-500 bg-blue-50': selectedCategory === category.id,
              'border-gray-200 hover:border-gray-300': selectedCategory !== category.id
            }"
            @click="selectCategory(category.id)"
          >
            <div class="flex items-center space-x-3">
              <div 
                class="w-10 h-10 rounded-lg flex items-center justify-center" 
                :class="selectedCategory === category.id ? 'bg-blue-500' : 'bg-gray-100'"
              >
                <svg 
                  class="w-5 h-5" 
                  :class="selectedCategory === category.id ? 'text-white' : 'text-gray-600'" 
                  fill="none" 
                  stroke="currentColor" 
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="category.icon"/>
                </svg>
              </div>
              <div>
                <h3 class="font-medium text-gray-900">{{ category.name }}</h3>
                <p class="text-sm text-gray-500">{{ getProductsCount(category.id) }} produits</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Étape 2: Sélection produits -->
      <div v-if="selectedCategory" class="animate-fade-in">
        <label class="block text-sm font-medium text-gray-700 mb-2">
          2. Sélectionnez vos produits
        </label>
        <div class="space-y-3 max-h-64 overflow-y-auto pr-2">
          <div 
            v-for="product in getProductsByCategory(selectedCategory)" 
            :key="product.id"
            class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50"
            :class="{'bg-blue-50': isProductSelected(product.id)}"
          >
            <div class="flex items-center space-x-3">
              <input 
                type="checkbox" 
                :id="'product-' + product.id"
                class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500"
                :checked="isProductSelected(product.id)"
                @change="toggleProduct(product.id)"
              >
              <label :for="'product-' + product.id" class="flex-1 cursor-pointer">
                <div>
                  <h4 class="font-medium text-gray-900">{{ product.name }}</h4>
                  <p class="text-sm text-gray-500">{{ product.price }} MAD/{{ product.unit }}</p>
                </div>
              </label>
            </div>
            <div v-if="isProductSelected(product.id)" class="flex items-center space-x-2">
              <button 
                class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center"
                @click="decrementQuantity(product.id)"
                :disabled="getProductQuantity(product.id) <= 1"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                </svg>
              </button>
              <input 
                type="number" 
                min="1" 
                :value="getProductQuantity(product.id)"
                class="w-16 px-2 py-1 text-center border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                @input="updateQuantity(product.id, $event.target.value)"
                @blur="validateQuantity(product.id)"
              >
              <button 
                class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center"
                @click="incrementQuantity(product.id)"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Résumé et validation -->
      <div v-if="selectedProducts.length > 0" class="animate-fade-in">
        <div class="bg-gray-50 p-4 rounded-lg">
          <h3 class="font-medium text-gray-900 mb-2">Résumé de la commande</h3>
          <div class="space-y-1 text-sm">
            <div v-for="item in selectedProducts" :key="item.id" class="flex justify-between">
              <span>{{ item.name }} ({{ item.quantity }} {{ item.unit }})</span>
              <span class="font-medium">{{ (item.price * item.quantity).toLocaleString() }} MAD</span>
            </div>
          </div>
          <div class="border-t pt-2 mt-2">
            <div class="flex justify-between font-bold">
              <span>Total</span>
              <span class="text-red-600">{{ totalAmount.toLocaleString() }} MAD</span>
            </div>
          </div>
          <button 
            class="w-full mt-4 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-colors"
            @click="validateOrder"
          >
            Valider la Commande
          </button>
        </div>
      </div>
    </div>

    <!-- Modal des informations client -->
    <div v-if="showCustomerModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click="closeCustomerModal">
      <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto" @click.stop>
        <div class="flex items-center justify-between mb-6">
          <h3 class="text-lg font-bold text-gray-900">Informations Client</h3>
          <button @click="closeCustomerModal" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
        
        <form @submit.prevent="saveCustomerInfo" class="space-y-4">
          <!-- Nom -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
            <input 
              v-model="customerForm.nom"
              type="text" 
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="Votre nom"
            >
          </div>

          <!-- Prénom -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
            <input 
              v-model="customerForm.prenom"
              type="text" 
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="Votre prénom"
            >
          </div>

          <!-- Email -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
            <input 
              v-model="customerForm.email"
              type="email" 
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="votre@email.com"
            >
          </div>

          <!-- Adresse -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Adresse *</label>
            <textarea 
              v-model="customerForm.adresse"
              required
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="Votre adresse complète"
            ></textarea>
          </div>

          <!-- Téléphone -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone *</label>
            <input 
              v-model="customerForm.telephone"
              type="tel" 
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="0612345678"
            >
          </div>

          <!-- Moyen de paiement -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Moyen de paiement *</label>
            <select 
              v-model="customerForm.moyenPaiement"
              required
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
              <option value="">Sélectionnez un moyen de paiement</option>
              <option value="especes">Espèces</option>
              <option value="carte">Carte bancaire</option>
              <option value="cheque">Chèque</option>
              <option value="virement">Virement bancaire</option>
              <option value="credit">Crédit (30 jours)</option>
            </select>
          </div>

          <!-- Commentaires -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Commentaires</label>
            <textarea 
              v-model="customerForm.commentaires"
              rows="2"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              placeholder="Commentaires ou instructions particulières (optionnel)"
            ></textarea>
          </div>

          <!-- Boutons -->
          <div class="flex space-x-3 pt-4">
            <button 
              type="button"
              @click="closeCustomerModal"
              class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
            >
              Annuler
            </button>
            <button 
              type="submit"
              class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
            >
              Valider
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue'

export default {
  name: 'CreateOrderSection',
  setup() {
    const selectedCategory = ref(null)
    const selectedProducts = ref([])
    const showCustomerModal = ref(false)
    
    // Informations client
    const customerInfo = ref({
      nom: '',
      prenom: '',
      email: '',
      adresse: '',
      telephone: '',
      moyenPaiement: '',
      commentaires: '',
      isValid: false
    })

    // Formulaire client (pour le modal)
    const customerForm = ref({
      nom: '',
      prenom: '',
      email: '',
      adresse: '',
      telephone: '',
      moyenPaiement: '',
      commentaires: ''
    })
    
    const categories = ref([
      {
        id: 1,
        name: 'Pavés & Dalles',
        icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'
      },
      {
        id: 2,
        name: 'Bordures & Caniveaux',
        icon: 'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z'
      },
      {
        id: 3,
        name: 'Agglos & Blocs',
        icon: 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16'
      },
      {
        id: 4,
        name: 'Hourdis & Poutres',
        icon: 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'
      }
    ])

    const products = ref([
      { id: 1, categoryId: 1, name: 'Pavé autobloquant 20x10', price: 45, unit: 'm²' },
      { id: 2, categoryId: 1, name: 'Dalle béton 40x40', price: 35, unit: 'm²' },
      { id: 3, categoryId: 1, name: 'Pavé carrossable 20x20', price: 55, unit: 'm²' },
      
      { id: 4, categoryId: 2, name: 'Bordure T2 50x20x15', price: 25, unit: 'ml' },
      { id: 5, categoryId: 2, name: 'Caniveau béton 30x30', price: 42, unit: 'ml' },
      { id: 6, categoryId: 2, name: 'Bordure jardinière 100x20', price: 38, unit: 'ml' },
      
      { id: 7, categoryId: 3, name: 'Agglo creux 20x20x40', price: 8, unit: 'unité' },
      { id: 8, categoryId: 3, name: 'Bloc béton plein 15x20x40', price: 12, unit: 'unité' },
      { id: 9, categoryId: 3, name: 'Agglo isolant 20x25x50', price: 15, unit: 'unité' },
      
      { id: 10, categoryId: 4, name: 'Hourdis béton 16+4', price: 95, unit: 'm²' },
      { id: 11, categoryId: 4, name: 'Poutre précontrainte 4m', price: 180, unit: 'unité' },
      { id: 12, categoryId: 4, name: 'Plancher alvéolaire 20cm', price: 120, unit: 'm²' }
    ])

    const productQuantities = ref({})

    // Fonctions du modal client
    const openCustomerModal = () => {
      // Pré-remplir le formulaire avec les données existantes
      customerForm.value = { ...customerInfo.value }
      showCustomerModal.value = true
    }

    const closeCustomerModal = () => {
      showCustomerModal.value = false
    }

    const saveCustomerInfo = () => {
      // Copier les données du formulaire vers customerInfo
      customerInfo.value = {
        ...customerForm.value,
        isValid: true
      }
      showCustomerModal.value = false
    }

    const getProductsCount = (categoryId) => {
      return products.value.filter(product => product.categoryId === categoryId).length
    }

    const selectCategory = (categoryId) => {
      selectedCategory.value = categoryId
    }

    const getProductsByCategory = (categoryId) => {
      return products.value.filter(product => product.categoryId === categoryId)
    }

    const isProductSelected = (productId) => {
      return selectedProducts.value.some(p => p.id === productId)
    }

    const toggleProduct = (productId) => {
      const product = products.value.find(p => p.id === productId)
      const index = selectedProducts.value.findIndex(p => p.id === productId)
      
      if (index > -1) {
        selectedProducts.value.splice(index, 1)
        delete productQuantities.value[productId]
      } else {
        selectedProducts.value.push({
          ...product,
          quantity: productQuantities.value[productId] || 1
        })
        productQuantities.value[productId] = productQuantities.value[productId] || 1
      }
    }

    const updateQuantity = (productId, quantity) => {
      const qty = parseInt(quantity) || 1
      productQuantities.value[productId] = qty
      
      const productIndex = selectedProducts.value.findIndex(p => p.id === productId)
      if (productIndex > -1) {
        selectedProducts.value[productIndex].quantity = qty
      }
    }

    const validateQuantity = (productId) => {
      if (!productQuantities.value[productId] || productQuantities.value[productId] < 1) {
        productQuantities.value[productId] = 1
        updateQuantity(productId, 1)
      }
    }

    const incrementQuantity = (productId) => {
      const currentQty = productQuantities.value[productId] || 1
      updateQuantity(productId, currentQty + 1)
    }

    const decrementQuantity = (productId) => {
      const currentQty = productQuantities.value[productId] || 1
      if (currentQty > 1) {
        updateQuantity(productId, currentQty - 1)
      }
    }

    const getProductQuantity = (productId) => {
      return productQuantities.value[productId] || 0
    }

    const totalAmount = computed(() => {
      return selectedProducts.value.reduce((total, product) => {
        return total + (product.price * product.quantity)
      }, 0)
    })

    const resetOrder = () => {
      selectedCategory.value = null
      selectedProducts.value = []
      productQuantities.value = {}
      // Ne pas réinitialiser customerInfo pour garder les données du client
    }

    const validateOrder = () => {
      const orderData = {
        customer: customerInfo.value,
        products: selectedProducts.value,
        total: totalAmount.value,
        date: new Date()
      }
      
      console.log('Commande validée:', orderData)
      alert(`Commande validée pour ${customerInfo.value.prenom} ${customerInfo.value.nom} - Total: ${totalAmount.value.toLocaleString()} MAD`)
      
      // Réinitialiser seulement la sélection des produits
      resetOrder()
    }

    return {
      selectedCategory,
      selectedProducts,
      showCustomerModal,
      customerInfo,
      customerForm,
      categories,
      products,
      productQuantities,
      openCustomerModal,
      closeCustomerModal,
      saveCustomerInfo,
      getProductsCount,
      selectCategory,
      getProductsByCategory,
      isProductSelected,
      toggleProduct,
      updateQuantity,
      validateQuantity,
      incrementQuantity,
      decrementQuantity,
      getProductQuantity,
      totalAmount,
      resetOrder,
      validateOrder
    }
  }
}
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Animation pour le modal */
.fixed {
  animation: modalFadeIn 0.2s ease-out;
}

@keyframes modalFadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

/* Style pour la scrollbar */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}
</style>