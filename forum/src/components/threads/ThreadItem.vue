<script setup>
import { defineProps } from 'vue'
import { useRouter } from 'vue-router'
import UserInfo from '@/components/UserInfo.vue';

const props = defineProps({
  id: Number,
  title: String,
  isClosed: Boolean,
  commentsCount: Number,
  user: Object,
  created_at: String,
})

const router = useRouter()

const goToThread = () => {
  router.push({ path: '/thread', query: { threadId: props.id } })
}
</script>

<template>
  <div
    class="flex justify-between items-center p-4 rounded-[12px] hover:bg-base-grey"
    @click="goToThread"
  >
    <div class="about-thread-block">
      <div class="flex mb-[10px]">
        <div class="text-xl font-w600 text-white">{{ title }}</div>
        <div v-if="isClosed">
          <img src="/closed.png" alt="icon" class="ml-2 h-6" />
        </div>
      </div>
      <div class="flex items-center">
        <img src="/comments.png" alt="icon" class="mr-2 h-5" />
        <div class="text-base-grey2">{{ commentsCount || 0 }}</div>
      </div>
    </div>
    <UserInfo :user="user" :createdAt="created_at"/>
  </div>
</template>

<style scoped></style>
