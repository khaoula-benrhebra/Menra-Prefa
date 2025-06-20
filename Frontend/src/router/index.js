import { createRouter, createWebHistory } from 'vue-router'
import { useAuth } from '@/composables/useAuth'
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import DashboardView from '../views/DashboardView.vue'
import DashboardClientView from '../views/DashboardClient.vue'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: HomeView
  },
  {
    path: '/login',
    name: 'Login',
    component: LoginView,
    meta: { requiresGuest: true }
  },
  {
    path: '/register',
    name: 'Register',
    component: RegisterView,
    meta: { requiresGuest: true }
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: DashboardView,
    meta: { requiresAuth: true, requiresAdmin: true }
  },
  {
    path: '/dashboardClient',
    name: 'DashboardClient',
    component: DashboardClientView,
    meta: { requiresAuth: true, requiresClient: true }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Guards de navigation
router.beforeEach(async (to, from, next) => {
  const { isAuthenticated, isAdmin, isClient, checkAuth } = useAuth()
  
  // Vérifier l'authentification si un token existe
  if (localStorage.getItem('auth_token') && !isAuthenticated.value) {
    await checkAuth()
  }
  
  // Rediriger les utilisateurs connectés loin des pages de connexion/inscription
  if (to.meta.requiresGuest && isAuthenticated.value) {
    if (isAdmin.value) {
      next('/dashboard')
    } else if (isClient.value) {
      next('/dashboardClient')
    } else {
      next('/')
    }
    return
  }
  
  // Vérifier l'authentification requise
  if (to.meta.requiresAuth && !isAuthenticated.value) {
    next('/login')
    return
  }
  
  // Vérifier les permissions admin
  if (to.meta.requiresAdmin && !isAdmin.value) {
    next('/')
    return
  }

  // Vérifier les permissions client
  if (to.meta.requiresClient && !isClient.value) {
    next('/')
    return
  }
  
  next()
})

export default router