<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/AuthStore'

const authStore = useAuthStore()
const isLoading = ref(true)

onMounted(async () => {
  await authStore.loadUserFromToken()
  isLoading.value = false
})
</script>

<template>
  <div v-if="isLoading" class="flex items-center justify-center h-screen text-base-gold">
    <p>Загружаем...</p>
  </div>
  <div v-else>
    <RouterView />
  </div>
</template>
