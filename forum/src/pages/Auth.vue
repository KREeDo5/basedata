<script setup>
import { ref, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import HeaderBar from '../components/HeaderBar.vue'
import Button from '../components/Button.vue'
import Modal from '../components/Modal.vue'

const route = useRoute()
const router = useRouter()
const isLogin = ref(route.query.mode !== 'register')

const form = ref({
  login: '',
  name: '',
  password: '',
})

const isModalVisible = ref(false)
const modalMessage = ref('')
const countdown = ref(5)
let countdownInterval = null

const toggleMode = () => {
  isLogin.value = !isLogin.value
  form.value.name = '' // Очистка имени при переключении
  router.push({ path: '/auth', query: { mode: isLogin.value ? 'login' : 'register' } })
}

const handleSubmit = () => {
  console.log('Form submitted:', form.value)
  if (
    form.value.login === '' ||
    form.value.password === '' ||
    (!isLogin.value && form.value.name === '')
  ) {
    modalMessage.value = 'Пожалуйста, заполните все поля.'
    isModalVisible.value = true
    startCountdown()
    return
  }
  router.push('/')
}

const startCountdown = () => {
  countdown.value = 3
  if (countdownInterval) clearInterval(countdownInterval)
  countdownInterval = setInterval(() => {
    countdown.value -= 1
    if (countdown.value <= 0) {
      closeModal()
    }
  }, 1000)
}

const closeModal = () => {
  isModalVisible.value = false
  if (countdownInterval) clearInterval(countdownInterval)
}

watch(isModalVisible, (newVal) => {
  if (!newVal && countdownInterval) clearInterval(countdownInterval)
})
</script>

<template>
  <HeaderBar :isAuthPage="true" />
  <div class="flex items-center justify-center mt-10">
    <!-- Модальное окно -->
    <Modal :isVisible="isModalVisible" :message="modalMessage" :countdown="countdown" />

    <!-- Основной контент -->
    <div class="w-full max-w-[500px] relative">
      <div class="h-1.5 bg-base-blue"></div>
      <div class="h-1.5 bg-white"></div>
      <div class="h-1.5 bg-base-red"></div>
      <div
        class="px-[70px] py-10 bg-base-darkgrey rounded-b-[60px] border-[1px] border-t-0 border-base-gold text-center"
      >
        <h2 class="text-white text-xl font-w300 mb-12">
          {{ isLogin ? 'Войдите в свой аккаунт' : 'Присоединиться к форуму' }}
        </h2>
        <form @submit.prevent="handleSubmit">
          <div class="mb-4">
            <input
              v-model="form.login"
              type="text"
              id="login"
              placeholder="Логин"
              class="w-full p-2 rounded-xl bg-base-grey text-gray-300 focus:outline-none focus:ring-1 focus:ring-base-blue text-center"
            />
          </div>
          <div v-if="!isLogin" class="mb-4">
            <input
              v-model="form.name"
              type="text"
              id="name"
              placeholder="Имя"
              class="w-full p-2 rounded-xl bg-base-grey text-gray-300 focus:outline-none focus:ring-1 focus:ring-base-blue text-center"
            />
          </div>
          <div class="mb-[60px]">
            <input
              v-model="form.password"
              type="password"
              id="password"
              placeholder="Пароль"
              class="w-full p-2 rounded-xl bg-base-grey text-gray-300 focus:outline-none focus:ring-1 focus:ring-base-blue text-center"
            />
          </div>
          <Button
            class="w-full flex justify-center"
            type="submit"
            :text="isLogin ? 'Вход' : 'Создать аккаунт'"
          />
        </form>
        <button @click="toggleMode" class="w-full text-sm text-gray-400 mt-5 hover:underline">
          {{ isLogin ? 'Регистрация' : 'Уже есть аккаунт? Войти' }}
        </button>
      </div>
    </div>
  </div>
</template>
