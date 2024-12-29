import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useAuthStore = defineStore('authStore', () => {
  const user = ref(null) // Профиль пользователя

  const isAuthenticated = computed(() => !!user.value) // Авторизован ли пользователь

  const setUser = (userData) => {
    user.value = userData
  }

  const logout = () => {
    user.value = null
  }

  const register = async (userData) => {
    setUser(userData)
  }

  const login = async (userData) => {
    setUser(userData)
  }

  return {
    user,
    isAuthenticated,
    setUser,
    logout,
    register,
    login,
  }
})
