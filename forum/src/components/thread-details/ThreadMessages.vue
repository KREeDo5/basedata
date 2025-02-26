<script setup>
import MessageItem from './MessageItem.vue'

const props = defineProps({
  messages: Array,
})
</script>

<template>
  <MessageItem
    v-for="message in props.messages"
    :key="message.id"
    :id="message.id"
    :text="message.text"
    :createdAt="message.created_at"
    :user="message.user"
    :images="message.images"
  />
</template>
