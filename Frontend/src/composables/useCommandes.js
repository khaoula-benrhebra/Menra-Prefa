import { ref, computed, watch } from 'vue'
import { commandesAPI } from '@/api/commandeAPI'
import { useAuth } from '@/composables/useAuth'

const commandes = ref([])
const isLoading = ref(false)
const error = ref(null)

export function useCommandes() {
  const { token } = useAuth()
  
  const commandesCount = computed(() => commandes.value.length)
  
  const commandesEnAttente = computed(() => 
    commandes.value.filter(commande => commande.statut === 'en_attente')
  )
  
  const commandesEnProduction = computed(() => 
    commandes.value.filter(commande => commande.statut === 'en_production')
  )
  
  const commandesTerminees = computed(() => 
    commandes.value.filter(commande => commande.statut === 'terminee')
  )
  
  const commandesLivrees = computed(() => 
    commandes.value.filter(commande => commande.statut === 'livree')
  )
  
  const commandesAnnulees = computed(() => 
    commandes.value.filter(commande => commande.statut === 'annulee')
  )
  
  const totalCommandesValue = computed(() => 
    commandes.value.reduce((total, commande) => total + parseFloat(commande.total || 0), 0)
  )
  
  const getOrdersForTracking = computed(() => {
    return commandes.value.map(commande => ({
      id: commande.id,
      number: commande.id.toString().padStart(6, '0'),
      date: new Date(commande.date_commande || commande.created_at),
      mainProduct: commande.products?.length > 0 ? commande.products[0].nom : 'Produit inconnu',
      otherProducts: Math.max(0, (commande.products?.length || 0) - 1),
      amount: parseFloat(commande.total || 0),
      status: commande.statut,
      products: commande.products?.map(product => ({
        id: product.id,
        name: product.nom,
        quantity: product.quantite,
        unit: 'unité(s)',
        price: parseFloat(product.prix_unitaire || 0)
      })) || [],
      paymentMethod: commande.moyen_paiement,
      comment: commande.commentaire,
      address: commande.adresse
    }))
  })
  
  const normalizeCommandeData = (rawCommande) => {
    if (!rawCommande) return null
    
    return {
      id: rawCommande.id,
      date_commande: rawCommande.date_commande,
      statut: rawCommande.statut || 'en_attente',
      total: parseFloat(rawCommande.total || 0),
      commentaire: rawCommande.commentaire || null,
      moyen_paiement: rawCommande.moyen_paiement,
      adresse: rawCommande.adresse,
      products: (rawCommande.products || []).map(product => ({
        id: product.id,
        nom: product.nom,
        prix_unitaire: parseFloat(product.prix_unitaire || 0),
        quantite: parseInt(product.pivot?.quantite || product.quantite || 0),
        total_ligne: parseFloat(product.prix_unitaire || 0) * parseInt(product.pivot?.quantite || product.quantite || 0)
      })),
      created_at: rawCommande.created_at,
      updated_at: rawCommande.updated_at
    }
  }
  
  const determineInitialStatus = (commandeData, productsAvailability) => {
   
    if (productsAvailability && Array.isArray(productsAvailability)) {
      const allProductsAvailable = productsAvailability.every(product => 
        product.available && product.sufficient_stock
      )
      
      if (allProductsAvailable) {
        return 'terminee'
      }
    }
    
    return 'en_attente'
  }
  
 
  const loadCommandes = async (forceReload = false) => {
    if (!token.value) {
      throw new Error('Token d\'authentification requis')
    }
    
    if (isLoading.value && !forceReload) {
      return commandes.value
    }
    
    try {
      isLoading.value = true
      error.value = null
      
      const response = await commandesAPI.getAll(token.value)
      
      const data = response.data || response
      
      if (!Array.isArray(data)) {
        throw new Error('Format de réponse invalide')
      }
      
      commandes.value = data.map(normalizeCommandeData).filter(Boolean)
      
      console.log('Commandes chargées:', commandes.value.length)
      console.log('Répartition par statut:', {
        en_attente: commandesEnAttente.value.length,
        en_production: commandesEnProduction.value.length,
        terminee: commandesTerminees.value.length,
        livree: commandesLivrees.value.length,
        annulee: commandesAnnulees.value.length
      })
      
      return commandes.value
    } catch (err) {
      error.value = err.message || 'Erreur lors du chargement des commandes'
      console.error('Erreur lors du chargement des commandes:', err)
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const createCommande = async (commandeData, productsAvailability = null) => {
    if (!token.value) {
      return {
        success: false,
        message: 'Token d\'authentification requis'
      }
    }
    
    try {
      isLoading.value = true
      error.value = null
     
      const initialStatus = determineInitialStatus(commandeData, productsAvailability)
      
     
      const commandeWithStatus = {
        ...commandeData,
        statut: initialStatus
      }
      
      const response = await commandesAPI.create(commandeWithStatus, token.value)
      
      
      if (response && response.success !== false) {
        const commandeResponse = response.data || response
        const newCommande = normalizeCommandeData(commandeResponse)
        
        if (newCommande) {
          commandes.value.unshift(newCommande)
          
          console.log('Nouvelle commande créée avec statut:', newCommande.statut)
          
          return {
            success: true,
            commande: newCommande,
            message: response.message || 'Commande créée avec succès'
          }
        }
      }
      
      return {
        success: false,
        message: response.message || 'Erreur lors de la création de la commande'
      }
    } catch (err) {
      error.value = err.message || 'Erreur lors de la création de la commande'
      console.error('Erreur lors de la création de la commande:', err)
      return {
        success: false,
        message: err.message || 'Erreur lors de la création de la commande'
      }
    } finally {
      isLoading.value = false
    }
  }

  const updateCommande = async (commandeId, commandeData) => {
    if (!token.value) {
      throw new Error('Token d\'authentification requis')
    }
    
    if (!commandeId) {
      throw new Error('ID de commande requis')
    }
    
    try {
      isLoading.value = true
      error.value = null
      
      const response = await commandesAPI.update(commandeId, commandeData, token.value)
      
      const commandeResponse = response.data || response
      const updatedCommande = normalizeCommandeData(commandeResponse)
      
      if (updatedCommande) {
        
        const index = commandes.value.findIndex(commande => commande.id === commandeId)
        if (index !== -1) {
          commandes.value[index] = updatedCommande
        }
        
        return {
          success: true,
          commande: updatedCommande,
          message: response.message || 'Commande mise à jour avec succès'
        }
      }
      
      throw new Error('Réponse invalide du serveur')
    } catch (err) {
      error.value = err.message || 'Erreur lors de la modification de la commande'
      console.error('Erreur lors de la modification de la commande:', err)
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const updateCommandeStatus = async (commandeId, statut) => {
    if (!token.value) {
      throw new Error('Token d\'authentification requis')
    }
    
    if (!commandeId) {
      throw new Error('ID de commande requis')
    }
    
    try {
      isLoading.value = true
      error.value = null
      
      const response = await commandesAPI.updateStatus(commandeId, statut, token.value)
      
      const commandeResponse = response.data || response
      const updatedCommande = normalizeCommandeData(commandeResponse)
      
      if (updatedCommande) {
        
        const index = commandes.value.findIndex(commande => commande.id === commandeId)
        if (index !== -1) {
          const oldStatus = commandes.value[index].statut
          commandes.value[index] = updatedCommande
          
          console.log(`Commande ${commandeId}: ${oldStatus} → ${statut}`)
          
          if (statut === 'livree') {
            console.log('Commande déplacée vers l\'historique')
          }
        }
        
        return {
          success: true,
          commande: updatedCommande,
          message: response.message || 'Statut mis à jour avec succès'
        }
      }
      
      throw new Error('Réponse invalide du serveur')
    } catch (err) {
      error.value = err.message || 'Erreur lors de la mise à jour du statut'
      console.error('Erreur lors de la mise à jour du statut:', err)
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const deleteCommande = async (commandeId) => {
    if (!token.value) {
      throw new Error('Token d\'authentification requis')
    }
    
    if (!commandeId) {
      throw new Error('ID de commande requis')
    }
    
    try {
      isLoading.value = true
      error.value = null
      
      const response = await commandesAPI.delete(commandeId, token.value)
      
      
      commandes.value = commandes.value.filter(commande => commande.id !== commandeId)
      
      return {
        success: true,
        message: response.message || 'Commande supprimée avec succès'
      }
    } catch (err) {
      error.value = err.message || 'Erreur lors de la suppression de la commande'
      console.error('Erreur lors de la suppression de la commande:', err)
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const getCommandeById = async (commandeId) => {
    if (!commandeId) {
      throw new Error('ID de commande requis')
    }
    
   
    const cachedCommande = commandes.value.find(commande => commande.id === commandeId)
    if (cachedCommande) {
      return cachedCommande
    }
    
    if (!token.value) {
      throw new Error('Token d\'authentification requis')
    }
    
    try {
      isLoading.value = true
      error.value = null
      
      const response = await commandesAPI.getById(commandeId, token.value)
      
      const commandeResponse = response.data || response
      const commande = normalizeCommandeData(commandeResponse)
      
      if (commande) {
        // Ajouter à la liste locale si pas déjà présent
        const existingIndex = commandes.value.findIndex(c => c.id === commandeId)
        if (existingIndex === -1) {
          commandes.value.push(commande)
        } else {
          commandes.value[existingIndex] = commande
        }
        
        return commande
      }
      
      throw new Error('Commande non trouvée')
    } catch (err) {
      error.value = err.message || 'Erreur lors de la récupération de la commande'
      console.error('Erreur lors de la récupération de la commande:', err)
      throw err
    } finally {
      isLoading.value = false
    }
  }

  // Méthodes utilitaires
  const getCommandesByStatus = (statut) => {
    return commandes.value.filter(commande => commande.statut === statut)
  }

  const canModifyCommande = (commande) => {
    if (!commande) return false
    return commande.statut === 'en_attente' || commande.statut === 'en_production'
  }

  const canDeleteCommande = (commande) => {
    if (!commande) return false
    return commande.statut === 'en_attente'
  }

  const canChangeStatusToLivree = (commande) => {
    if (!commande) return false
    return commande.statut === 'terminee'
  }

  const getCommandeStatusText = (statut) => {
    const statusMap = {
      'en_attente': 'En attente',
      'en_production': 'En production',
      'terminee': 'Terminée',
      'livree': 'Livrée',
      'annulee': 'Annulée'
    }
    return statusMap[statut] || statut
  }

  const getCommandeStatusColor = (statut) => {
    const colorMap = {
      'en_attente': 'orange',
      'en_production': 'blue',
      'terminee': 'green',
      'livree': 'gray',
      'annulee': 'red'
    }
    return colorMap[statut] || 'gray'
  }

  const getMoyenPaiementText = (moyenPaiement) => {
    const paymentMap = {
      'espece': 'Espèces',
      'virement': 'Virement bancaire',
      'cheque': 'Chèque'
    }
    return paymentMap[moyenPaiement] || moyenPaiement
  }

  const formatDate = (dateString) => {
    if (!dateString) return ''
    try {
      return new Date(dateString).toLocaleDateString('fr-FR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      })
    } catch (error) {
      return dateString
    }
  }

  const formatCurrency = (amount) => {
    return new Intl.NumberFormat('fr-FR', {
      style: 'decimal',
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    }).format(amount || 0) + ' MAD'
  }

  // Méthodes utilitaires pour les statistiques
  const getCommandesStats = () => {
    return {
      total: commandesCount.value,
      enAttente: commandesEnAttente.value.length,
      enProduction: commandesEnProduction.value.length,
      terminees: commandesTerminees.value.length,
      livrees: commandesLivrees.value.length,
      annulees: commandesAnnulees.value.length,
      chiffreAffaires: totalCommandesValue.value
    }
  }

  // Réinitialiser l'état
  const resetState = () => {
    commandes.value = []
    isLoading.value = false
    error.value = null
  }

  // Rafraîchir les données périodiquement
  const refreshCommandes = async () => {
    try {
      await loadCommandes(true)
    } catch (error) {
      console.error('Erreur lors du rafraîchissement:', error)
    }
  }

  // Surveiller les changements de token
  watch(token, (newToken) => {
    if (!newToken) {
      resetState()
    }
  })

  return {
    // État
    commandes: computed(() => commandes.value),
    isLoading: computed(() => isLoading.value),
    error: computed(() => error.value),
    commandesCount,
    commandesEnAttente,
    commandesEnProduction,
    commandesTerminees,
    commandesLivrees,
    commandesAnnulees,
    totalCommandesValue,
    getOrdersForTracking,
    
    // Méthodes CRUD
    loadCommandes,
    createCommande,
    updateCommande,
    updateCommandeStatus,
    deleteCommande,
    getCommandeById,
    getCommandesByStatus,
    refreshCommandes,
    
    // Utilitaires
    canModifyCommande,
    canDeleteCommande,
    canChangeStatusToLivree,
    getCommandeStatusText,
    getCommandeStatusColor,
    getMoyenPaiementText,
    formatDate,
    formatCurrency,
    getCommandesStats,
    resetState,
    determineInitialStatus
  }
}