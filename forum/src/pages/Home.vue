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

const threads = ref([
  {
    id: 1,
    title: 'Не видит видеокарту',
    isClosed: false,
    commentsCount: 6,
    userAvatar: '/path/to/avatar1.png',
    userName: 'User1',
    registarionDate: '01.01.2022',
  },
  {
    id: 2,
    title: 'Оцените сборку',
    isClosed: true,
    commentsCount: 231,
    userAvatar: '/path/to/avatar2.png',
    userName: 'Gabe Newell',
    registarionDate: '01.01.2020',
  },
])

const categories = ref([
  {
    id: 1,
    title: 'Лекции',
    hasSubcategories: 1,
    parentCategoryId: 0,
  },
  {
    id: 2,
    title: 'Компьютеры',
    hasSubcategories: 0,
    parentCategoryId: 0,
  },
  {
    id: 3,
    title: 'Лабораторные работы',
    hasSubcategories: 0,
    parentCategoryId: 0,
  },
  {
    id: 4,
    title: 'Практические занятия',
    hasSubcategories: 0,
    parentCategoryId: 0,
  },
  {
    id: 5,
    title: 'Стипендии',
    hasSubcategories: 0,
    parentCategoryId: 0,
  },
  {
    id: 6,
    title: 'ТАиФЯ',
    hasSubcategories: 0,
    parentCategoryId: 1,
  },
  {
    id: 7,
    title: 'Базы Данных',
    hasSubcategories: 0,
    parentCategoryId: 1,
  },
])
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
    <CategoryBlock :categoryList="categories" />
    <div class="w-full">
      <div class="bg-base-darkgrey rounded-[20px] py-3 px-4 mb-5">по дате создания треда</div>
      <ThreadsBlock :threadList="threads" />
    </div>
  </div>
</template>

<style></style>
