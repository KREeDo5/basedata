import { defineStore } from 'pinia'
import { ref, onMounted } from 'vue'
import axios from 'axios'

export const useHomeStore = defineStore('homeStore', () => {
  const threads = ref([])
  const categories = ref([])

  const fetchThreads = async (categoryId, sortField) => {
    try {
      const response = await axios.get('https://forum.kreedo.tech:8443/threads', {
        headers: {
          categoryId: categoryId,
          sortField: sortField,
        },
      })
      const meta = response.data.meta
      const data = response.data.data
      if (meta.success && Array.isArray(data.threads)) {
        threads.value = data.threads
      } else {
        console.error('Unexpected response data format for threads:', response.data)
      }
    } catch (error) {
      console.error('Error fetching threads:', error)
    }
  }

  const fetchCategories = async () => {
    try {
      const response = await axios.get('https://forum.kreedo.tech:8443/categories')
      const meta = response.data.meta
      const data = response.data.data
      if (meta.success && Array.isArray(data.categories)) {
        categories.value = data.categories
      } else {
        console.error('Unexpected response data format for categories:', response.data)
      }
    } catch (error) {
      console.error('Error fetching categories:', error)
    }
  }

  const createThread = async (form) => {
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

      const response = await axios.post('https://forum.kreedo.tech:8443/create/thread', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })

      const meta = response.data.meta
      if (meta.success) {
        console.log('Thread created')
      } else {
        throw new Error(meta.error || 'Create thread failed')
      }
    } catch (error) {
      throw new Error(error.response?.data?.meta?.error || error.message)
    }
  }


  onMounted(() => {
    fetchThreads()
    fetchCategories()
  })

  const clearThreads = async () => {
    threads.value = []
  }

  return {
    threads,
    categories,
    fetchThreads,
    fetchCategories,
    createThread,
    clearThreads,
  }
})
