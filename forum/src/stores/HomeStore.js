import { defineStore } from 'pinia'
import { ref, onMounted } from 'vue'
import axios from 'axios'

export const useHomeStore = defineStore('homeStore', () => {
  const threads = ref([])
  const categories = ref([])

  const fetchThreads = async () => {
    try {
      const response = await axios.get('https://forum.kreedo.tech:8443/threads')
      console.log(response)
      if (response.data.meta.success && Array.isArray(response.data.data.threads)) {
        threads.value = response.data.data.threads
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
      console.log(response)
      if (response.data.meta.success && Array.isArray(response.data.data.categories)) {
        categories.value = response.data.data.categories
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
