<script setup>
import { ref, defineProps, defineEmits, watch } from 'vue'
import Button from '@/components/core/Button.vue'
import Loader from '@/components/core/Loader.vue'
import { useAuthStore } from '@/stores/AuthStore'

const props = defineProps({
  isVisible: {
    type: Boolean,
    default: false,
  },
})

const emits = defineEmits(['save', 'cancel'])

const authStore = useAuthStore()

const password = ref('')
const showPassword = ref(false)
const passwordError = ref('')
const successMessage = ref('')
const isLoading = ref(false)

const save = async () => {
  if (authStore.validatePassword(password.value)) {
    isLoading.value = true
    try {
      await emits('save', password.value)
      successMessage.value = 'Пароль успешно изменен'
      passwordError.value = ''
      password.value = ''
    } catch (error) {
      passwordError.value = error.message || 'Ошибка при смене пароля'
    } finally {
      isLoading.value = false
    }
  } else {
    passwordError.value = 'Пароль должен быть не меньше 6 символов, содержать буквы на латинице и цифры'
  }
}

const cancel = () => {
  password.value = ''
  passwordError.value = ''
  successMessage.value = ''
  isLoading.value = false
  emits('cancel')
}

const toggleShowPassword = () => {
  showPassword.value = !showPassword.value
}

watch(
  () => props.isVisible,
  (newVal) => {
    if (!newVal) {
      password.value = ''
      passwordError.value = ''
      successMessage.value = ''
      isLoading.value = false
    }
  },
)
</script>

<template>
  <div
    v-if="isVisible"
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
  >
    <div class="bg-base-darkgrey rounded-lg p-6 w-96">
      <h3 class="text-xl text-base-blue mb-4">Смена пароля</h3>
      <div v-if="isLoading" class="flex justify-center items-center">
        <Loader />
      </div>
      <div v-else>
        <div class="relative flex">
          <input
            v-model="password"
            :type="showPassword ? 'text' : 'password'"
            placeholder="Введите новый пароль"
            class="w-full py-2 pl-2 pr-[70px] mb-4 rounded-xl bg-base-asphalt text-gray-300 focus:outline-none focus:ring-1 focus:ring-base-blue"
          />
          <img
            v-if="password"
            @click="toggleShowPassword"
            :src="showPassword ? '/hide-password.png' : '/show-password.png'"
            alt="toggle password visibility"
            class="absolute right-2 my-2 text-gray-300 h-6 w-6 cursor-pointer"
          />
        </div>
        <p v-if="passwordError" class="text-red-500 mb-4">{{ passwordError }}</p>
        <p v-if="successMessage" class="text-green-500 mb-4">{{ successMessage }}</p>
        <div class="flex justify-end space-x-2">
          <Button variant="rounded" text="Сохранить" @click="save" title="Сохранить пароль" />
          <Button text="Отменить" @click="cancel" title="Отменить смену пароля" />
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
