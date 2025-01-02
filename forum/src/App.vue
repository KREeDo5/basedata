<script setup>
import { useAuthStore } from '@/stores/AuthStore'

const authStore = useAuthStore()
authStore.loadUserFromToken()
</script>

<template>
  <RouterView />
</template>
