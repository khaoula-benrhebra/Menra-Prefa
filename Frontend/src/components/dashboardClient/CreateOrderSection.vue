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

    <!-- Indicateur de chargement -->
    <div v-if="isLoadingData" class="text-center py-8">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div>
      <p class="mt-2 text-gray-600">Chargement des données...</p>
    </div>

    <!-- Message d'instruction -->
    <div v-if="!customerInfo.isValid && !isLoadingData" class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
      <div class="flex items-center">
        <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-blue-800">
          Cliquez sur "Nouvelle Commande" pour commencer en saisissant vos informations personnelles.
        </p>
      </div>
    </div>

    <!-- Message d'erreur -->
    <div v-if="errorMessage" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
      <div class="flex items-center">
        <svg class="w-5 h-5 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-red-800">{{ errorMessage }}</p>
      </div>
    </div>

    <!-- Message de succès -->
    <div v-if="successMessage" class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
      <div class="flex items-center">
        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-green-800">{{ successMessage }}</p>
      </div>
    </div>

    <!-- Informations client validées -->
    <div v-if="customerInfo.isValid && !isLoadingData" class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <div>
            <p class="text-green-800 font-medium">{{ customerInfo.nom }}</p>
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
    <div v-if="customerInfo.isValid && !isLoadingData" class="space-y-4">
      <!-- Étape 1: Sélection catégorie -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
          1. Sélectionnez une catégorie
        </label>
        <div v-if="categories.length === 0" class="text-gray-500 text-center py-4">
          Aucune catégorie disponible
        </div>
        <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-3">
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
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
              </div>
              <div>
                <h3 class="font-medium text-gray-900">{{ category.name }}</h3>
                <p class="text-sm text-gray-500">{{ category.products_count }} produits</p>
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
        <div v-if="getProductsByCategory(selectedCategory).length === 0" class="text-gray-500 text-center py-4">
          Aucun produit disponible dans cette catégorie
        </div>
        <div v-else class="space-y-3 max-h-64 overflow-y-auto pr-2">
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
                  <p class="text-sm text-gray-500">{{ formatCurrency(product.price) }}</p>
                  <p class="text-xs text-gray-400">Stock: {{ product.stock_actuel }}</p>
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
              <span>{{ item.name }} ({{ item.quantity }})</span>
              <span class="font-medium">{{ formatCurrency(item.price * item.quantity) }}</span>
            </div>
          </div>
          <div class="border-t pt-2 mt-2">
            <div class="flex justify-between font-bold">
              <span>Total</span>
              <span class="text-red-600">{{ formatCurrency(totalAmount) }}</span>
            </div>
          </div>
          <button 
            class="w-full mt-4 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-colors"
            @click="validateOrder"
            :disabled="isLoading || selectedProducts.length === 0"
          >
            {{ isLoading ? 'Création en cours...' : 'Valider la Commande' }}
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
              <option value="espece">Espèces</option>
              <option value="virement">Virement bancaire</option>
              <option value="cheque">Chèque</option>
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
import { ref, computed, onMounted, watch } from 'vue'
import { useCommandes } from '@/composables/useCommandes'
import { useProducts } from '@/composables/useProducts'
import { useCategories } from '@/composables/useCategories'

export default {
  name: 'CreateOrderSection',
  setup() {
    const { 
      createCommande, 
      isLoading, 
      error,
      formatCurrency,
      refreshCommandes
    } = useCommandes()
    
    const { 
      products, 
      loadProducts,
      isLoading: productsLoading,
      error: productsError,
      getProductsByCategory
    } = useProducts()

    const { 
      categories, 
      loadCategories,
      isLoading: categoriesLoading,
      error: categoriesError
    } = useCategories()

    const selectedCategory = ref(null)
    const selectedProducts = ref([])
    const showCustomerModal = ref(false)
    const errorMessage = ref('')
    const successMessage = ref('')
    
    // Informations client (sans prénom)
    const customerInfo = ref({
      nom: '',
      email: '',
      adresse: '',
      telephone: '',
      moyenPaiement: '',
      commentaires: '',
      isValid: false
    })

    // Formulaire client 
    const customerForm = ref({
      nom: '',
      email: '',
      adresse: '',
      telephone: '',
      moyenPaiement: '',
      commentaires: ''
    })

    const productQuantities = ref({})

    // Computed pour vérifier si on est en train de charger
    const isLoadingData = computed(() => {
      return isLoading.value || productsLoading.value || categoriesLoading.value
    })

    // Charger les données au montage
    onMounted(async () => {
      try {
        errorMessage.value = ''
        await Promise.all([
          loadProducts(),
          loadCategories()
        ])
        console.log('Données chargées:', {
          products: products.value.length,
          categories: categories.value.length
        })
      } catch (err) {
        console.error('Erreur lors du chargement des données:', err)
        errorMessage.value = 'Erreur lors du chargement des données: ' + err.message
      }
    })

    // Surveiller les erreurs
    watch([error, productsError, categoriesError], ([commandeError, prodError, catError]) => {
      if (commandeError || prodError || catError) {
        errorMessage.value = commandeError || prodError || catError
      }
    })

    // Fonctions du modal client
    const openCustomerModal = () => {
      customerForm.value = { ...customerInfo.value }
      showCustomerModal.value = true
      errorMessage.value = ''
      successMessage.value = ''
    }

    const closeCustomerModal = () => {
      showCustomerModal.value = false
    }

    const saveCustomerInfo = () => {
      // Validation 
      if (!customerForm.value.nom || !customerForm.value.email || 
          !customerForm.value.adresse || !customerForm.value.telephone || !customerForm.value.moyenPaiement) {
        errorMessage.value = 'Tous les champs obligatoires doivent être remplis'
        return
      }

      customerInfo.value = {
        ...customerForm.value,
        isValid: true
      }
      showCustomerModal.value = false
      errorMessage.value = ''
      successMessage.value = 'Informations client sauvegardées avec succès'
      
      setTimeout(() => {
        successMessage.value = ''
      }, 3000)
    }

    const selectCategory = (categoryId) => {
      selectedCategory.value = categoryId
      selectedProducts.value = []
      productQuantities.value = {}
    }

    const isProductSelected = (productId) => {
      return selectedProducts.value.some(p => p.id === productId)
    }

    const toggleProduct = (productId) => {
      const product = products.value.find(p => p.id === productId)
      if (!product) return

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
      const qty = Math.max(1, parseInt(quantity) || 1)
      productQuantities.value[productId] = qty
      
      const productIndex = selectedProducts.value.findIndex(p => p.id === productId)
      if (productIndex > -1) {
        selectedProducts.value[productIndex].quantity = qty
      }
    }

    const validateQuantity = (productId) => {
      if (!productQuantities.value[productId] || productQuantities.value[productId] < 1) {
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
        return total + (parseFloat(product.price) * product.quantity)
      }, 0)
    })

    const resetOrder = () => {
      selectedCategory.value = null
      selectedProducts.value = []
      productQuantities.value = {}
      errorMessage.value = ''
      successMessage.value = ''
    }

    const validateOrder = async () => {
      try {
        errorMessage.value = ''
        successMessage.value = ''
        
        if (!customerInfo.value.isValid) {
          errorMessage.value = 'Veuillez remplir les informations client'
          return
        }

        if (selectedProducts.value.length === 0) {
          errorMessage.value = 'Veuillez sélectionner au moins un produit'
          return
        }

        const commandeData = {
          adresse: customerInfo.value.adresse,
          moyen_paiement: customerInfo.value.moyenPaiement,
          commentaire: customerInfo.value.commentaires || null,
          produits: selectedProducts.value.map(product => ({
            product_id: product.id,
            quantite: product.quantity
          }))
        }
        
        const result = await createCommande(commandeData)
        
        if (result.success) {
          successMessage.value = `Commande créée avec succès! Numéro: ${result.commande?.id || 'N/A'} - Total: ${formatCurrency(totalAmount.value)}`
          
          await refreshCommandes()
          resetOrder()
          
          setTimeout(() => {
            successMessage.value = ''
          }, 5000)
        } else {
          errorMessage.value = result.message || 'Erreur lors de la création de la commande'
        }
      } catch (error) {
        console.error('Erreur lors de la validation de la commande:', error)
        errorMessage.value = error.message || 'Erreur lors de la création de la commande'
      }
    }

    return {
      // État
      selectedCategory,
      selectedProducts,
      showCustomerModal,
      customerInfo,
      customerForm,
      categories,
      products,
      productQuantities,
      isLoading,
      isLoadingData,
      errorMessage,
      successMessage,
      
      // Fonctions
      openCustomerModal,
      closeCustomerModal,
      saveCustomerInfo,
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
      validateOrder,
      formatCurrency
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