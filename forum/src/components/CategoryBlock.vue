<script setup>
import { defineProps, ref } from 'vue'

import Button from './Button.vue'
import CategoryItem from './CategoryItem.vue'

const props = defineProps({
  categoryList: {
    type: Array,
    required: true,
  },
})

const expandedCategories = ref([])

const toggleCategory = (categoryId) => {
  if (expandedCategories.value.includes(categoryId)) {
    expandedCategories.value = expandedCategories.value.filter(id => id !== categoryId)
  } else {
    expandedCategories.value.push(categoryId)
  }
}

const getSubcategories = (categoryId) => {
  return props.categoryList.filter(category => category.parentCategoryId === categoryId)
}
</script>

<template>
  <div class="h-min w-[320px] bg-base-darkgrey rounded-[20px] py-5 px-4 mx-9">
    <Button class="w-full" text="Создать тред" />
    <div class="space-y-3 mt-2">
      <CategoryItem
        v-for="category in categoryList"
        :key="category.id"
        :title="category.title"
        :hasSubcategories="category.hasSubcategories"
        :parentCategoryId="category.parentCategoryId"
        :categoryId="category.id"
        :subcategories="getSubcategories(category.id)"
        :onToggle="toggleCategory"
        v-show="category.parentCategoryId === 0 || category.parentCategoryId === undefined"
      />
    </div>
  </div>
</template>

<style scoped></style>