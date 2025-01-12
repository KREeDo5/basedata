<script setup>
import { ref, onMounted, computed, defineEmits } from 'vue'
import { useAuthStore } from '@/stores/AuthStore'

const props = defineProps({
  threadId: String,
})

const emit = defineEmits(['messageSent'])

const authStore = useAuthStore()
const newMessage = ref('')
const messageImages = ref([])
const imageInputRef = ref(null)

const adjustTextareaHeight = (event) => {
  const textarea = event.target
  textarea.style.height = 'auto'
  textarea.style.height = `${textarea.scrollHeight}px`
}

onMounted(() => {
  const textarea = document.getElementById('newMessage')
  if (textarea) {
    textarea.addEventListener('input', adjustTextareaHeight)
  }
})

const sendMessage = async () => {
  if (!isMessageValid.value) {
    return
  }
  const message = {
    threadId: props.threadId,
    userId: authStore.token,
    text: newMessage.value,
    messageImages: messageImages.value,
  }
  emit('messageSent', message)

  newMessage.value = ''
  messageImages.value = []
}

const isMessageValid = computed(() => {
  return newMessage.value.trim() !== ''
})

const addImage = (event) => {
  const files = event.target.files
  for (let i = 0; i < files.length; i++) {
    const reader = new FileReader()
    reader.onload = (e) => {
      messageImages.value.push(e.target.result)
    }
    reader.readAsDataURL(files[i])
  }
}
</script>

<template>
  <div class="">
    <hr class="border-t border-base-grey pb-5" />
    <div v-if="messageImages.length" class="px-5 flex w-full flex-wrap">
      <div v-for="(image, index) in messageImages" :key="index" class="mr-2 mb-2">
        <img :src="image" alt="Uploaded Image" class="max-w-[100px] max-h-[100px] rounded-lg cursor-pointer" @click="imageInputRef.click()" />
      </div>
    </div>
    <div class="px-5 flex w-full">
      <input type="file" id="imageInput" ref="imageInputRef" class="hidden" @change="addImage" multiple>
      <img
        src="/add-image.png"
        alt="add-icon"
        class="h-[26px] w-[26px] mr-4 mt-1 cursor-pointer hover:opacity-70"
        @click="imageInputRef.click()"
        title="Добавить изображение"
      />
      <form class="bg-base-grey rounded-xl w-full">
        <textarea
          v-model="newMessage"
          type="text"
          id="newMessage"
          placeholder="Напишите ответ"
          class="bg-base-grey min-h-10 h-10 w-full px-4 py-2 rounded-xl text-base text-gray-400 focus:outline-none focus:ring-1 focus:ring-base-blue"
          @input="adjustTextareaHeight"
        />
      </form>
      <img
        src="/send.png"
        alt="send-icon"
        class="${disabledStyle} h-[26px] w-[26px] ml-4 mt-1 hover:opacity-70"
        :class="isMessageValid ? 'cursor-pointer' : 'cursor-not-allowed opacity-50'"
        @click="sendMessage"
        title="Отправить сообщение"
      />
    </div>
  </div>
</template>