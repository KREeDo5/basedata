<script setup>
import SafeNetworkImage from '@/components/core/SafeNetworkImage.vue'

const props = defineProps({
  images: {
    type: Array,
  },
})
</script>

<template>
  <div class="px-5 pb-3 flex flex-wrap gap-2">
    <SafeNetworkImage
      v-for="(image, index) in props.images"
      :key="index"
      :url="image"
      alt="image"
      class="max-h-[300px] object-cover rounded-lg"
    />
  </div>
</template>
