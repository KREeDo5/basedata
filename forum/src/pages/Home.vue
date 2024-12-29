<script setup>
import { ref } from 'vue'

import HeaderBar from '../components/HeaderBar.vue'
import CategoryBlock from '../components/CategoryBlock.vue'
import ThreadsBlock from '../components/ThreadsBlock.vue'

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
  <div class="max-w-[1520px] w-full mx-auto mt-10 py-5 flex">
    <CategoryBlock />
    <div class="w-full">
      <div class="bg-base-darkgrey rounded-[20px] py-3 px-4 mb-5">по дате создания треда</div>
      <ThreadsBlock />
    </div>
  </div>
</template>

<style></style>
