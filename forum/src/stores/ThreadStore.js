import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useThreadStore = defineStore('threadStore', () => {
  const threads = ref([
    {
      id: 1,
      title: 'Не видит видеокарту',
      isClosed: false,
      commentsCount: 6,
      userAvatar: '/path/to/avatar1.png',
      userName: 'User1',
      registarionDate: '01.01.2022',
    },
    {
      id: 2,
      title: 'Оцените сборку',
      isClosed: true,
      commentsCount: 231,
      userAvatar: '/path/to/avatar2.png',
      userName: 'Gabe Newell',
      registarionDate: '01.01.2020',
    },
  ])

  return { threads }
})
