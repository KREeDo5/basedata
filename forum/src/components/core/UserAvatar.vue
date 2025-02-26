<script setup>
import { computed } from 'vue'

import SafeNetworkImage from './SafeNetworkImage.vue'

const props = defineProps({
  url: {
    type: String,
    default: '',
  },
  size: {
    type: String,
    default: 'medium',
    validator: (value) => ['big', 'medium', 'small'].includes(value),
  },
})

const avatarClass = computed(() => {
  switch (props.size) {
    case 'big':
      return 'w-full h-[300px] bg-white object-cover rounded-2xl'
    case 'medium':
      return 'w-[60px] h-[60px] bg-white object-cover rounded-full border-[1px] border-white'
    case 'small':
      return 'w-[36px] h-[36px] bg-white object-cover rounded-full border-[1px] border-white'
    default:
      return ''
  }
})
</script>

<template>
  <SafeNetworkImage :url="url" :class="avatarClass" :isUser="true" />
</template>
