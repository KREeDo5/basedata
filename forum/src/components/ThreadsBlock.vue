<script setup>
import { defineProps } from 'vue'

import ThreadItem from '../components/ThreadItem.vue'

const props = defineProps({
  threadList: {
    type: Array,
    required: true,
  },
})
</script>

<template>
  <div class="h-min bg-base-darkgrey rounded-[20px] p-6">
    <div class="space-y-4">
      <div v-if="threadList.length === 0">Нет доступных тредов.</div>
      <div v-else>
        <ThreadItem
          v-for="thread in threadList"
          :key="thread.id"
          :title="thread.title"
          :isClosed="thread.isClosed"
          :commentsCount="thread.commentsCount"
          :userAvatar="thread.userAvatar"
          :userName="thread.userName"
          :registrationDate="thread.registarionDate"
        />
      </div>
    </div>
  </div>
</template>

<style scoped></style>
