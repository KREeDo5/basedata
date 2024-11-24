<script setup>
import { computed } from 'vue'
import Button from '../components/Button.vue'

const props = defineProps({
  isAuthPage: Boolean,
  isAuthenticated: Boolean,
  userName: String,
  userAvatar: String,
})

const showAuthButtons = computed(() => !props.isAuthenticated && !props.isAuthPage)
const showUserInfo = computed(() => props.isAuthenticated && !props.isAuthPage)
</script>

<template>
  <header class="mx-auto flex items-center justify-between px-[200px]">
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
    <div v-if="showUserInfo">
      <RouterLink to="/profile" class="flex items-center space-x-2">
        <img :src="userAvatar" alt="User Avatar" class="avatar" />
        <span class="text-white">{{ userName }}</span>
      </RouterLink>
    </div>
  </header>
</template>
