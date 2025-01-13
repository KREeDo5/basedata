<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { useThreadStore } from '@/stores/ThreadStore'
import { useAuthStore } from '@/stores/AuthStore'

import HeaderBar from '@/components/HeaderBar.vue'
import Button from '@/components/core/Button.vue'
import Loader from '@/components/core/Loader.vue'
import ThreadImages from '@/components/thread-details/ThreadImages.vue'
import ThreadMessages from '@/components/thread-details/ThreadMessages.vue'
import CreateMessage from '@/components/thread-details/CreateMessage.vue'
import UserInfo from '@/components/UserInfo.vue'

const props = defineProps({
  threadId: {
    type: String,
  },
})

const threadStore = useThreadStore()
const authStore = useAuthStore()

const isOwner = computed(() => {
  const result = threadStore.threadAuthor && threadStore.threadAuthor.id === Number(authStore.token)
  return result
})

const isUserAuthorized = computed(() => !!authStore.user)

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

const sendMessage = async (messageForm) => {
  await threadStore.sendMessage(messageForm)
  await fetchThread()
}

const closeThread = async () => {
  await threadStore.closeThread({ userId: authStore.token, threadId: props.threadId })
  await fetchThread()
}
</script>

<template>
  <HeaderBar />
  <div v-if="isLoading" class="fixed inset-0 flex items-center justify-center">
    <Loader class="mt-20" />
  </div>
  <div
    v-else
    class="flex flex-col min-h-[calc(100vh-10rem)] max-w-7xl w-full mx-auto mt-10 mb-10 bg-base-darkgrey rounded-[20px] py-5"
  >
    <div class="flex p-[22px]">
      <div v-if="threadStore.isClosed" class="mr-2 mt-1">
        <img src="/closed.png" alt="icon" class="ml-2 h-6" />
      </div>
      <div class="flex text-white text-2xl font-w600 w-[1040px]">{{ threadStore.title }}</div>
      <div>
        <UserInfo :user="threadStore.threadAuthor" :createdAt="threadStore.createdAt" />
        <Button
          v-if="isOwner && !threadStore.isClosed"
          class="w-full mt-3"
          text="закрыть тему"
          variant="edit"
          @click="closeThread"
        />
      </div>
    </div>
    <hr class="border-t border-base-grey" />
    <div class="text-white text-base p-5 font-w400">
      {{ threadStore.text || 'Тема без описания' }}
    </div>
    <ThreadImages :images="threadStore.images" />
    <ThreadMessages :messages="threadStore.threadMessages" />
    <div class="mt-auto">
      <CreateMessage
        v-if="!threadStore.isClosed && isUserAuthorized"
        :threadId="threadId"
        @messageSent="sendMessage"
      />
      <div v-else>
        <hr class="border-t border-base-grey pb-5" />
        <div class="text-base text-base-red text-center w-full">
          {{
            threadStore.isClosed
              ? 'Тема закрыта для написания сообщений'
              : 'Для ответа необходимо авторизоваться'
          }}
        </div>
      </div>
    </div>
  </div>
</template>

<style></style>
