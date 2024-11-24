<script setup>
defineProps({
  url: String,
})
</script>

<template>
  <img :src="url || '/placeholder.png'" alt="network-image" />
</template>
