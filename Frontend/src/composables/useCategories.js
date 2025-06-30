import { ref, computed } from 'vue'
import { categoryAPI } from '@/api/categoryAPI'
import { useAuth } from '@/composables/useAuth'

// État global des catégories
const categories = ref([])
const isLoading = ref(false)

export function useCategories() {
  const { token } = useAuth()
  
  // Computed properties
  const categoriesCount = computed(() => categories.value.length)
  
  // Méthodes
  const loadCategories = async () => {
    try {
      isLoading.value = true
      
      const response = await categoryAPI.getAll(token.value || null)
      categories.value = response.categories.map(cat => ({
        id: cat.id,
        name: cat.nom,
        description: cat.description,
        products_count: 0 
      }))
      return categories.value
    } catch (error) {
      console.error('Erreur lors du chargement des catégories:', error)

      if (!token.value) {
        console.warn('Tentative de chargement des catégories sans authentification')
        // On garde un tableau vide mais on ne throw pas l'erreur
        categories.value = []
        return categories.value
      }
      
      throw error
    } finally {
      isLoading.value = false
    }
  }

  const createCategory = async (categoryData) => {
    if (!token.value) {
      throw new Error('Token d\'authentification requis')
    }
    
    try {
      isLoading.value = true
      const response = await categoryAPI.create(categoryData, token.value)
      
      // Ajouter la nouvelle catégorie à la liste locale
      const newCategory = {
        id: response.category.id,
        name: response.category.nom,
        description: response.category.description,
        products_count: 0
      }
      categories.value.push(newCategory)
      
      return newCategory
    } catch (error) {
      console.error('Erreur lors de la création de la catégorie:', error)
      throw error
    } finally {
      isLoading.value = false
    }
  }

  const updateCategory = async (categoryData) => {
    if (!token.value) {
      throw new Error('Token d\'authentification requis')
    }
    
    try {
      isLoading.value = true
      const response = await categoryAPI.update(categoryData.id, categoryData, token.value)
      
      // Mettre à jour la catégorie dans la liste locale
      const index = categories.value.findIndex(cat => cat.id === categoryData.id)
      if (index !== -1) {
        categories.value[index] = {
          id: response.category.id,
          name: response.category.nom,
          description: response.category.description,
          products_count: categories.value[index].products_count
        }
      }
      
      return categories.value[index]
    } catch (error) {
      console.error('Erreur lors de la modification de la catégorie:', error)
      throw error
    } finally {
      isLoading.value = false
    }
  }

  const deleteCategory = async (categoryId) => {
    if (!token.value) {
      throw new Error('Token d\'authentification requis')
    }
    
    try {
      isLoading.value = true
      await categoryAPI.delete(categoryId, token.value)
      
      // Supprimer la catégorie de la liste locale
      categories.value = categories.value.filter(cat => cat.id !== categoryId)
      
      return true
    } catch (error) {
      console.error('Erreur lors de la suppression de la catégorie:', error)
      throw error
    } finally {
      isLoading.value = false
    }
  }

  const getCategoryById = (categoryId) => {
    return categories.value.find(cat => cat.id === categoryId)
  }

  return {
    // État
    categories: computed(() => categories.value),
    isLoading: computed(() => isLoading.value),
    categoriesCount,
    
    // Méthodes
    loadCategories,
    createCategory,
    updateCategory,
    deleteCategory,
    getCategoryById
  }
}