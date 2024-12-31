import { defineStore } from 'pinia'
import { ref, onMounted } from 'vue'
import axios from 'axios'

export const useHomeStore = defineStore('homeStore', () => {
  const threads = ref([])
  const categories = ref([])

  const fetchThreads = async () => {
    try {
      const response = await axios.get('/threads')
      if (Array.isArray(response.data)) {
        threads.value = response.data.map((item) => item.thread)
      } else {
        console.error('Unexpected response data format for threads:', response.data)
      }
    } catch (error) {
      console.error('Error fetching threads:', error)
    }
  }

  const fetchCategories = async () => {
    try {
      const response = await axios.get('/categories')
      if (Array.isArray(response.data)) {
        categories.value = response.data.map((item) => item.category)
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
