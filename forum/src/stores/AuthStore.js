import { defineStore } from 'pinia'
import { ref, computed, watchEffect } from 'vue'
import { emitter } from '@/eventBus'
import axios from 'axios'

export const useAuthStore = defineStore('authStore', () => {
  const user = ref(null) // Профиль пользователя
  const subscriptions = ref([])
  const subscribers = ref([])
  const token = ref(localStorage.getItem('auth_token')) // Токен авторизации

  const isAuthenticated = computed(() => !!token.value) // Авторизован ли пользователь

  const setUser = (userData) => {
    user.value = userData.user
    user.value.id = localStorage.getItem('auth_token') // TODO: заменить на id пользователя
    subscriptions.value = userData.subscriptions
    subscribers.value = userData.subscribers
    emitter.emit('user-updated', userData)
  }

  const logout = () => {
    user.value = null
    emitter.emit('user-updated', null)
    localStorage.removeItem('auth_token')
    token.value = null // Обновляем реактивное значение
  }

  const register = async (form) => {
    try {
      const response = await axios.post('https://forum.kreedo.tech:8443/registration', form)
      const meta = response.data.meta
      if (meta.success) {
        const newToken = '9' // TODO: обработка токена
        localStorage.setItem('auth_token', newToken)
        token.value = newToken // Обновляем реактивное значение
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
        const newToken = '9' // TODO: обработка токена
        localStorage.setItem('auth_token', newToken)
        token.value = newToken // Обновляем реактивное значение
      } else {
        throw new Error(meta.error || 'Login failed')
      }
    } catch (error) {
      throw new Error(error.response?.data?.meta?.error || error.message)
    }
  }

  const loadUserFromToken = async () => {
    const storedToken = localStorage.getItem('auth_token')
    if (storedToken) {
      try {
        const response = await axios.get('https://forum.kreedo.tech:8443/user', {
          headers: {
            id: storedToken,
          },
        })
        const meta = response.data.meta
        const data = response.data.data
        if (meta.success && data.userData) {
          setUser(data.userData)
        } else {
          throw new Error(meta.error || 'Ошибка получения профиля')
        }
      } catch (error) {
        throw new Error(error.response?.data?.meta?.error || error.message)
      }
    } else {
      console.log('No token found')
    }
  }

  const editProfile = async (form) => {
    try {
      const response = await axios.post('https://forum.kreedo.tech:8443/change/profile', form)
      const meta = response.data.meta
      if (meta.success) {
        loadUserFromToken()
      } else {
        throw new Error(meta.error || 'Login failed')
      }
    } catch (error) {
      throw new Error(error.response?.data?.meta?.error || error.message)
    }
  }

  // Слушаем изменения localStorage
  const syncToken = () => {
    const storedToken = localStorage.getItem('auth_token')
    if (token.value !== storedToken) {
      token.value = storedToken
    }
  }

  window.addEventListener('storage', syncToken)

  // Следим за токеном при загрузке страницы
  watchEffect(() => {
    token.value = localStorage.getItem('auth_token')
  })

  return {
    user,
    isAuthenticated,
    setUser,
    logout,
    register,
    login,
    subscriptions,
    subscribers,
    loadUserFromToken,
    token,
    editProfile,
  }
})
