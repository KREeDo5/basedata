<script setup>
import Button from './Button.vue'
import CategoryItem from './CategoryItem.vue'
</script>

<template>
  <div class="h-min w-[320px] bg-base-darkgrey rounded-[20px] py-5 px-4 mx-9">
    <Button class="w-full" text="Создать тред" />
    <div class="space-y-3 mt-2">
      <CategoryItem title="Лекции" />
      <CategoryItem title="Компьютеры" />
      <CategoryItem title="Лабораторные работы" />
    </div>
  </div>
</template>

<style scoped></style>
