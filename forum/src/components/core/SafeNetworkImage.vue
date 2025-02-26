<script setup>
import { ref, watch, onMounted } from 'vue'

const props = defineProps({
  url: {
    type: String,
    default: '',
  },
  isUser: {
    type: Boolean,
    default: false,
  },
})

const imageUrl = ref('')

const handleError = () => {
  imageUrl.value = props.isUser ? '/user.png' : '/placeholder.png'
}

const loadImage = async(path) => {
  try {
    const response = await fetch(path)
    if (!response.ok) {
      throw new Error('Image not found')
    }
    imageUrl.value = path
  } catch (_) {
    handleError()
  }
}

const getImageUrl = (url) => {
  if (props.isUser) {
    return url ? `https://forum.kreedo.tech:8443/${url}` : '/user.png'
  } else {
    return url ? `https://forum.kreedo.tech:8443/${url}` : '/placeholder.png'
  }
}

watch(() => props.url, (newUrl) => {
  loadImage(getImageUrl(newUrl))
})

onMounted(() => {
  loadImage(getImageUrl(props.url))
})
</script>

<template>
  <img :src="imageUrl" alt="network-image" @error="handleError" />
</template>