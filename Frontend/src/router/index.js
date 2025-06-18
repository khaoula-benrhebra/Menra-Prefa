import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import DashboardView from '@/views/DashboardView.vue'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: HomeView
  },
  {
    path: '/login',
    name: 'Login',
    component: LoginView
  },
  {
    path: '/register',
    name: 'Register',
    component: RegisterView
  } ,

    {
    path: '/dashboard',
    name: 'Dashboard',
    component: DashboardView,
    // meta: { requiresAuth: true } // si vous avez un système d'authentification
  }

  // Ajoutez d'autres routes ici au fur et à mesure
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router