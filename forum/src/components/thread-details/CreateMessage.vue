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
  if (!textarea.value) {
    textarea.style.height = 'auto'
    return
  }
  textarea.style.height = 'auto'
  textarea.style.height = `${textarea.scrollHeight}px`
}

onMounted(() => {
  const textarea = document.getElementById('newMessage')
  if (textarea) {
    textarea.style.height = 'auto'
    textarea.style.height = `${textarea.scrollHeight}px`
  }
})

const sendMessage = async () => {
  if (!props.threadId) {
    console.error('Отсутствует threadId')
    return
  }
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

  const textarea = document.getElementById('newMessage')
  if (textarea) {
    textarea.style.height = 'auto'
  }
}

const isMessageValid = computed(() => {
  return newMessage.value.trim() !== ''
})

const addImage = (event) => {
  const files = event.target.files
  for (let i = 0; i < files.length; i++) {
    messageImages.value.push(files[i])
  }
  event.target.value = ''
}

const getObjectURL = (file) => URL.createObjectURL(file)

const removeImage = (index) => {
  messageImages.value.splice(index, 1)
}

const handleKeyDown = (event) => {
  if (event.key === 'Enter') {
    if (event.shiftKey) {
      event.preventDefault()
      newMessage.value += '\n'
      adjustTextareaHeight(event)
    } else {
      event.preventDefault()
      sendMessage()
    }
  }
}
</script>

<template>
  <div class="">
    <hr class="border-t border-base-grey pb-5" />
    <div v-if="messageImages.length" class="px-5 flex w-full flex-wrap">
      <div v-for="(image, index) in messageImages" :key="index" class="mr-2 mb-2 relative">
        <img
          :src="getObjectURL(image)"
          alt="Uploaded Image"
          class="max-w-[100px] max-h-[100px] rounded-lg cursor-pointer"
        />
        <button
          @click="removeImage(index)"
          class="absolute top-0 right-0 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm"
          title="Удалить"
        >
          ×
        </button>
      </div>
    </div>
    <div class="px-5 flex w-full">
      <input
        type="file"
        id="imageInput"
        ref="imageInputRef"
        class="hidden"
        @change="addImage"
        multiple
      />
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
          class="bg-base-grey min-h-10 h-10 max-h-[150px] w-full px-4 py-2 rounded-xl text-base text-gray-400 focus:outline-none focus:ring-1 focus:ring-base-blue"
          @input="adjustTextareaHeight"
          @keydown="handleKeyDown"
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
