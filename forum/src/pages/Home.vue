<script setup>
import { ref } from 'vue'

import HeaderBar from '../components/HeaderBar.vue'

const isAuthenticated = ref(false) // Заменить для переключения авторизован/неавторизован
const userName = ref('Иван Иванов') // Пример
const userAvatar = ref('') // Пример

const toggleAuth = () => {
  //временное решение до реализации получения данных об авторизации пользователя
  isAuthenticated.value = !isAuthenticated.value
}
</script>

<template>
  <!-- @toggle-auth="toggleAuth" - временное решение до реализации получения данных об авторизации пользователя -->
  <HeaderBar
    :isAuthenticated="isAuthenticated"
    :userName="userName"
    :userAvatar="userAvatar"
    @toggle-auth="toggleAuth"
  />
  <div class="home">
    <h1>This is an home page</h1>
  </div>
</template>

<style></style>
