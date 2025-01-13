<script setup>
import { ref, onMounted, computed } from 'vue'
import Button from '@/components/core/Button.vue'
import { useAuthStore } from '@/stores/AuthStore'
import { useHomeStore } from '@/stores/HomeStore'

const authStore = useAuthStore()
const homeStore = useHomeStore()

const showModal = ref(true)
const threadImages = ref([])
const imageInputRef = ref(null)
const title = ref('')
const text = ref('')
const categories = ref([])
const selectedCategory = ref('')

onMounted(async () => {
  categories.value = homeStore.categories
})

const closeModal = () => {
  showModal.value = false
  emits('closeCreateThreadModal')
}

const emits = defineEmits(['closeCreateThreadModal'])

const createThread = async () => {
  if (isValidForm) {
    await sendThread()
    closeModal()
  }
}

const isValidForm = computed(() => {
  const result = title.value.trim() !== '' && selectedCategory.value && text.value.trim() !== ''
  return result
})

const sendThread = async () => {
  const thread = {
    categoryId: selectedCategory.value,
    userId: authStore.token,
    title: title.value,
    text: text.value,
    threadImages: threadImages.value,
  }
  await homeStore.createThread(thread)
}

const addImage = (event) => {
  const files = event.target.files
  for (let i = 0; i < files.length; i++) {
    threadImages.value.push(files[i])
  }
  event.target.value = ''
}

const getObjectURL = (file) => URL.createObjectURL(file)

const removeImage = (index) => {
  threadImages.value.splice(index, 1)
}
</script>

<template>
  <div
    v-if="showModal"
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
  >
    <div class="max-w-4xl w-full bg-base-grey rounded-[20px] p-6">
      <div>
        <div class="mb-4">
          <label for="category" class="text-2xl text-base-gold font-w500">Категория:</label>
          <div class="h-[10px]" />
          <select
            id="category"
            v-model="selectedCategory"
            class="w-full p-2.5 block bg-base-darkgrey text-base-grey2 border border-base-grey text-base rounded-[8px] focus:outline-none focus:ring-1 focus:ring-base-blue"
            required
          >
            <option value="" disabled selected hidden>Выберите категорию</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">
              {{ category.title }}
            </option>
          </select>
        </div>
        <div class="mb-4">
          <label for="title" class="text-2xl text-base-gold font-w500">Заголовок:</label>
          <div class="text-base text-base-light-grey my-2">
            Сформулируйте в нескольких словах о чём ваш тред
          </div>
          <input
            id="title"
            v-model="title"
            type="text"
            placeholder="Введите заголовок треда"
            class="w-full p-2.5 bg-base-darkgrey text-base-grey2 border border-base-grey text-base rounded-[8px] focus:outline-none focus:ring-1 focus:ring-base-blue"
            required
          />
        </div>
        <div class="mb-4">
          <label for="text" class="text-2xl text-base-gold font-w500">Описание:</label>
          <div class="h-[10px]" />
          <textarea
            v-model="text"
            type="text"
            id="aboutMe"
            placeholder="Опишите подробнее что вас интересует"
            class="bg-base-darkgrey w-full min-h-[220px] max-h-[220px] px-4 py-3 rounded-xl text-base text-base-grey2 break-words whitespace-pre-wrap focus:outline-none focus:ring-1 focus:ring-base-blue"
          />
        </div>
        <div class="mb-4 flex">
          <div
            class="bg-base-darkgrey h-[120px] w-[180px] rounded-lg cursor-pointer hover:opacity-70 py-5"
          >
            <img
              src="/add-thread-image.png"
              alt="add-icon"
              class="h-[80px] w-[80px] mx-auto"
              @click="imageInputRef.click()"
              title="Добавить изображение"
            />
          </div>
          <input
            type="file"
            id="imageInput"
            ref="imageInputRef"
            class="hidden"
            @change="addImage"
            multiple
          />
          <div v-if="threadImages.length" class="px-5 flex w-full flex-wrap">
            <div v-for="(image, index) in threadImages" :key="index" class="mr-2 mb-2 relative">
              <img
                :src="getObjectURL(image)"
                alt="Uploaded Image"
                class="max-h-[120px] max-w-[180px] rounded-lg cursor-pointer"
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
        </div>
        <div class="flex justify-end">
          <Button
            variant="rounded"
            text="Отмена"
            @click="closeModal"
            title="Отменить создание треда"
            class="mr-2"
          />
          <Button
            type="submit"
            text="Создать"
            @click="createThread"
            title="Создать тред"
            :disabled="!isValidForm"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
