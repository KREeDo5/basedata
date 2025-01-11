<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

import HeaderBar from '@/components/HeaderBar.vue'
import { useThreadStore } from '@/stores/ThreadStore'

const props = defineProps({
  threadId: {
    type: String,
    default: 0,
  },
})

const threadStore = useThreadStore()

const isLoading = ref(true)

onMounted(async () => {
  isLoading.value = true
  await fetchThread()
  isLoading.value = false
})

onUnmounted(() => {
  threadStore.clearStore()
})

const fetchThread = async () => {
  threadStore.clearStore()
  const id = props.threadId
  if (id) {
    await threadStore.fetchThreadDetails(id)
  } else {
    router.push('/home')
  }
}

console.log(threadStore.title)
</script>

<template>
  <HeaderBar />
  <div>{{ threadStore.title }}</div>
</template>

<style></style>
