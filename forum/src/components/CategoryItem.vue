<script setup>
import { defineProps } from 'vue'
const props = defineProps({
  title: String,
  hasSubcategories: Boolean,
  parentCategoryId: Number,
})
</script>

<template>
  <div
    class="flex justify-between items-center py-2 pl-3 pr-2 rounded-[20px] hover:bg-base-grey group"
  >
    <div class="flex">
      <img src="/category.png" alt="icon" class="mr-2 h-7" />
      <div class="text-base text-base-grey2">{{ title }}</div>
    </div>
    <svg
      v-if="hasSubcategories"
      class="w-4 h-6 text-base-grey2 opacity-0 group-hover:opacity-100"
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 10 6"
    >
      <path
        stroke="currentColor"
        stroke-linecap="round"
        stroke-linejoin="round"
        stroke-width="1"
        d="m1 1 4 4 4-4"
      />
    </svg>
  </div>
</template>

<style scoped></style>
