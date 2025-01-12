import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'

export const useThreadStore = defineStore('threadStore', () => {
  const title = ref('')
  const text = ref('')
  const threadAuthor = ref(null)
  const threadMessages = ref([])
  const createdAt = ref(null)

  const fetchThreadDetails = async (threadId) => {
    try {
      const response = await axios.get('https://forum.kreedo.tech:8443/thread', {
        headers: {
          id: threadId,
        },
      })
      const meta = response.data.meta
      const data = response.data.data
      if (meta.success && data.threadData) {
        title.value = data.threadData.title
        text.value = data.threadData.text
        createdAt.value = data.threadData.created_at
        threadAuthor.value = data.threadData.user
        threadMessages.value = data.threadData.messages
      } else {
        console.error('Unexpected response data format for thread details:', response.data)
      }
    } catch (error) {
      console.error('Error fetching thread details:', error)
    }
  }

  const sendMessage = async (form) => {
    try {
      const formData = new FormData()
      for (const key in form) {
        formData.append(key, form[key])
      }

      formData.forEach((value, key) => {
        console.log(`${key}: ${value}`)
      })

      const response = await axios.post('https://forum.kreedo.tech:8443/create/message', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })

      const meta = response.data.meta
      if (meta.success) {
        console.log('Message sent')
      } else {
        throw new Error(meta.error || 'Send message failed')
      }
    } catch (error) {
      throw new Error(error.response?.data?.meta?.error || error.message)
    }
  }

  const clearStore = () => {
    title.value = null
    text.value = ''
    threadAuthor.value = null
    threadMessages.value = []
    createdAt.value = null
  }

  return {
    fetchThreadDetails,
    clearStore,
    sendMessage,
    title,
    text,
    threadAuthor,
    threadMessages,
    createdAt,
  }
})
