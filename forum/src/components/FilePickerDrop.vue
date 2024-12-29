<script setup>
import { ref } from 'vue'

const props = defineProps({
  onFileSelectedCallback: Function,
})

const imagePreview = ref(null)

const onFileSelected = (event) => {
  const file = event.target.files[0]
  if (file && file.type.startsWith('image/')) {
    const reader = new FileReader()
    reader.onload = () => {
      imagePreview.value = reader.result
      props.onFileSelectedCallback(reader.result) // Вызов функции обратного вызова
    }
    reader.readAsDataURL(file)
  } else {
    alert('Please select a valid image file.')
  }
}
</script>

<template>
  <div class="flex items-center justify-center w-full">
    <label
      for="dropzone-file"
      class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50"
    >
      <div v-if="!imagePreview" class="flex flex-col items-center justify-center pt-5 pb-6">
        <img alt="upload" src="/upload.svg" class="mb-4 w-8 h-8" aria-hidden="true" />
        <p class="mb-2 text-sm text-gray-500">
          <span class="font-w600">Click to upload</span> or drag and drop
        </p>
      </div>

      <div v-else class="flex items-center justify-center w-full h-full overflow-hidden">
        <img
          :src="imagePreview"
          alt="Selected image"
          class="object-contain w-full h-full rounded-lg"
        />
      </div>

      <input
        id="dropzone-file"
        type="file"
        class="hidden"
        @change="onFileSelected"
        accept="image/*"
      />
    </label>
  </div>
</template>
