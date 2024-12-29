<script setup>
import { defineProps } from 'vue'
import Avatar from './Avatar.vue'

const props = defineProps({
  title: String,
  isClosed: Boolean,
  commentsCount: Number,
  userAvatar: String,
  userName: String,
  registrationDate: String,
})
</script>

<template>
  <div class="flex justify-between items-center p-4 rounded-[12px] hover:bg-base-grey">
    <div class="about-thread-block">
      <div class="flex mb-[10px]">
        <div class="text-xl font-w600 text-white">{{ title }}</div>
        <div v-if="isClosed"><img src="/closed.png" alt="icon" class="ml-2 h-6" /></div>
      </div>
      <div class="flex items-center">
        <img src="/comments.png" alt="icon" class="mr-2 h-5" />
        <div class="text-base-grey2">{{ commentsCount }}</div>
      </div>
    </div>
    <div class="flex items-center author-block w-[225px]">
      <Avatar class="mr-3" :src="userAvatar" size="medium" />
      <div>
        <div class="text-base font-w600 text-base-blue">{{ userName || 'без имени' }}</div>
        <div class="text-white">{{ registrationDate || '01.01.2000' }}</div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
