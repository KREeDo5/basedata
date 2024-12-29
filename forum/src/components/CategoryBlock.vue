<script setup>
import { defineProps } from 'vue'

import Button from './Button.vue'
import CategoryItem from './CategoryItem.vue'

const props = defineProps({
  categoryList: {
    type: Array,
    required: true,
  },
})
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
      />
    </div>
  </div>
</template>

<style scoped></style>
