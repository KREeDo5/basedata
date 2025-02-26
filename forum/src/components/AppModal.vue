<template>
  <div
    v-if="isVisible"
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
  >
    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
      <p class="text-xl text-base-red font-w500 mb-4">{{ message }}</p>
      <div class="relative flex items-center justify-center">
        <!-- Лоадер -->
        <div
          class="w-12 h-12 border-2 border-gray-300 border-t-black rounded-full animate-spin"
        ></div>
        <!-- Счётчик -->
        <span class="absolute text-xl font-w600">{{ countdown }}</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    isVisible: {
      type: Boolean,
      required: true,
    },
    message: {
      type: String,
      required: true,
    },
    countdown: {
      type: Number,
      required: true,
    },
  },
}
</script>

<style scoped>
/* Если нужны кастомные анимации, можно оставить их здесь */
</style>
