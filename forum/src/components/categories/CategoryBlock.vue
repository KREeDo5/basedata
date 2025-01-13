<script setup>
import { defineProps, defineEmits, ref } from 'vue'

import Button from '../core/Button.vue'
import CategoryItem from './CategoryItem.vue'

const props = defineProps({
  categoryList: Array,
})

const emits = defineEmits(['openCreateThreadModal'])

const expandedCategories = ref([])

const toggleCategory = (categoryId) => {
  if (expandedCategories.value.includes(categoryId)) {
    expandedCategories.value = expandedCategories.value.filter((id) => id !== categoryId)
  } else {
    expandedCategories.value.push(categoryId)
  }
}

const shouldShowCategory = (category) => {
  return (
    category.id_parent_category === 0 ||
    category.id_parent_category === undefined ||
    category.id_parent_category === null
  )
}

const createModalOpen = () => {
  emits('openCreateThreadModal')
}
</script>

<template>
  <div class="h-min w-[320px] bg-base-darkgrey rounded-[20px] py-5 px-4 mx-9">
    <Button class="w-full" text="Создать тред" @click="createModalOpen" />
    <div class="space-y-3 mt-2">
      <CategoryItem
        v-for="category in categoryList"
        :key="category.id"
        :title="category.title"
        :hasSubcategories="Boolean(category.has_subcategories)"
        :parentCategoryId="category.id_parent_category"
        :categoryId="category.id"
        :categoryList="categoryList"
        :onToggle="toggleCategory"
        v-show="shouldShowCategory(category)"
      />
    </div>
  </div>
</template>

<style scoped></style>
