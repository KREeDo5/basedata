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
      :src="image ? `https://forum.kreedo.tech:8443/${image}` : null"
    />
  </div>
</template>
