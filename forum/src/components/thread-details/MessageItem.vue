<script setup>
import UserInfo from '@/components/UserInfo.vue'

const props = defineProps({
  id: Number,
  text: String,
  createdAt: String,
  user: Object,
  images: Array,
})
</script>

<template>
  <div class="">
    <hr class="border-t border-base-grey" />
    <UserInfo class="pl-5 py-3" :user="user" :createdAt="createdAt" />
    <div class="px-5 pb-3 text-white">
      {{ text }}
    </div>
  </div>
</template>
