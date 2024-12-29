import { createRouter, createWebHistory } from 'vue-router'

import { useAuthStore } from '@/stores/AuthStore'

import Home from '../pages/Home.vue'
import About from '../pages/About.vue'
import Auth from '../pages/Auth.vue'
import Profile from '../pages/Profile.vue'

const routes = [
  { path: '/', name: 'Home', component: Home },
  { path: '/about', name: 'About', component: About },
  { path: '/auth', name: 'Auth', component: Auth },
  { path: '/profile', name: 'Profile', component: Profile, meta: { requiresAuth: true } },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: routes,
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next('/auth') // Перенаправление на авторизацию
  } else {
    next() // Разрешить переход
  }
})

export default router
