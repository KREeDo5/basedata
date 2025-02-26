import { createRouter, createWebHistory } from 'vue-router'

import { useAuthStore } from '@/stores/AuthStore'

import HomePage from '@/pages/HomePage.vue'
import AboutPage from '@/pages/AboutPage.vue'
import AuthPage from '@/pages/AuthPage.vue'
import ProfilePage from '@/pages/ProfilePage.vue'
import ThreadPage from '@/pages/ThreadPage.vue'

const routes = [
  { path: '/', name: 'HomePage', component: HomePage },
  { path: '/about', name: 'AboutPage', component: AboutPage },
  { path: '/auth', name: 'AuthPage', component: AuthPage },
  { path: '/profile', name: 'ProfilePage', component: ProfilePage, meta: { requiresAuth: true }, props: route => ({ userId: route.query.userId }) },
  { path: '/thread', name: 'ThreadPage', component: ThreadPage, props: route => ({ threadId: route.query.threadId }) },
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
