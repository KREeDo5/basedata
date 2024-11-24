<script setup>
const props = defineProps({
  title: String,
  items: Array,
})
</script>

<template>
  <div class="w-full">
    <h3 class="text-xl font-w300 text-base-light-grey mb-[10px]">{{ title }}</h3>
    <div class="bg-base-grey min-h-[200px] rounded-xl grid grid-cols-2 gap-4 px-6 py-5">
      <div
        v-for="(item, index) in items"
        :key="index"
        class="flex items-center space-x-3 text-base-blue"
      >
        <span>{{ item }}</span>
      </div>
    </div>
  </div>
</template>
