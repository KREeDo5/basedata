<script setup>
import { ref, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/AuthStore'

import HeaderBar from '@/components/HeaderBar.vue'
import Button from '@/components/Button.vue'
import Modal from '@/components/Modal.vue'

const route = useRoute()
const router = useRouter()

const authStore = useAuthStore()

const isLogin = ref(route.query.mode !== 'register')

const form = ref({
  login: '',
  name: '',
  password: '',
})

const isModalVisible = ref(false)
const showPassword = ref(false)
const modalMessage = ref('')
const countdown = ref(5)
let countdownInterval = null

const toggleMode = () => {
  isLogin.value = !isLogin.value
  form.value.name = '' // Очистка имени при переключении
  router.push({ path: '/auth', query: { mode: isLogin.value ? 'login' : 'register' } })
}

const handleSubmit = async () => {
  try {
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

    if (isLogin.value) {
      // Авторизация
      await authStore.login({
        login: form.value.login,
        password: form.value.password,
      })
    } else {
      // Регистрация
      await authStore.register({
        login: form.value.login,
        name: form.value.name,
        password: form.value.password,
      })
    }

    await authStore.loadUserFromToken()

    if (authStore.isAuthenticated) {
      router.push('/') // Перенаправление после успешного входа
    } else {
      modalMessage.value =
        'Ошибка авторизации. Пожалуйста, проверьте свои данные и попробуйте снова.'
      isModalVisible.value = true
      startCountdown()
    }
  } catch (error) {
    modalMessage.value = 'Ошибка: ' + error.message
    isModalVisible.value = true
    startCountdown()
  }
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

const toggleShowPassword = () => {
  showPassword.value = !showPassword.value
}
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
          <div class="relative flex mb-[60px]">
            <input
              v-model="form.password"
              :type="showPassword ? 'text' : 'password'"
              id="password"
              placeholder="Пароль"
              class="w-full py-2 px-10 rounded-xl bg-base-grey text-gray-300 focus:outline-none focus:ring-1 focus:ring-base-blue text-center"
            />
            <img
              v-if="form.password"
              @click="toggleShowPassword"
              :src="showPassword ? '/hide-password.png' : '/show-password.png'"
              alt="toggle password visibility"
              class="absolute right-2 my-2 text-gray-300 h-6 w-6 cursor-pointer"
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
