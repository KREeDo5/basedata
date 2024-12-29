import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useCategoryStore = defineStore('categoryStore', () => {
  const categories = ref([
    {
      id: 1,
      title: 'Лекции',
      hasSubcategories: 1,
      parentCategoryId: 0,
    },
    {
      id: 2,
      title: 'Компьютеры',
      hasSubcategories: 0,
      parentCategoryId: 0,
    },
    {
      id: 3,
      title: 'Лабораторные работы',
      hasSubcategories: 0,
      parentCategoryId: 0,
    },
    {
      id: 4,
      title: 'Практические занятия',
      hasSubcategories: 0,
      parentCategoryId: 0,
    },
    {
      id: 5,
      title: 'Стипендии',
      hasSubcategories: 0,
      parentCategoryId: 0,
    },
    {
      id: 6,
      title: 'ТАиФЯ',
      hasSubcategories: 0,
      parentCategoryId: 1,
    },
    {
      id: 7,
      title: 'Базы Данных',
      hasSubcategories: 0,
      parentCategoryId: 1,
    },
  ])

  return { categories }
})
