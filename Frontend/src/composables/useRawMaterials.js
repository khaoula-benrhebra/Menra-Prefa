import { ref, computed } from 'vue'
import { rawMaterialsAPI } from '@/api/rawMaterialsAPI'
import { useAuth } from '@/composables/useAuth'

// État global des matières premières
const rawMaterials = ref([])
const isLoading = ref(false)

export function useRawMaterials() {
  const { token } = useAuth()
  
  // Computed properties
  const rawMaterialsCount = computed(() => rawMaterials.value.length)
  
  // Calculer le nombre de matières premières en stock bas
  const lowStockCount = computed(() => 
    rawMaterials.value.filter(material => material.stock_actuel <= material.stock_min).length
  )
  
  // Méthodes
  const loadRawMaterials = async () => {
    if (!token.value) {
      throw new Error('Token d\'authentification requis')
    }
    
    try {
      isLoading.value = true
      
      const response = await rawMaterialsAPI.getAll(token.value)
      rawMaterials.value = response.raw_materials.map(rawMaterial => ({
        id: rawMaterial.id,
        nom: rawMaterial.nom,
        prix_unitaire: parseFloat(rawMaterial.prix_unitaire),
        stock_min: parseInt(rawMaterial.stock_min),
        stock_actuel: parseInt(rawMaterial.stock_actuel),
        created_at: rawMaterial.created_at,
        updated_at: rawMaterial.updated_at
      }))
      
      return rawMaterials.value
    } catch (error) {
      console.error('Erreur lors du chargement des matières premières:', error)
      throw error
    } finally {
      isLoading.value = false
    }
  }

  const createRawMaterial = async (rawMaterialData) => {
    if (!token.value) {
      throw new Error('Token d\'authentification requis')
    }
    
    try {
      isLoading.value = true
      const response = await rawMaterialsAPI.create(rawMaterialData, token.value)
      
      // Ajouter la nouvelle matière première à la liste locale
      const newRawMaterial = {
        id: response.raw_material.id,
        nom: response.raw_material.nom,
        prix_unitaire: parseFloat(response.raw_material.prix_unitaire),
        stock_min: parseInt(response.raw_material.stock_min),
        stock_actuel: parseInt(response.raw_material.stock_actuel),
        created_at: response.raw_material.created_at || new Date().toISOString(),
        updated_at: response.raw_material.updated_at || new Date().toISOString()
      }
      
      rawMaterials.value.push(newRawMaterial)
      
      return newRawMaterial
    } catch (error) {
      console.error('Erreur lors de la création de la matière première:', error)
      throw error
    } finally {
      isLoading.value = false
    }
  }

  const updateRawMaterial = async (rawMaterialData) => {
    if (!token.value) {
      throw new Error('Token d\'authentification requis')
    }
    
    try {
      isLoading.value = true
      const response = await rawMaterialsAPI.update(rawMaterialData.id, rawMaterialData, token.value)
      
      // Mettre à jour la matière première dans la liste locale
      const index = rawMaterials.value.findIndex(material => material.id === rawMaterialData.id)
      if (index !== -1) {
        rawMaterials.value[index] = {
          id: response.raw_material.id,
          nom: response.raw_material.nom,
          prix_unitaire: parseFloat(response.raw_material.prix_unitaire),
          stock_min: parseInt(response.raw_material.stock_min),
          stock_actuel: parseInt(response.raw_material.stock_actuel),
          created_at: rawMaterials.value[index].created_at,
          updated_at: response.raw_material.updated_at || new Date().toISOString()
        }
      }
      
      return rawMaterials.value[index]
    } catch (error) {
      console.error('Erreur lors de la modification de la matière première:', error)
      throw error
    } finally {
      isLoading.value = false
    }
  }

  const deleteRawMaterial = async (rawMaterialId) => {
    if (!token.value) {
      throw new Error('Token d\'authentification requis')
    }
    
    try {
      isLoading.value = true
      await rawMaterialsAPI.delete(rawMaterialId, token.value)
      
      // Supprimer la matière première de la liste locale
      rawMaterials.value = rawMaterials.value.filter(material => material.id !== rawMaterialId)
      
      return true
    } catch (error) {
      console.error('Erreur lors de la suppression de la matière première:', error)
      throw error
    } finally {
      isLoading.value = false
    }
  }

  const getRawMaterialById = (rawMaterialId) => {
    return rawMaterials.value.find(material => material.id === rawMaterialId)
  }

  const getLowStockMaterials = () => {
    return rawMaterials.value.filter(material => material.stock_actuel <= material.stock_min)
  }

  const getStockStatus = (material) => {
    return material.stock_actuel <= material.stock_min ? 'stock_bas' : 'stock_ok'
  }

  return {
    // État
    rawMaterials: computed(() => rawMaterials.value),
    isLoading: computed(() => isLoading.value),
    rawMaterialsCount,
    lowStockCount,
    
    // Méthodes
    loadRawMaterials,
    createRawMaterial,
    updateRawMaterial,
    deleteRawMaterial,
    getRawMaterialById,
    getLowStockMaterials,
    getStockStatus
  }
}