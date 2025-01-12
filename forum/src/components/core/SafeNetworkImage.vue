<script setup>
import { ref } from 'vue'

const props = defineProps({
  url: String,
})

const imageUrl = ref(props.url || '/placeholder.png')

const handleError = () => {
  imageUrl.value = '/placeholder.png'
}
</script>

<template>
  <img :src="imageUrl" alt="network-image" @error="handleError" />
</template>
