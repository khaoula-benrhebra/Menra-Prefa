const API_BASE_URL = 'http://localhost:8000/api'

export const categoryEndpoints = {
  list: `${API_BASE_URL}/public/categories`,
  show: (id) => `${API_BASE_URL}/public/categories/${id}`,
  create: `${API_BASE_URL}/categories`,
  update: (id) => `${API_BASE_URL}/categories/${id}`,
  delete: (id) => `${API_BASE_URL}/categories/${id}`
}

export const categoryAPI = {
  async getAll(token = null) {
    const headers = {
      'Content-Type': 'application/json',
    }
    
    const response = await fetch(categoryEndpoints.list, {
      headers
    })
    
    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.message || 'Erreur lors de la récupération des catégories')
    }
    
    return response.json()
  },

  async getById(id, token = null) {
    const headers = {
      'Content-Type': 'application/json',
    }
    
    if (token) {
      headers['Authorization'] = `Bearer ${token}`
    }
    
    const response = await fetch(categoryEndpoints.show(id), {
      headers
    })
    
    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.message || 'Erreur lors de la récupération de la catégorie')
    }
    
    return response.json()
  },

  async create(categoryData, token) {
    const response = await fetch(categoryEndpoints.create, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        nom: categoryData.name,
        description: categoryData.description
      })
    })
    
    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.message || 'Erreur lors de la création de la catégorie')
    }
    
    return response.json()
  },

  async update(id, categoryData, token) {
    const response = await fetch(categoryEndpoints.update(id), {
      method: 'PUT',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        nom: categoryData.name,
        description: categoryData.description
      })
    })
    
    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.message || 'Erreur lors de la modification de la catégorie')
    }
    
    return response.json()
  },

  async delete(id, token) {
    const response = await fetch(categoryEndpoints.delete(id), {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
      }
    })
    
    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.message || 'Erreur lors de la suppression de la catégorie')
    }
    
    return response.json()
  }
}