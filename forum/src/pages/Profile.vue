<script setup>
import { ref } from 'vue'

import HeaderBar from '../components/HeaderBar.vue'
import Button from '../components/Button.vue'
import SubscriptionBlock from '../components/SubscriptionBlock.vue'

import { subscribers as subscribersData } from '../../fake-api/subscribers-api'
import { subscriptions as subscriptionsData } from '../../fake-api/subscriptions-api'

const subscriberList = ref(subscribersData)
const subscriptionList = ref(subscriptionsData)
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
          <div class="avatar w-full h-[300px] bg-white rounded-2xl mb-[8px]"></div>
          <div class="flex flex-col space-y-[6px] text-center">
            <Button variant="edit" text="Редактировать" />
            <Button variant="edit" text="Сменить пароль" />
          </div>
          <p class="text-gray-500 mt-2">Дата регистрации: 01.04.2003</p>
        </div>
        <!-- Правая сторона -->
        <div class="flex-1">
          <div class="name mb-6">
            <h3 class="text-xl font-w300 text-base-light-grey mb-[10px]">Имя</h3>
            <div class="bg-base-grey rounded-xl px-4 py-3">
              <p class="text-xl text-gray-400">Gabe Newell</p>
            </div>
          </div>
          <div class="about-me">
            <h3 class="text-xl font-w300 text-base-light-grey mb-[10px]">Обо мне</h3>
            <div class="bg-base-grey min-h-[220px] rounded-xl px-4 py-3">
              <p class="text-xl text-gray-400">
                Провожу самые крутые вечеринки в Калифорнии! <br />
                Основатель VolgaAutumn
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
