<script setup>
import { ref } from 'vue'

import { useAuthStore } from '@/stores/AuthStore'
import { useRouter } from 'vue-router'
import { emitter } from '@/eventBus'

import HeaderBar from '@/components/HeaderBar.vue'
import Button from '@/components/Button.vue'
import SubscriptionBlock from '@/components/SubscriptionBlock.vue'
import Avatar from '@/components/Avatar.vue'
import FilePickerDrop from '../components/FilePickerDrop.vue'

const router = useRouter()
const authStore = useAuthStore()

const avatarUrl = ref(authStore.user.avatarUrl || '')
const name = ref(authStore.user.name)
const aboutMe = ref(authStore.user.aboutMe)
const login = authStore.user.login

const logout = () => {
  authStore.logout()
  router.push('/auth')
}

import { subscribers as subscribersData } from '../../fake-api/subscribers-api'
import { subscriptions as subscriptionsData } from '../../fake-api/subscriptions-api'

const subscriberList = ref(subscribersData)
const subscriptionList = ref(subscriptionsData)

const isEditing = ref(false)

const toggleEditMode = () => {
  if (isEditing.value) {
    // Сохранение изменений
    authStore.setUser({
      ...authStore.user, // Копируем все свойства пользователя
      name: name.value,
      aboutMe: aboutMe.value,
      avatarUrl: avatarUrl.value,
    })
  }
  isEditing.value = !isEditing.value
}

const removeAvatar = () => {
  avatarUrl.value = ''
  emitter.emit('remove-avatar')
}

const onFileSelectedCallback = (fileUrl) => {
  avatarUrl.value = fileUrl
}
</script>

<template>
  <HeaderBar />
  <div class="max-w-7xl w-full mx-auto mt-10 bg-base-darkgrey rounded-[20px] py-5">
    <div class="flex mb-5 justify-center text-3xl font-w400">
      <h2 class="text-base-gold mr-2">Профиль пользователя</h2>
      <h2 class="text-base-blue">
        {{ login }}
      </h2>
    </div>
    <div class="px-12">
      <!-- Блок [основная информация] -->
      <div class="flex justify-between gap-9">
        <!-- Левая сторона -->
        <div class="flex flex-col w-[250px]">
          <Avatar v-if="!isEditing" :src="avatarUrl" size="big" class="mb-[8px]" />
          <div v-if="isEditing" class="flex flex-col space-y-[6px] mb-[6px] text-center">
            <FilePickerDrop :onFileSelectedCallback="onFileSelectedCallback" />
          </div>
          <div class="flex flex-col space-y-[6px]">
            <div class="flex items-center">
              <img
                v-if="isEditing && avatarUrl"
                src="/delete.png"
                alt="delete-icon"
                class="h-[30px] w-[36px] mr-2"
                @click="removeAvatar"
                title="Удалить аватар"
              />
              <Button
                variant="edit"
                :text="isEditing ? 'Сохранить' : 'Редактировать'"
                @click="toggleEditMode"
                class="w-full"
                :title="isEditing ? 'Сохранить изменения' : 'Редактировать профиль'"
              />
            </div>
            <Button variant="edit" text="Сменить пароль" />
            <Button variant="edit" text="Выйти" @click="logout" title="Выйти из профиля" />
          </div>
          <p class="text-gray-500 mt-2">
            Дата регистрации: {{ userData?.registrationDate || '-' }}
          </p>
        </div>
        <!-- Правая сторона -->
        <div class="flex-1">
          <div class="name mb-6">
            <h3 class="mb-[10px] text-xl font-w300 text-base-light-grey">Имя</h3>
            <form class="bg-base-grey rounded-xl">
              <input
                v-if="isEditing"
                v-model="name"
                type="text"
                id="name"
                placeholder="Введите имя"
                class="bg-base-grey w-full px-4 py-3 rounded-xl text-xl text-gray-400 focus:outline-none focus:ring-1 focus:ring-base-blue"
              />
              <p v-else class="px-4 py-3 text-xl text-gray-400">{{ name || 'Без имени' }}</p>
            </form>
          </div>
          <div class="about-me">
            <h3 class="mb-[10px] text-xl font-w300 text-base-light-grey">Обо мне</h3>
            <div
              v-if="!isEditing"
              class="bg-base-grey min-h-[220px] rounded-xl px-4 py-3 text-xl text-gray-400 break-words whitespace-pre-wrap"
            >
              <p>{{ aboutMe || 'Расскажите о себе' }}</p>
            </div>
            <form v-if="isEditing">
              <textarea
                v-model="aboutMe"
                type="text"
                id="aboutMe"
                placeholder="Расскажите о себе"
                class="bg-base-grey w-full min-h-[220px] px-4 py-3 rounded-xl text-xl text-gray-400 break-words whitespace-pre-wrap focus:outline-none focus:ring-1 focus:ring-base-blue"
              />
            </form>
          </div>
        </div>
      </div>
      <!-- Блок [подписки/подписчики] -->
      <div class="flex justify-between mt-6 gap-[30px]">
        <SubscriptionBlock title="Мои подписки" :items="subscriptionList" class="w-full" />
        <SubscriptionBlock title="Подписчики" :items="subscriberList" class="w-full" />
      </div>
    </div>
  </div>
</template>

<style scoped></style>
