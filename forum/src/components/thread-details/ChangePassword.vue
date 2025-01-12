<script setup>
import { ref, defineProps, defineEmits, watch, onMounted, onUnmounted } from 'vue'
import { emitter } from '@/eventBus'
import Button from '@/components/core/Button.vue'
import Loader from '@/components/core/Loader.vue'

const props = defineProps({
  isVisible: {
    type: Boolean,
    default: false,
  },
})

const emits = defineEmits(['save', 'cancel'])

const newPassword = ref('')
const oldPassword = ref('')
const showNewPassword = ref(false)
const showOldPassword = ref(false)
const passwordError = ref('')
const successMessage = ref('')
const isLoading = ref(false)

const save = async () => {
  if (!newPassword.value || !oldPassword.value) {
    passwordError.value = 'Заполните все поля'
    return
  }
  if (newPassword.value === oldPassword.value) {
    passwordError.value = 'Новый пароль не должен совпадать с текущим паролем'
    return
  }
  passwordError.value = ''
  successMessage.value = ''

  try {
    isLoading.value = true
    await emits('save', oldPassword.value, newPassword.value);
  } catch (error) {
    passwordError.value = error.message || 'Ошибка при смене пароля'
  }
}

const cancel = () => {
  newPassword.value = ''
  oldPassword.value = ''
  passwordError.value = ''
  successMessage.value = ''
  isLoading.value = false
  emits('cancel')
}

const toggleShowOldPassword = () => {
  showOldPassword.value = !showOldPassword.value
}
const toggleShowNewPassword = () => {
  showNewPassword.value = !showNewPassword.value
}

watch(
  () => props.isVisible,
  (newVal) => {
    if (!newVal) {
      newPassword.value = ''
      oldPassword.value = ''
      passwordError.value = ''
      successMessage.value = ''
      isLoading.value = false
    }
  },
)

onMounted(() => {
  emitter.on('passwordError', (message) => {
    passwordError.value = message;
    isLoading.value = false
  });
  emitter.on('passwordSuccess', () => {
    successMessage.value = 'Пароль успешно изменен'
    isLoading.value = false
  });
})

onUnmounted(() => {
  emitter.off('passwordError')
  emitter.off('passwordSuccess')
})
</script>

<template>
  <div
    v-if="isVisible"
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50"
  >
    <div class="bg-base-darkgrey rounded-lg p-6 w-96">
      <h3 class="text-xl text-base-blue mb-4">Смена пароля</h3>
      <div v-if="isLoading" class="flex justify-center items-center">
        <Loader :key="isLoading" />
      </div>
      <div v-else>
        <div class="relative flex">
          <input
            v-model="oldPassword"
            :type="showOldPassword ? 'text' : 'password'"
            placeholder="Введите старый пароль"
            class="w-full py-2 pl-2 pr-[70px] mb-4 rounded-xl bg-base-asphalt text-gray-300 focus:outline-none focus:ring-1 focus:ring-base-blue"
          />
          <img
            v-if="oldPassword"
            @click="toggleShowOldPassword"
            :src="showOldPassword ? '/hide-password.png' : '/show-password.png'"
            alt="toggle oldPassword visibility"
            class="absolute right-2 my-2 text-gray-300 h-6 w-6 cursor-pointer"
          />
        </div>
        <div class="relative flex">
          <input
            v-model="newPassword"
            :type="showNewPassword ? 'text' : 'password'"
            placeholder="Введите новый пароль"
            class="w-full py-2 pl-2 pr-[70px] mb-4 rounded-xl bg-base-asphalt text-gray-300 focus:outline-none focus:ring-1 focus:ring-base-blue"
          />
          <img
            v-if="newPassword"
            @click="toggleShowNewPassword"
            :src="showNewPassword ? '/hide-password.png' : '/show-password.png'"
            alt="toggle newPassword visibility"
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
