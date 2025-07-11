const API_BASE_URL = 'http://localhost:8000/api'

export const rawMaterialEndpoints = {
  list: `${API_BASE_URL}/raw-materials`,
  show: (id) => `${API_BASE_URL}/raw-materials/${id}`,
  create: `${API_BASE_URL}/raw-materials`,
  update: (id) => `${API_BASE_URL}/raw-materials/${id}`,
  delete: (id) => `${API_BASE_URL}/raw-materials/${id}`
}

export const rawMaterialsAPI = {
  async getAll(token) {
    const headers = {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token}`
    }
    
    const response = await fetch(rawMaterialEndpoints.list, {
      headers
    })
    
    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.message || 'Erreur lors de la récupération des matières premières')
    }
    
    return response.json()
  },

  async getById(id, token) {
    const headers = {
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token}`
    }
    
    const response = await fetch(rawMaterialEndpoints.show(id), {
      headers
    })
    
    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.message || 'Erreur lors de la récupération de la matière première')
    }
    
    return response.json()
  },

  async create(rawMaterialData, token) {
    const response = await fetch(rawMaterialEndpoints.create, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`,
      },
      body: JSON.stringify({
        nom: rawMaterialData.nom,
        prix_unitaire: rawMaterialData.prix_unitaire,
        stock_min: rawMaterialData.stock_min,
        stock_actuel: rawMaterialData.stock_actuel
      })
    })
    
    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.message || 'Erreur lors de la création de la matière première')
    }
    
    return response.json()
  },

  async update(id, rawMaterialData, token) {
    const response = await fetch(rawMaterialEndpoints.update(id), {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`,
      },
      body: JSON.stringify({
        nom: rawMaterialData.nom,
        prix_unitaire: rawMaterialData.prix_unitaire,
        stock_min: rawMaterialData.stock_min,
        stock_actuel: rawMaterialData.stock_actuel
      })
    })
    
    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.message || 'Erreur lors de la modification de la matière première')
    }
    
    return response.json()
  },

  async delete(id, token) {
    const response = await fetch(rawMaterialEndpoints.delete(id), {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
      }
    })
    
    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.message || 'Erreur lors de la suppression de la matière première')
    }
    
    return response.json()
  }
}