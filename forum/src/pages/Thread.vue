<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { useThreadStore } from '@/stores/ThreadStore'
import { format, isValid } from 'date-fns'

import HeaderBar from '@/components/HeaderBar.vue'
import Button from '@/components/Button.vue'
import Avatar from '@/components/Avatar.vue'
import Loader from '@/components/Loader.vue'

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

const goToProfile = (event) => {
  router.push({ path: '/profile', query: { userId: threadStore.threadAuthor.id } })
}

const formattedDate = computed(() => {
  const date = new Date(threadStore.createdAt)
  return isValid(date) ? format(date, 'dd.MM.yy HH:mm') : threadStore.createdAt
})
</script>

<template>
  <HeaderBar />
  <div v-if="isLoading" class="fixed inset-0 flex items-center justify-center">
    <Loader class="mt-20" />
  </div>
  <div v-else class="max-w-7xl w-full mx-auto mt-10 bg-base-darkgrey rounded-[20px] py-5">
    <div class="flex p-[22px]">
      <div class="flex text-white text-2xl font-w600 w-[1040px]">{{ threadStore.title }}</div>
      <div>
        <div
          class="flex items-center author-block w-[225px]"
          @click="goToProfile"
          style="cursor: pointer"
        >
          <Avatar
            class="mr-3"
            :src="
              threadStore.threadAuthor.image_path
                ? `https://forum.kreedo.tech:8443/${threadStore.threadAuthor.image_path}`
                : null
            "
            size="medium"
          />
          <div>
            <div class="text-base font-w600 text-base-blue">
              {{ threadStore.threadAuthor.name || 'без имени' }}
            </div>
            <div class="text-white">{{ formattedDate }}</div>
          </div>
        </div>
        <Button class="w-full mt-3" text="закрыть тему" variant="edit" />
      </div>
    </div>
    <hr class="border-t border-gray-300 my-4" />
    <div class="text-white text-xl px-5 font-w400">
      {{ threadStore.text || 'Тема без описания' }}
    </div>
  </div>
</template>

<style></style>
