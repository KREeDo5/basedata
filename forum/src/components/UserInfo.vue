<script setup>
import { computed } from 'vue'
import { format, isValid } from 'date-fns'
import { useRouter } from 'vue-router'

import Avatar from '@/components/core/Avatar.vue'

const props = defineProps({
  user: Object,
  createdAt: String,
})

const router = useRouter()

const goToProfile = (event) => {
  event.stopPropagation()
  router.push({ path: '/profile', query: { userId: props.user.id } })
}

const formattedDate = computed(() => {
  const date = new Date(props.createdAt)
  return isValid(date) ? format(date, 'dd.MM.yy HH:mm') : props.createdAt
})
</script>

<template>
  <div
    v-if="user"
    class="flex items-center author-block w-[225px]"
    @click="goToProfile"
    style="cursor: pointer"
  >
    <Avatar
      class="mr-3"
      :url="user.image_path"
      size="medium"
    />
    <div>
      <div class="text-base font-w600 text-base-blue">
        {{ user.name || 'без имени' }}
      </div>
      <div class="text-white">{{ formattedDate }}</div>
    </div>
  </div>
  <div v-else class="text-white">Обновляем...</div>
</template>

<style></style>
