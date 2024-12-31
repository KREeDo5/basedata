import { defineStore } from 'pinia'
import { ref, onMounted } from 'vue'
import axios from 'axios'

export const useHomeStore = defineStore('homeStore', () => {
  const threads = ref([])
  const categories = ref([])

  const fetchThreads = async () => {
    try {
      const response = await axios.get('http://79.137.184.176:8001/threads')
      threads.value = response.data
    } catch (error) {
      console.error('Error fetching threads:', error)
    }
  }

  const fetchCategories = async () => {
    try {
      const response = await axios.get('http://79.137.184.176:8001/categories')
      categories.value = response.data
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
    fetchCategories
  }
})