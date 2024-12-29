<script setup>
import { defineProps, ref } from 'vue'
import SubCategoryItem from './SubCategoryItem.vue'

const props = defineProps({
  categoryId: Number,
  title: String,
  hasSubcategories: Boolean,
  parentCategoryId: Number,
  subcategories: {
    type: Array,
    default: () => [],
  },
  onToggle: Function,
})

const isRotated = ref(false)

const toggleRotation = () => {
  isRotated.value = !isRotated.value
  if (props.onToggle) {
    props.onToggle(props.categoryId)
  }
}
</script>

<template>
  <div>
    <div
      class="flex justify-between items-center py-2 pl-3 pr-2 rounded-[20px] hover:bg-base-asphalt group"
    >
      <div class="text-base text-base-grey2">{{ title }}</div>
      <svg
        v-if="hasSubcategories"
        @click="toggleRotation"
        :class="{ 'rotate-180': isRotated, 'rotate-0': !isRotated, 'opacity-100': isRotated }"
        class="w-4 h-6 text-base-grey2 opacity-0 group-hover:opacity-100 transition-transform duration-300"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 10 6"
      >
        <path
          stroke="currentColor"
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="1"
          d="m1 1 4 4 4-4"
        />
      </svg>
    </div>
    <div v-if="isRotated" class="pl-6">
      <SubCategoryItem
        v-for="subcategory in subcategories"
        :key="subcategory.id"
        :categoryId="subcategory.id"
        :title="subcategory.title"
        :hasSubcategories="subcategory.hasSubcategories"
        :parentCategoryId="subcategory.parentCategoryId"
        :subcategories="subcategory.subcategories"
        :onToggle="onToggle"
      />
    </div>
  </div>
</template>

<style scoped>
.rotate-0 {
  transform: rotate(0deg);
}
.rotate-180 {
  transform: rotate(180deg);
}
</style>
