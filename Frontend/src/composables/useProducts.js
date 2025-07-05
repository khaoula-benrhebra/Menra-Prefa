import { ref, computed } from 'vue'
import { productsAPI } from '@/api/productsAPI'
import { useAuth } from '@/composables/useAuth'

// État global des produits
const products = ref([])
const isLoading = ref(false)

export function useProducts() {
  const { token } = useAuth()
  
  // Computed properties
  const productsCount = computed(() => products.value.length)
  
  // Méthodes
  const loadProducts = async () => {
    try {
      isLoading.value = true
      
      const response = await productsAPI.getAll(token.value || null)
      products.value = response.products.map(product => ({
        id: product.id,
        name: product.nom,
        description: product.description,
        price: parseFloat(product.prix_unitaire),
        stock_actuel: parseInt(product.stock_actuel),
        stock_min: parseInt(product.stock_min),
        category_id: product.category?.id,
        category_name: product.category?.nom || 'Aucune catégorie',
        image: product.image,
        created_at: product.created_at,
        updated_at: product.updated_at
      }))
      return products.value
    } catch (error) {
      console.error('Erreur lors du chargement des produits:', error)

      if (!token.value) {
        console.warn('Tentative de chargement des produits sans authentification')
        // On garde un tableau vide mais on ne throw pas l'erreur
        products.value = []
        return products.value
      }
      
      throw error
    } finally {
      isLoading.value = false
    }
  }

  const createProduct = async (productData) => {
    if (!token.value) {
      throw new Error('Token d\'authentification requis')
    }
    
    try {
      isLoading.value = true
      const response = await productsAPI.create(productData, token.value)
      console.log( 'test' , response) ;
      
      // Ajouter le nouveau produit à la liste locale
      const newProduct = {
        id: response.product.id,
        name: response.product.nom,
        description: response.product.description,
        price: parseFloat(response.product.prix_unitaire),
        stock_actuel: parseInt(response.product.stock_actuel),
        stock_min: parseInt(response.product.stock_min),
        category_id: response.product.category?.id,
        category_name: response.product.category?.nom || 'Aucune catégorie',
        image: response.product.image,
        created_at: response.product.created_at || new Date().toISOString(),
        updated_at: response.product.updated_at || new Date().toISOString()
      }
      products.value.push(newProduct)
      
      return newProduct
    } catch (error) {
      console.error('Erreur lors de la création du produit:', error)
      throw error
    } finally {
      isLoading.value = false
    }
  }

  const updateProduct = async (productData) => {
    if (!token.value) {
      throw new Error('Token d\'authentification requis')
    }
    
    try {
      isLoading.value = true
      const response = await productsAPI.update(productData.id, productData, token.value)
      
      // Mettre à jour le produit dans la liste locale
      const index = products.value.findIndex(product => product.id === productData.id)
      if (index !== -1) {
        products.value[index] = {
          id: response.product.id,
          name: response.product.nom,
          description: response.product.description,
          price: parseFloat(response.product.prix_unitaire),
          stock_actuel: parseInt(response.product.stock_actuel),
          stock_min: parseInt(response.product.stock_min),
          category_id: response.product.category?.id,
          category_name: response.product.category?.nom || 'Aucune catégorie',
          image: response.product.image,
          created_at: products.value[index].created_at,
          updated_at: response.product.updated_at || new Date().toISOString()
        }
      }
      
      return products.value[index]
    } catch (error) {
      console.error('Erreur lors de la modification du produit:', error)
      throw error
    } finally {
      isLoading.value = false
    }
  }

  const deleteProduct = async (productId) => {
    if (!token.value) {
      throw new Error('Token d\'authentification requis')
    }
    
    try {
      isLoading.value = true
      await productsAPI.delete(productId, token.value)
      
      // Supprimer le produit de la liste locale
      products.value = products.value.filter(product => product.id !== productId)
      
      return true
    } catch (error) {
      console.error('Erreur lors de la suppression du produit:', error)
      throw error
    } finally {
      isLoading.value = false
    }
  }

  const getProductById = (productId) => {
    return products.value.find(product => product.id === productId)
  }

  const getProductsByCategory = (categoryId) => {
    return products.value.filter(product => product.category_id === categoryId)
  }

  return {
    // État
    products: computed(() => products.value),
    isLoading: computed(() => isLoading.value),
    productsCount,
    
    // Méthodes
    loadProducts,
    createProduct,
    updateProduct,
    deleteProduct,
    getProductById,
    getProductsByCategory
  }
}