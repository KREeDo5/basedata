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
  },
}
</script>

<template>
  <div
    v-if="isVisible"
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
  >
    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
      <p class="text-red-600 font-semibold mb-4">{{ message }}</p>
      <button
        @click="$emit('close')"
        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
      >
        Закрыть
      </button>
    </div>
  </div>
</template>
