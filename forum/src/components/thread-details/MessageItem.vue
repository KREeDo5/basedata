<script setup>
import UserInfo from '@/components/UserInfo.vue'
import ThreadImages from '@/components/thread-details/ThreadImages.vue'

const props = defineProps({
  id: Number,
  text: String,
  createdAt: String,
  user: Object,
  images: Array,
})
</script>

<template>

    <hr class="border-t border-base-grey" />
    <UserInfo class="pl-5 py-3" :user="user" :createdAt="createdAt" />
    <div class="px-5 pb-3 text-white break-words whitespace-pre-wrap">
      {{ text }}
    </div>
    <ThreadImages :images="images" />
</template>
