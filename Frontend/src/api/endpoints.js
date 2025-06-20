const API_BASE_URL = 'http://localhost:8000/api'

export const endpoints = {
  auth: {
    register: `${API_BASE_URL}/register`,
    login: `${API_BASE_URL}/login`,
    logout: `${API_BASE_URL}/auth/logout`,
    me: `${API_BASE_URL}/auth/me`,
    resendVerification: `${API_BASE_URL}/email/resend`
  }
}

export const authAPI = {
  async register(userData) {
    const response = await fetch(endpoints.auth.register, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        name: userData.nom,
        email: userData.email,
        phone: userData.telephone,
        password: userData.password
      })
    })
    
    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.message || 'Erreur lors de l\'inscription')
    }
    
    return response.json()
  },

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
  },

  async resendVerificationEmail(email) {
    const response = await fetch(endpoints.auth.resendVerification, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ email })
    })
    
    if (!response.ok) {
      const error = await response.json()
      throw new Error(error.message || 'Erreur lors du renvoi de l\'email')
    }
    
    return response.json()
  }
}