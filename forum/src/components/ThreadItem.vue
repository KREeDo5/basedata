<script setup>
import { defineProps } from 'vue'
const props = defineProps({
  title: {
    type: String,
    required: true,
  },
  isClosed: {
    type: Boolean,
    required: true,
  },
  commentsCount: {
    type: Number,
    required: true,
  },
})
</script>

<template>
  <div class="flex items-center p-4 rounded-[12px] hover:bg-base-grey">
    <div class="left-block">
      <div class="flex mb-[10px]">
        <div class="text-xl font-w600 text-white">{{ title }}</div>
        <div v-if="isClosed"><img src="/closed.png" alt="icon" class="ml-2 h-6" /></div>
      </div>
      <div class="flex items-center">
        <img src="/comments.png" alt="icon" class="mr-2 h-5" />
        <div class="text-base-grey2">{{ commentsCount }}</div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
