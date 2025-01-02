<script setup>
import { ref, onMounted, toRefs, computed } from 'vue'
import { useAuthStore } from '@/stores/AuthStore'
import { useProfileStore } from '@/stores/ProfileStore'
import { useRouter } from 'vue-router'
import { emitter } from '@/eventBus'
import { format } from 'date-fns'

import HeaderBar from '@/components/HeaderBar.vue'
import Button from '@/components/Button.vue'
import SubscriptionBlock from '@/components/SubscriptionBlock.vue'
import Avatar from '@/components/Avatar.vue'
import FilePickerDrop from '@/components/FilePickerDrop.vue'
import Loader from '@/components/Loader.vue'

const props = defineProps({
  userId: {
    type: String,
    default: null,
  },
})

const isOwner = computed(() => !props.userId || props.userId === authStore.token)

const router = useRouter()
const authStore = useAuthStore()
const profileStore = useProfileStore()

const { userProfile, subscriptions, subscribers } = toRefs(profileStore)

const avatarUrl = ref('')
const name = ref('')
const aboutMe = ref('')
const registrationDate = ref('')

const isEditing = ref(false)

const toggleEditMode = async () => {
  if (isEditing.value) {
    await authStore.editProfile({
      id: authStore.token,
      name: name.value,
      description: aboutMe.value,
      image_path: avatarUrl.value,
    })
    await fetchProfile()
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

const fetchProfile = async () => {
  const id = props.userId || authStore.token
  if (id) {
    await profileStore.fetchUserProfile(id)
    if (userProfile.value) {
      avatarUrl.value = userProfile.value.image_path || ''
      name.value = userProfile.value.name
      aboutMe.value = userProfile.value.description
      registrationDate.value = userProfile.value.registration_date
    }
  } else {
    router.push('/auth')
  }
}

onMounted(async () => {
  isLoading.value = true
  await fetchProfile()
  isLoading.value = false
})

const isLoading = ref(true)

const logout = () => {
  authStore.logout()
  router.push('/auth')
}

const subscribe = async () => {
  try {
    await profileStore.subscribe(props.userId)
  } catch (error) {
    console.error('Error during subscription:', error)
  }
}

const unsubscribe = async () => {
  try {
    await profileStore.unsubscribe(props.userId)
  } catch (error) {
    console.error('Error during unsubscription:', error)
  }
}

const formattedRegistrationDate = computed(() => {
  return registrationDate.value ? format(new Date(registrationDate.value), 'dd.MM.yyyy') : ''
})
</script>

<template>
  <HeaderBar />
  <div>
    <div v-if="isLoading" class="fixed inset-0 flex items-center justify-center">
      <Loader class="mt-20" />
    </div>
    <div v-else class="max-w-7xl w-full mx-auto mt-10 bg-base-darkgrey rounded-[20px] py-5">
      <div class="flex mb-5 justify-center text-3xl font-w400">
        <h2 class="text-base-gold mr-2">{{ isOwner ? 'Ваш профиль' : 'Профиль пользователя' }}</h2>
        <h2 v-if="isOwner" class="text-base-blue">
          {{ name }}
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
                  v-if="isOwner"
                  :variant="isEditing ? 'rounded' : 'edit'"
                  :text="isEditing ? 'Сохранить' : 'Редактировать'"
                  @click="toggleEditMode"
                  class="w-full"
                  :title="isEditing ? 'Сохранить изменения' : 'Редактировать профиль'"
                />
              </div>
              <Button v-if="isOwner" variant="edit" text="Сменить пароль" />
              <Button
                v-if="isOwner"
                variant="edit"
                text="Выйти"
                @click="logout"
                title="Выйти из профиля"
              />
              <Button v-if="!isOwner" text="Подписаться" @click="subscribe" title="Подписаться" />
              <Button
              v-if="!isOwner"
              text="Отписаться"
              @click="unsubscribe"
              title="Отписаться"
            />
            </div>
            <p class="text-gray-500 mt-2">Дата регистрации: {{ formattedRegistrationDate || '-' }}</p>
          </div>
          <!-- Правая сторона -->
          <div class="flex-1">
            <div v-if="isOwner" class="name mb-6">
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
                <p>{{ aboutMe || 'Нет информации' }}</p>
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
          <SubscriptionBlock
            :title="isOwner ? 'Мои подписки' : 'Подписки'"
            :items="subscriptions"
            class="w-full"
          />
          <SubscriptionBlock title="Подписчики" :items="subscribers" class="w-full" />
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
