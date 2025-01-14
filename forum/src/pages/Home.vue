<script setup>
import { ref, onMounted, computed } from 'vue'

import HeaderBar from '@/components/HeaderBar.vue'
import CategoryBlock from '@/components/categories/CategoryBlock.vue'
import ThreadsBlock from '@/components/threads/ThreadsBlock.vue'
import Loader from '@/components/core/Loader.vue'
import CreateThread from '@/components/CreateThread.vue'
import Button from '@/components/core/Button.vue'

import { useHomeStore } from '@/stores/HomeStore'

const homeStore = useHomeStore()
const isLoading = ref(true)
const showModal = ref(false)
const currentCategoryId = ref(null)
const optionValue = ref(localStorage.getItem('optionValue') || 'created_at')

const sortOptions = [
  { value: 'created_at', text: 'По дате создания треда' },
  { value: 'messagesCount', text: 'По количеству сообщений' },
  { value: 'lastMessage', text: 'По последнему сообщению' },
]

const openCreateThreadModal = () => {
  showModal.value = true
}

const closeCreateThreadModal = () => {
  showModal.value = false
  updateThreadList()
}

const updateThreadList = async (categoryId = null) => {
  isLoading.value = true
  homeStore.clearThreads()
  console.log(optionValue.value)
  await homeStore.fetchThreads(categoryId, optionValue.value)
  currentCategoryId.value = categoryId
  isLoading.value = false
}

const currentCategoryName = computed(() => {
  if (currentCategoryId.value === null) return ''
  const category = homeStore.categories.find((cat) => cat.id === currentCategoryId.value)
  return category ? category.title : ''
})

const handleSortChange = (event) => {
  optionValue.value = event.target.value
  localStorage.setItem('optionValue', optionValue.value)
  updateThreadList(currentCategoryId.value)
}

onMounted(async () => {
  isLoading.value = true
  //await Promise.all([homeStore.fetchThreads(), homeStore.fetchCategories()])
  isLoading.value = false
})
</script>

<template>
  <HeaderBar />
  <div>
    <div v-if="isLoading" class="fixed inset-0 flex items-center justify-center">
      <Loader class="mt-20" />
    </div>
    <div v-else class="max-w-7xl w-full mx-auto mt-10 py-5 flex">
      <CategoryBlock
        :categoryList="homeStore.categories"
        @openCreateThreadModal="openCreateThreadModal"
        @updateThreadList="updateThreadList"
      />
      <div class="w-full">
        <div class="flex bg-base-darkgrey rounded-[20px] py-3 px-4 mb-5">
          <form class="mr-3">
            <select
              id="sortType"
              class="w-[260px] p-2.5 block bg-base-grey text-base-grey2 border border-base-grey text-base rounded-[12px] focus:border-blue-500"
              @change="handleSortChange"
              :value="optionValue"
            >
              <option v-for="option in sortOptions" :key="option.value" :value="option.value">
                {{ option.text }}
              </option>
            </select>
          </form>
          <form class="flex-1 relative">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
              <img src="/search.png" alt="search-icon" />
            </div>
            <input
              type="text"
              placeholder="Поиск тем"
              class="w-full p-2.5 ps-10 block bg-base-grey text-base-grey2 text-base rounded-[12px] focus:outline-none focus:ring-1 focus:ring-base-blue"
            />
          </form>
        </div>
        <div v-if="currentCategoryName" class="flex items-center mb-5">
          <Button
            text="&lt;"
            variant="rounded"
            @click="updateThreadList(null)"
            title="Вернуться на главную страницу"
          />
          <h2 class="text-2xl text-white ml-5">{{ currentCategoryName }}</h2>
        </div>
        <ThreadsBlock :threadList="homeStore.threads" />
      </div>
    </div>
    <CreateThread v-if="showModal" @closeCreateThreadModal="closeCreateThreadModal" />
  </div>
</template>

<style></style>
