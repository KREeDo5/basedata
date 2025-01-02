<script setup>
import { ref, onMounted } from 'vue'

import HeaderBar from '@/components/HeaderBar.vue'
import CategoryBlock from '@/components/CategoryBlock.vue'
import ThreadsBlock from '@/components/ThreadsBlock.vue'
import Loader from '@/components/Loader.vue'

import { useHomeStore } from '@/stores/HomeStore'

const homeStore = useHomeStore()
const isLoading = ref(true)

const sortOptions = [
  { value: 'date', text: 'По дате создания треда' },
  { value: 'count', text: 'По количеству сообщений' },
]

onMounted(async () => {
  isLoading.value = true
  await Promise.all([homeStore.fetchThreads(), homeStore.fetchCategories()])
  isLoading.value = false
})
</script>

<template>
  <HeaderBar />
  <div>
    <div v-if="isLoading" class="fixed inset-0 flex items-center justify-center">
      <Loader class="mt-20" />
    </div>
    <div v-else class="max-w-[1520px] w-full mx-auto mt-10 py-5 flex">
      <CategoryBlock :categoryList="homeStore.categories" />
      <div class="w-full">
        <div class="flex bg-base-darkgrey rounded-[20px] py-3 px-4 mb-5">
          <form class="mr-3">
            <select
              id="sortType"
              class="w-[260px] p-2.5 block bg-base-grey text-base-grey2 border border-base-grey text-base rounded-[12px] focus:border-blue-500"
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
        <ThreadsBlock :threadList="homeStore.threads" />
      </div>
    </div>
  </div>
</template>

<style></style>
