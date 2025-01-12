<script setup>
import { defineProps } from 'vue'

import ThreadItem from './ThreadItem.vue'

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
          :id="thread.id"
          :title="thread.title"
          :isClosed="thread.isClosed"
          :commentsCount="thread.commentsCount"
          :user="thread.user"
          :created_at="thread.created_at"
        />
      </div>
    </div>
  </div>
</template>

<style scoped></style>
