<script setup>
import { ref, onMounted, toRefs, computed, watch } from 'vue'
import { useAuthStore } from '@/stores/AuthStore'
import { useProfileStore } from '@/stores/ProfileStore'
import { useRouter } from 'vue-router'
import { emitter } from '@/eventBus'
import { format } from 'date-fns'

import HeaderBar from '@/components/HeaderBar.vue'
import AppButton from '@/components/core/AppButton.vue'
import SubscriptionBlock from '@/components/profile/SubscriptionBlock.vue'
import UserAvatar from '@/components/core/UserAvatar.vue'
import FilePickerDrop from '@/components/core/FilePickerDrop.vue'
import AppLoader from '@/components/core/AppLoader.vue'
import ChangePassword from '@/components/thread-details/ChangePassword.vue'

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

const image = ref(null)
const name = ref('')
const aboutMe = ref('')
const registrationDate = ref('')

const isEditing = ref(false)
const isPasswordModalOpen = ref(false)

const isSubscribed = computed(() => {
  const result = authStore.subscriptions.some((sub) => {
    return sub.id === Number(props.userId)
  })
  return result
})

const isNameValid = computed(() => {
  return name.value.trim() !== ''
})

const toggleEditMode = async () => {
  if (isEditing.value) {
    const updatedProfile = {
      id: authStore.token,
      name: name.value,
      description: aboutMe.value,
    }
    if (image.value) {
      updatedProfile.image = image.value
    }
    await authStore.editProfile(updatedProfile)
    await fetchProfile()
  }
  isEditing.value = !isEditing.value
}

const removeAvatar = async () => {
  image.value = null
  emitter.emit('remove-avatar')
  await authStore.removeAvatar()
  await fetchProfile()
}

const onFileSelectedCallback = (file) => {
  image.value = file
}

const fetchProfile = async () => {
  profileStore.clearStore()
  const id = props.userId || authStore.token
  if (id) {
    await profileStore.fetchUserProfile(id)
    if (userProfile.value) {
      image.value = userProfile.value.image || null
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
    await profileStore.subscribe(props.userId, image, name)
    await authStore.loadUserFromToken()
    await fetchProfile()
  } catch (error) {
    console.error('Error during subscription:', error)
  }
}

const unsubscribe = async () => {
  try {
    await profileStore.unsubscribe(props.userId, image, name)
    await authStore.loadUserFromToken()
    await fetchProfile()
  } catch (error) {
    console.error('Error during unsubscription:', error)
  }
}

const formattedRegistrationDate = computed(() => {
  return registrationDate.value ? format(new Date(registrationDate.value), 'dd.MM.yyyy') : ''
})

const editPassword = () => {
  isPasswordModalOpen.value = true
}

const saveNewPassword = async (oldPassword, newPassword) => {
  try {
    await authStore.editPassword({ id: authStore.token, oldPassword, newPassword })
    isPasswordModalOpen.value = false
    emitter.emit('passwordSuccess')
  } catch (error) {
    emitter.emit('passwordError', error.message || 'Ошибка при смене пароля')
  }
}

const cancelPasswordChange = () => {
  isPasswordModalOpen.value = false
}

watch(
  () => props.userId, // Отслеживаем изменение userId
  async () => {
    isLoading.value = true
    await fetchProfile()
    isLoading.value = false
  },
)

const aboutMeTextComputed = computed(() => {
  const text = aboutMe.value
  return text === null || text === 'null' || text.trim() === '' ? 'Нет информации' : text
})
</script>

<template>
  <HeaderBar />
  <div>
    <div v-if="isLoading" class="fixed inset-0 flex items-center justify-center">
      <AppLoader class="mt-20" />
    </div>
    <div v-else class="max-w-7xl w-full mx-auto mt-10 bg-base-darkgrey rounded-[20px] py-5">
      <div class="flex mb-5 justify-center text-3xl font-w400">
        <h2 class="text-base-gold mr-2">{{ isOwner ? 'Ваш профиль' : 'Профиль пользователя' }}</h2>
        <h2 v-if="!isOwner" class="text-base-blue">
          {{ name }}
        </h2>
      </div>
      <div class="px-12">
        <!-- Блок [основная информация] -->
        <div class="flex justify-between gap-9">
          <!-- Левая сторона -->
          <div class="flex flex-col w-[250px]">
            <UserAvatar v-if="!isEditing" :key="image" :url="image" size="big" class="mb-[8px]" />
            <div v-if="isEditing" class="flex flex-col space-y-[6px] mb-[6px] text-center">
              <FilePickerDrop :onFileSelectedCallback="onFileSelectedCallback" />
            </div>
            <div class="flex flex-col space-y-[6px]">
              <div class="flex items-center">
                <img
                  v-if="isOwner && image"
                  src="/delete.png"
                  alt="delete-icon"
                  class="h-[30px] w-[36px] mr-2 cursor-pointer hover:opacity-70"
                  @click="removeAvatar"
                  title="Удалить аватар"
                />
                <AppButton
                  v-if="isOwner"
                  :variant="isEditing ? 'rounded' : 'edit'"
                  :text="isEditing ? 'Сохранить' : 'Редактировать'"
                  @click="toggleEditMode"
                  class="w-full"
                  :title="isEditing ? 'Сохранить изменения' : 'Редактировать профиль'"
                  :disabled="isEditing && !isNameValid"
                />
              </div>
              <AppButton
                v-if="isOwner"
                variant="edit"
                text="Сменить пароль"
                @click="editPassword"
              />
              <AppButton
                v-if="isOwner"
                variant="edit"
                text="Выйти"
                @click="logout"
                title="Выйти из профиля"
              />
              <AppButton
                v-if="!isOwner && isSubscribed"
                text="Отписаться"
                variant="rounded"
                @click="unsubscribe"
                title="Отписаться"
              />
              <AppButton
                v-if="!isOwner && !isSubscribed"
                text="Подписаться"
                @click="subscribe"
                title="Подписаться"
              />
            </div>
            <p class="text-gray-500 mt-2">
              Дата регистрации: {{ formattedRegistrationDate || '-' }}
            </p>
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
                <p>{{ aboutMeTextComputed }}</p>
              </div>
              <form v-if="isEditing">
                <textarea
                  v-model="aboutMe"
                  type="text"
                  id="aboutMe"
                  placeholder="Расскажите о себе"
                  class="bg-base-grey w-full min-h-[220px] max-h-[400px] px-4 py-3 rounded-xl text-xl text-gray-400 break-words whitespace-pre-wrap focus:outline-none focus:ring-1 focus:ring-base-blue"
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

  <!-- Модальное окно для смены пароля -->
  <ChangePassword
    :isVisible="isPasswordModalOpen"
    @save="saveNewPassword"
    @cancel="cancelPasswordChange"
  />
</template>

<style scoped></style>
