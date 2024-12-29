<script setup>
import { ref } from 'vue'

import { useAuthStore } from '@/stores/AuthStore'
import { useRouter } from 'vue-router'

import HeaderBar from '@/components/HeaderBar.vue'
import Button from '@/components/Button.vue'
import SubscriptionBlock from '@/components/SubscriptionBlock.vue'
import Avatar from '@/components/Avatar.vue'
import FilePickerDrop from '../components/FilePickerDrop.vue'

const router = useRouter()
const authStore = useAuthStore()

const avatarUrl = ref(authStore.user.avatarUrl || '')
const name = ref(authStore.user.name || 'Без имени')
const aboutMe = ref(authStore.user.aboutMe || 'Расскажите о себе')

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
    authStore.setUser(
      {
        ...authStore.user, // Копируем все свойства пользователя
        name: name.value,
        aboutMe: aboutMe.value,
        avatarUrl: avatarUrl.value,
      },
      authStore.token,
    )
  }
  isEditing.value = !isEditing.value
}

const removeAvatar = () => {
  avatarUrl.value = ''
}

const onFileSelectedCallback = (fileUrl) => {
  avatarUrl.value = fileUrl
}
</script>

<template>
  <HeaderBar />
  <div class="max-w-7xl w-full mx-auto mt-10 bg-base-darkgrey rounded-[20px] py-5">
    <h2 class="text-3xl font-w400 text-base-gold text-center mb-5">Профиль</h2>
    <div class="px-12">
      <!-- Блок [основная информация] -->
      <div class="flex justify-between gap-9">
        <!-- Левая сторона -->
        <div class="flex flex-col w-[250px]">
          <Avatar v-if="!isEditing" :src="avatarUrl" size="big" class="mb-[8px]" />
          <div v-if="isEditing" class="flex flex-col space-y-[6px] mb-[6px] text-center">
            <FilePickerDrop :onFileSelectedCallback="onFileSelectedCallback" />
            <Button variant="edit" text="Удалить аватар" @click="removeAvatar" v-if="avatarUrl" />
          </div>
          <div class="flex flex-col space-y-[6px]">
            <Button
              variant="edit"
              :text="isEditing ? 'Сохранить' : 'Редактировать'"
              @click="toggleEditMode"
            />
            <Button variant="edit" text="Сменить пароль" />
            <Button variant="edit" text="Выйти" @click="logout" />
          </div>
          <p class="text-gray-500 mt-2">
            Дата регистрации: {{ userData?.registrationDate || '-' }}
          </p>
        </div>
        <!-- Правая сторона -->
        <div class="flex-1">
          <div class="name mb-6">
            <h3 class="text-xl font-w300 text-base-light-grey mb-[10px]">Имя</h3>
            <div class="bg-base-grey rounded-xl px-4 py-3">
              <input
                v-if="isEditing"
                v-model="name"
                class="w-full text-xl bg-base-grey text-white"
              />
              <p v-else class="text-xl text-gray-400">{{ name }}</p>
            </div>
          </div>
          <div class="about-me">
            <h3 class="text-xl font-w300 text-base-light-grey mb-[10px]">Обо мне</h3>
            <div class="bg-base-grey min-h-[220px] rounded-xl px-4 py-3">
              <textarea
                v-if="isEditing"
                v-model="aboutMe"
                class="w-full text-xl bg-base-grey text-white"
              />
              <p v-else class="text-xl text-gray-400">
                {{ aboutMe || 'Информация отсутствует' }}
              </p>
            </div>
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
