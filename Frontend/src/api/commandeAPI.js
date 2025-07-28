const API_BASE_URL = 'http://localhost:8000/api'

export const commandeEndpoints = {
  list: `${API_BASE_URL}/commandes`,
  show: (id) => `${API_BASE_URL}/commandes/${id}`,
  create: `${API_BASE_URL}/commandes`,
  update: (id) => `${API_BASE_URL}/commandes/${id}`,
  delete: (id) => `${API_BASE_URL}/commandes/${id}`,
  updateStatus: (id) => `${API_BASE_URL}/commandes/${id}/status`
}

export const commandesAPI = {
  async getAll(token) {
    if (!token) {
      throw new Error('Token d\'authentification requis')
    }
    
    try {
      const response = await fetch(commandeEndpoints.list, {
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
        }
      })
      
      if (!response.ok) {
        const error = await response.json().catch(() => ({}))
        throw new Error(error.message || `Erreur ${response.status}: ${response.statusText}`)
      }
      
      return await response.json()
    } catch (error) {
      console.error('Erreur API getAll:', error)
      throw error
    }
  },

  async getById(id, token) {
    if (!token) {
      throw new Error('Token d\'authentification requis')
    }
    
    if (!id) {
      throw new Error('ID de commande requis')
    }
    
    try {
      const response = await fetch(commandeEndpoints.show(id), {
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
        }
      })
      
      if (!response.ok) {
        const error = await response.json().catch(() => ({}))
        throw new Error(error.message || `Erreur ${response.status}: ${response.statusText}`)
      }
      
      return await response.json()
    } catch (error) {
      console.error('Erreur API getById:', error)
      throw error
    }
  },

  async create(commandeData, token) {
    if (!token) {
      throw new Error('Token d\'authentification requis')
    }
    
    // Validation des données
    if (!commandeData || typeof commandeData !== 'object') {
      throw new Error('Données de commande invalides')
    }
    
    if (!commandeData.adresse || !commandeData.adresse.trim()) {
      throw new Error('L\'adresse est requise')
    }
    
    if (!commandeData.produits || !Array.isArray(commandeData.produits) || commandeData.produits.length === 0) {
      throw new Error('Au moins un produit est requis')
    }
    
    if (!commandeData.moyen_paiement || !commandeData.moyen_paiement.trim()) {
      throw new Error('Le moyen de paiement est requis')
    }
    
    // Validation des produits
    for (const produit of commandeData.produits) {
      if (!produit.product_id || !produit.quantite || produit.quantite <= 0) {
        throw new Error('Chaque produit doit avoir un ID et une quantité valide')
      }
    }
    
    try {
      const response = await fetch(commandeEndpoints.create, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          adresse: commandeData.adresse.trim(),
          produits: commandeData.produits.map(produit => ({
            product_id: parseInt(produit.product_id),
            quantite: parseInt(produit.quantite)
          })),
          moyen_paiement: commandeData.moyen_paiement.trim(),
          commentaire: commandeData.commentaire ? commandeData.commentaire.trim() : null
        })
      })
      
      if (!response.ok) {
        const error = await response.json().catch(() => ({}))
        throw new Error(error.message || `Erreur ${response.status}: ${response.statusText}`)
      }
      
      return await response.json()
    } catch (error) {
      console.error('Erreur API create:', error)
      throw error
    }
  },

  async update(id, commandeData, token) {
    if (!token) {
      throw new Error('Token d\'authentification requis')
    }
    
    if (!id) {
      throw new Error('ID de commande requis')
    }
    
    if (!commandeData || typeof commandeData !== 'object') {
      throw new Error('Données de commande invalides')
    }
    
    try {
      const response = await fetch(commandeEndpoints.update(id), {
        method: 'PUT',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          adresse: commandeData.adresse ? commandeData.adresse.trim() : undefined,
          produits: commandeData.produits ? commandeData.produits.map(produit => ({
            product_id: parseInt(produit.product_id),
            quantite: parseInt(produit.quantite)
          })) : undefined,
          moyen_paiement: commandeData.moyen_paiement ? commandeData.moyen_paiement.trim() : undefined,
          commentaire: commandeData.commentaire !== undefined ? 
            (commandeData.commentaire ? commandeData.commentaire.trim() : null) : undefined
        })
      })
      
      if (!response.ok) {
        const error = await response.json().catch(() => ({}))
        throw new Error(error.message || `Erreur ${response.status}: ${response.statusText}`)
      }
      
      return await response.json()
    } catch (error) {
      console.error('Erreur API update:', error)
      throw error
    }
  },

  async updateStatus(id, statut, token) {
    if (!token) {
      throw new Error('Token d\'authentification requis')
    }
    
    if (!id) {
      throw new Error('ID de commande requis')
    }
    
    if (!statut) {
      throw new Error('Statut requis')
    }
    
    const validStatuses = ['en_attente', 'en_production', 'terminee', 'livree', 'annulee']
    if (!validStatuses.includes(statut)) {
      throw new Error('Statut invalide')
    }
    
    try {
      const response = await fetch(commandeEndpoints.updateStatus(id), {
        method: 'PATCH',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ statut })
      })
      
      if (!response.ok) {
        const error = await response.json().catch(() => ({}))
        throw new Error(error.message || `Erreur ${response.status}: ${response.statusText}`)
      }
      
      return await response.json()
    } catch (error) {
      console.error('Erreur API updateStatus:', error)
      throw error
    }
  },

  async delete(id, token) {
    if (!token) {
      throw new Error('Token d\'authentification requis')
    }
    
    if (!id) {
      throw new Error('ID de commande requis')
    }
    
    try {
      const response = await fetch(commandeEndpoints.delete(id), {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
        }
      })
      
      if (!response.ok) {
        const error = await response.json().catch(() => ({}))
        throw new Error(error.message || `Erreur ${response.status}: ${response.statusText}`)
      }
      
      return await response.json()
    } catch (error) {
      console.error('Erreur API delete:', error)
      throw error
    }
  }
}