<script setup>
import ThreadItem from '../components/ThreadItem.vue'
</script>

<template>
  <div class="h-min bg-base-darkgrey rounded-[20px] p-6">
    <div class="space-y-4">
      <ThreadItem title="Не видит видеокарту" commentsCount="6" :isClosed="false" />
      <ThreadItem title="Оцените сборку" commentsCount="231" :isClosed="true" />
    </div>
  </div>
</template>

<style scoped></style>
