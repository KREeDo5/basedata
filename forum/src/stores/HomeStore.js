import { defineStore } from 'pinia'
import { ref, onMounted } from 'vue'
import axios from 'axios'

export const useHomeStore = defineStore('homeStore', () => {
  const threads = ref([])
  const categories = ref([])

  const fetchThreads = async () => {
    try {
      const response = await axios.get('https://forum.kreedo.tech:8443/threads')
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

  onMounted(() => {
    fetchThreads()
    fetchCategories()
  })

  return {
    threads,
    categories,
    fetchThreads,
    fetchCategories,
  }
})
