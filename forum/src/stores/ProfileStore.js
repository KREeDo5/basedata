import { defineStore } from 'pinia'
import { useAuthStore } from '@/stores/AuthStore'
import { ref } from 'vue'
import axios from 'axios'

export const useProfileStore = defineStore('profileStore', () => {
  const authStore = useAuthStore()
  const userProfile = ref(null)
  const subscriptions = ref([])
  const subscribers = ref([])
  const ownerId = localStorage.getItem('auth_token')

  const fetchUserProfile = async (userId) => {
    try {
      const response = await axios.get('https://forum.kreedo.tech:8443/user', {
        headers: {
          id: userId,
        },
      })
      const meta = response.data.meta
      const data = response.data.data
      if (meta.success && data.userData) {
        if (userId === ownerId) {
          authStore.setUser(data.userData)
        }
        userProfile.value = data.userData.user
        userProfile.value.image = userProfile.value.image_path
        subscriptions.value = data.userData.subscriptions
        subscribers.value = data.userData.subscribers
      } else {
        console.error('Unexpected response data format for user profile:', response.data)
      }
    } catch (error) {
      console.error('Error fetching user profile:', error)
    }
  }

  const subscribe = async (subscriptionId) => {
    try {
      const response = await axios.post('https://forum.kreedo.tech:8443/subscribe', {
        subscriber: ownerId,
        subscription: subscriptionId,
      })
      const meta = response.data.meta
      if (meta.success) {
        console.log('Subscribed successfully')
      } else {
        console.error('Subscription failed:', response.data)
      }
    } catch (error) {
      console.error('Error during subscription:', error)
    }
  }

  const unsubscribe = async (subscriptionId) => {
    try {
      const response = await axios.delete('https://forum.kreedo.tech:8443/unsubscribe', {
        data: {
          subscriber: ownerId,
          subscription: subscriptionId,
        },
      })
      const meta = response.data.meta
      if (meta.success) {
        console.log('Unsubscribed successfully')
      } else {
        console.error('Unsubscription failed:', response.data)
      }
    } catch (error) {
      console.error('Error during unsubscription:', error)
    }
  }

  const clearStore = () => {
    userProfile.value = null
    subscriptions.value = []
    subscribers.value = []
  }

  return {
    userProfile,
    subscriptions,
    subscribers,
    fetchUserProfile,
    subscribe,
    unsubscribe,
    clearStore,
  }
})
