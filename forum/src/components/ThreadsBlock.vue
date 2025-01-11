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
          :id="thread.id"
          :title="thread.title"
          :isClosed="thread.isClosed"
          :commentsCount="thread.commentsCount"
          :userAvatar="
            thread.user?.image_path
              ? `https://forum.kreedo.tech:8443/${thread.user?.image_path}`
              : null
          "
          :userName="thread.user?.name"
          :created_at="thread.created_at"
          :userId="thread.user?.id"
        />
      </div>
    </div>
  </div>
</template>

<style scoped></style>
