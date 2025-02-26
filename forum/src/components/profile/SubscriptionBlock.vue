<script setup>
import UserAvatar from '../core/UserAvatar.vue'
import { useRouter } from 'vue-router'

const props = defineProps({
  title: String,
  items: Array,
})

const router = useRouter()

const goToProfile = (userId) => {
  router.push({ path: '/profile', query: { userId: userId } })
}
</script>

<template>
  <div class="w-full">
    <h3 class="text-xl font-w300 text-base-light-grey mb-[10px]">{{ props.title }}</h3>
    <div class="bg-base-grey min-h-[200px] rounded-xl grid grid-cols-2 gap-4 px-6 py-5">
      <div v-if="props.items.length === 0" class="text-center col-span-2">
        <span class="text-base-light-grey">Отсутствуют</span>
      </div>
      <div v-else v-for="(item, index) in props.items" :key="index">
        <div class="flex items-center" @click="goToProfile(item.id)" style="cursor: pointer">
          <UserAvatar size="small" class="mr-2" :url="item.image_path" />
          <span class="text-base-blue truncate">{{ item.name || 'Без имени' }}</span>
        </div>
      </div>
    </div>
  </div>
</template>
