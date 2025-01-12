<script setup>
import SafeNetworkImage from '@/components/core/SafeNetworkImage.vue'

const props = defineProps({
  images: Array,
})

</script>

<template>
  <div class="flex">
    <SafeNetworkImage
      v-for="image in images"
      :key="image.id"
      :url="image"
    />
  </div>
</template>
