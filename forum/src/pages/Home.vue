<script setup>
import { ref } from 'vue'

import HeaderBar from '../components/HeaderBar.vue'

const isAuthenticated = ref(false) // Заменить для переключения авторизован/неавторизован
const userName = ref('Иван Иванов') // Пример
const userAvatar = ref('path/to/avatar.jpg') // Пример
</script>

<template>
  <HeaderBar :isAuthenticated="isAuthenticated" :userName="userName" :userAvatar="userAvatar" />
  <div class="home">
    <h1>This is an home page</h1>
  </div>
</template>

<style></style>
