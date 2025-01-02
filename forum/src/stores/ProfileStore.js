import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'

export const useProfileStore = defineStore('profileStore', () => {
  const userProfile = ref(null)
  const subscriptions = ref([])
  const subscribers = ref([])

  const fetchUserProfile = async (userId) => {
    try {
      const response = await axios.get('https://forum.kreedo.tech:8443/user', {
        headers: {
          'id': userId,
        },
      })
      const meta = response.data.meta
      const data = response.data.data
      if (meta.success && data.userData) {
        userProfile.value = data.userData.user
        subscriptions.value = data.userData.subscriptions
        subscribers.value = data.userData.subscribers
      } else {
        console.error('Unexpected response data format for user profile:', response.data)
      }
    } catch (error) {
      console.error('Error fetching user profile:', error)
    }
  }

  return {
    userProfile,
    subscriptions,
    subscribers,
    fetchUserProfile,
  }
})
