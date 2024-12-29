<script setup>
import { ref } from 'vue'

import HeaderBar from '../components/HeaderBar.vue'
import CategoryBlock from '../components/CategoryBlock.vue'
import ThreadsBlock from '../components/ThreadsBlock.vue'

import { useThreadStore } from '@/stores/ThreadStore'
import { useCategoryStore } from '@/stores/CategoryStore'

const threadListStore = useThreadStore()
const categoryListStore = useCategoryStore()

const threads = ref(threadListStore.threads)
const categories = ref(categoryListStore.categories)

const sortOptions = [
  { value: 'date', text: 'По дате создания треда' },
  { value: 'count', text: 'По количеству сообщений' },
]
</script>

<template>
  <HeaderBar/>
  <div class="max-w-[1520px] w-full mx-auto mt-10 py-5 flex">
    <CategoryBlock :categoryList="categories" />
    <div class="w-full">
      <div class="bg-base-darkgrey rounded-[20px] py-3 px-4 mb-5">
        <form>
          <select
            id="sortType"
            class="w-[260px] bg-base-grey text-base-grey2 border border-base-grey text-base rounded-[12px] focus:border-blue-500 block p-2.5"
          >
            <option v-for="option in sortOptions" :key="option.value" :value="option.value">
              {{ option.text }}
            </option>
          </select>
        </form>
      </div>
      <ThreadsBlock :threadList="threads" />
    </div>
  </div>
</template>

<style></style>
