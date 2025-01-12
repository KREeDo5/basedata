<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
  id: Number,
})

const newMessage = ref('')

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
</script>

<template>
  <div class="">
    <hr class="border-t border-base-grey pb-5" />
    <div class="px-5 flex w-full">
      <img
        src="/add-image.png"
        alt="add-icon"
        class="h-[26px] w-[26px] mr-4 mt-1 cursor-pointer hover:opacity-70"
        @click="addImage"
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
        alt="add-icon"
        class="h-[26px] w-[26px] ml-4 mt-1 cursor-pointer hover:opacity-70"
        @click="sendMessage"
        title="Добавить изображение"
      />
    </div>
  </div>
</template>
