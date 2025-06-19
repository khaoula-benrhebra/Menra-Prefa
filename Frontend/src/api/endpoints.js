const API_BASE_URL = 'http://localhost:8000/api'

export const endpoints = {
  auth: {
    login: `${API_BASE_URL}/login`,
    logout: `${API_BASE_URL}/auth/logout`,
    me: `${API_BASE_URL}/auth/me`
  }
}


export const authAPI = {
  async login(credentials) {
    const response = await fetch(endpoints.auth.login, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(credentials)
    })
    
    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.message || 'Erreur de connexion')
    }
    
    return response.json()
  },

  async logout(token) {
    const response = await fetch(endpoints.auth.logout, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
      }
    })
    
    if (!response.ok) {
      throw new Error('Erreur lors de la déconnexion')
    }
    
    return response.json()
  },

  async me(token) {
    const response = await fetch(endpoints.auth.me, {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
      }
    })
    
    if (!response.ok) {
      throw new Error('Erreur lors de la récupération du profil')
    }
    
    return response.json()
  }
}