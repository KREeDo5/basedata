<script setup>
import { computed, ref, watch } from 'vue'
import { useAuthStore } from '@/stores/AuthStore'
import { emitter } from '@/eventBus'

import Button from './core/Button.vue'
import Avatar from './core/Avatar.vue'

const props = defineProps({
  isAuthPage: Boolean,
})

const authStore = useAuthStore()
const userAvatar = ref(authStore.user?.image || '')
const name = ref(authStore.user?.name || 'Без имени')

const isUserLoaded = computed(() => !!authStore.user)

const showAuthButtons = computed(() => !authStore.isAuthenticated && !props.isAuthPage)
const showUserInfo = computed(() => authStore.isAuthenticated && !props.isAuthPage)

watch(
  () => authStore.user,
  (newUser) => {
    userAvatar.value = newUser?.image || null
    name.value = newUser?.name || 'Без имени'
    console.log('User image',  userAvatar.value)
  },
)

emitter.on('user-updated', (userData) => {
  userAvatar.value = userData?.image || null
  name.value = userData?.name || 'Без имени'
})
</script>

<template>
  <header class="flex items-center">
    <div class="max-w-7xl w-full mx-auto flex items-center justify-between">
      <RouterLink to="/">
        <div class="flex items-center space-x-2">
          <img src="/logo.png" alt="Logo" class="h-[60px] w-[60px]" />
          <span class="text-lg font-w400 text-base-gold">VOLGA-FORUM</span>
        </div>
      </RouterLink>

      <div v-if="showAuthButtons" class="flex items-center space-x-2">
        <RouterLink :to="{ path: '/auth', query: { mode: 'login' } }">
          <Button text="Войти" />
        </RouterLink>
        <RouterLink :to="{ path: '/auth', query: { mode: 'register' } }">
          <Button variant="free" text="Создать аккаунт" />
        </RouterLink>
      </div>
      <div v-if="showUserInfo && isUserLoaded" title="Перейти в профиль">
        <RouterLink to="/profile" class="flex items-center space-x-2">
          <Avatar :url="userAvatar" />
          <span class="text-white">{{ name }}</span>
        </RouterLink>
      </div>
    </div>
  </header>
</template>
