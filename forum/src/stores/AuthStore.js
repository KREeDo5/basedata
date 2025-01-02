import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { emitter } from '@/eventBus'
import axios from 'axios'

export const useAuthStore = defineStore('authStore', () => {
  const user = ref(null) // Профиль пользователя

  const isAuthenticated = computed(() => !!user.value) // Авторизован ли пользователь

  const setUser = (userData) => {
    user.value = userData
    emitter.emit('user-updated', userData)
  }

  const logout = () => {
    user.value = null
    emitter.emit('user-updated', null)
  }

  const register = async (form) => {
    try {
      const response = await axios.post('https://forum.kreedo.tech:8443/registration', form)
      const meta = response.data.meta
      if (meta.success) {
        setUser(form)
      } else {
        throw new Error(meta.error || 'Registration failed')
      }
    } catch (error) {
      throw new Error(error.response?.data?.meta?.error || error.message)
    }
  }

  const login = async (form) => {
    try {
      const response = await axios.post('https://forum.kreedo.tech:8443/auth', form)
      const meta = response.data.meta
      if (meta.success) {
        setUser(form)
      } else {
        throw new Error(meta.error || 'Login failed')
      }
    } catch (error) {
      throw new Error(error.response?.data?.meta?.error || error.message)
    }
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
