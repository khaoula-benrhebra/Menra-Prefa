const API_BASE_URL = 'http://localhost:8000/api'

export const productEndpoints = {
  list: `${API_BASE_URL}/public/products`,
  show: (id) => `${API_BASE_URL}/public/products/${id}`,
  create: `${API_BASE_URL}/products`,
  update: (id) => `${API_BASE_URL}/products/${id}`,
  delete: (id) => `${API_BASE_URL}/products/${id}`
}

export const productsAPI = {
  async getAll(token = null) {
    const headers = {
      'Content-Type': 'application/json',
    }
    
    const response = await fetch(productEndpoints.list, {
      headers
    })
    
    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.message || 'Erreur lors de la récupération des produits')
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
    
    const response = await fetch(productEndpoints.show(id), {
      headers
    })
    
    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.message || 'Erreur lors de la récupération du produit')
    }
    
    return response.json()
  },

  async create(productData, token) {
    const formData = new FormData()
    formData.append('nom', productData.name)
    formData.append('description', productData.description || '')
    formData.append('prix_unitaire', productData.price)
    formData.append('stock_actuel', productData.stock_actuel)
    formData.append('stock_min', productData.stock_min)
    formData.append('category_id', productData.category_id)
    
    // Correction: utiliser rawMaterials au lieu de raw_materials
    if (productData.rawMaterials && productData.rawMaterials.length > 0) {
      productData.rawMaterials.forEach((rawMaterial, index) => {
        formData.append(`raw_materials[${index}][id]`, rawMaterial.id)
        formData.append(`raw_materials[${index}][quantite_par_unite]`, rawMaterial.quantite_par_unite)
      })
    }
    
    if (productData.imageFile) {
      formData.append('image', productData.imageFile)
    }
    
    const response = await fetch(productEndpoints.create, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
      },
      body: formData
    })
    
    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.message || 'Erreur lors de la création du produit')
    }
    
    return response.json()
  },

  async update(id, productData, token) {
    const formData = new FormData()
    formData.append('nom', productData.name)
    formData.append('description', productData.description || '')
    formData.append('prix_unitaire', productData.price)
    formData.append('stock_actuel', productData.stock_actuel)
    formData.append('stock_min', productData.stock_min)
    formData.append('category_id', productData.category_id)
    formData.append('_method', 'PUT')
    
    // Correction: utiliser rawMaterials au lieu de raw_materials
    if (productData.rawMaterials && productData.rawMaterials.length > 0) {
      productData.rawMaterials.forEach((rawMaterial, index) => {
        formData.append(`raw_materials[${index}][id]`, rawMaterial.id)
        formData.append(`raw_materials[${index}][quantite_par_unite]`, rawMaterial.quantite_par_unite)
      })
    }
    
    if (productData.imageFile) {
      formData.append('image', productData.imageFile)
    }
    
    const response = await fetch(productEndpoints.update(id), {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
      },
      body: formData
    })
    
    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.message || 'Erreur lors de la modification du produit')
    }
    
    return response.json()
  },

  async delete(id, token) {
    const response = await fetch(productEndpoints.delete(id), {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
      }
    })
    
    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.message || 'Erreur lors de la suppression du produit')
    }
    
    return response.json()
  }
}