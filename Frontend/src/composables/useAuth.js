import { ref, computed } from 'vue'
import { authAPI } from '@/api/endpoints'

// État global de l'authentification
const user = ref(null)
const token = ref(localStorage.getItem('auth_token'))
const isLoading = ref(false)

export function useAuth() {
  // Computed properties
  const isAuthenticated = computed(() => !!token.value && !!user.value)
  const isAdmin = computed(() => user.value?.role === 'Admin')
  const isClient = computed(() => user.value?.role === 'Client')

  // Méthodes
  const register = async (userData) => {
    try {
      isLoading.value = true
      const response = await authAPI.register(userData)
      return response
    } catch (error) {
      console.error('Erreur d\'inscription:', error)
      throw error
    } finally {
      isLoading.value = false
    }
  }

  const login = async (credentials) => {
    try {
      isLoading.value = true
      const response = await authAPI.login(credentials)
      
      // Stocker le token et les infos utilisateur
      token.value = response.token
      user.value = response.user
      localStorage.setItem('auth_token', response.token)
      
      return response
    } catch (error) {
      console.error('Erreur de connexion:', error)
      throw error
    } finally {
      isLoading.value = false
    }
  }

  const logout = async () => {
    try {
      if (token.value) {
        await authAPI.logout(token.value)
      }
    } catch (error) {
      console.error('Erreur lors de la déconnexion:', error)
    } finally {
      // Nettoyer l'état local dans tous les cas
      token.value = null
      user.value = null
      localStorage.removeItem('auth_token')
    }
  }

  const checkAuth = async () => {
    if (!token.value) return false
    
    try {
      const response = await authAPI.me(token.value)
      user.value = response.user
      return true
    } catch (error) {
      console.error('Token invalide:', error)
      // Token invalide, nettoyer
      token.value = null
      user.value = null
      localStorage.removeItem('auth_token')
      return false
    }
  }

  const resendVerificationEmail = async (email) => {
    try {
      isLoading.value = true
      const response = await authAPI.resendVerificationEmail(email)
      return response
    } catch (error) {
      console.error('Erreur renvoi email:', error)
      throw error
    } finally {
      isLoading.value = false
    }
  }

  return {
    // État
    user: computed(() => user.value),
    token: computed(() => token.value),
    isLoading: computed(() => isLoading.value),
    
    // Computed
    isAuthenticated,
    isAdmin,
    isClient,
    
    // Méthodes
    register,
    login,
    logout,
    checkAuth,
    resendVerificationEmail
  }
}