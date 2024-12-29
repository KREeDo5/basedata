<script setup>
import { defineProps } from 'vue'
const props = defineProps({
  title: {
    type: String,
    required: true,
  },
})
</script>

<template>
  <div class="flex justify-between items-center py-2 pl-3 pr-2 rounded-[20px] hover:bg-base-grey">
    <div class="flex">
      <img src="/category.png" alt="icon" class="mr-2 h-7" />
      <div class="text-base text-base-grey2">{{ title }}</div>
    </div>
    <img src="/comments.png" alt="icon" class="mr-2 h-7" />
  </div>
</template>

<style scoped></style>
