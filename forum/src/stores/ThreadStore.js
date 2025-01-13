import { defineStore } from 'pinia'
import { ref } from 'vue'
import axios from 'axios'

export const useThreadStore = defineStore('threadStore', () => {
  const title = ref('')
  const text = ref('')
  const threadAuthor = ref(null)
  const threadMessages = ref([])
  const images = ref([])
  const createdAt = ref(null)
  const isClosed = ref(false)

  const fetchThreadDetails = async (threadId) => {
    try {
      const response = await axios.get('https://forum.kreedo.tech:8443/thread', {
        headers: {
          id: threadId,
        },
      })
      const meta = response.data.meta
      const data = response.data.data
      if (meta.success && data.threadInfo) {
        title.value = data.threadInfo.title
        text.value = data.threadInfo.text
        createdAt.value = data.threadInfo.created_at
        threadAuthor.value = data.threadInfo.user
        threadMessages.value = data.threadInfo.messages
        isClosed.value = data.threadInfo.isClosed
        images.value = data.threadInfo.images
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
        if (Array.isArray(form[key])) {
          form[key].forEach((file, index) => {
            formData.append(`${key}[${index}]`, file)
          })
        } else {
          formData.append(key, form[key])
        }
      }

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

  const closeThread = async (form) => {
    try {
      const response = await axios.post('https://forum.kreedo.tech:8443/closeThread', form)
      const meta = response.data.meta
      if (!meta.success) {
        throw new Error(meta.error || 'Close thread failed')
      }
    } catch (error) {
      throw new Error(error.response?.data?.meta?.error || error.message)
    }
  }

  return {
    fetchThreadDetails,
    clearStore,
    sendMessage,
    closeThread,
    title,
    text,
    threadAuthor,
    threadMessages,
    createdAt,
    isClosed,
    images,
  }
})
