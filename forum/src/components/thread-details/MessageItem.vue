<script setup>
import UserInfo from '@/components/UserInfo.vue'
import SafeNetworkImage from '../core/SafeNetworkImage.vue';

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
    <div class="px-5 pb-3 flex flex-wrap gap-2">
      <SafeNetworkImage
        v-for="(image, index) in images"
        :key="index"
        :url="image"
        alt="message image"
        class="h-32 w-32 object-cover rounded-lg"
      />
    </div>
</template>
