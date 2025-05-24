<template>
  <div>
    <label :for="id" class="block text-sm font-medium text-gray-700">{{ label }}</label>
    <div class="mt-1 flex items-center">
      <input
        type="file"
        :name="name || id"
        :id="id"
        @change="handleFileChange"
        class="sr-only"
        :accept="accept"
        :required="required"
        ref="fileInput"
      />
      <button
        type="button"
        @click="$refs.fileInput.click()"
        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
      >
        Choose File
      </button>
      <span class="ml-3 text-sm text-gray-500">{{ fileName || 'No file selected' }}</span>
    </div>
    <div v-if="previewUrl" class="mt-2">
      <img :src="previewUrl" alt="Preview" class="h-32 w-auto object-cover rounded-md" />
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  label: {
    type: String,
    required: true
  },
  modelValue: {
    type: [File, null,String],
    default: null
  },
  id: {
    type: String,
    required: true
  },
  name: {
    type: String,
    default: null
  },
  accept: {
    type: String,
    default: 'image/*'
  },
  required: {
    type: Boolean,
    default: false
  }
});

const emit = defineEmits(['update:modelValue']);
const fileName = ref('');
const previewUrl = ref('');
const fileInput = ref(null);

// Handle when a file is selected
function handleFileChange(event) {
  const file = event.target.files[0];
  if (file) {
    fileName.value = file.name;
    emit('update:modelValue', file);
    
    // Create preview URL for images
    if (file.type.startsWith('image/')) {
      previewUrl.value = URL.createObjectURL(file);
    } else {
      previewUrl.value = '';
    }
  } else {
    fileName.value = '';
    previewUrl.value = '';
    emit('update:modelValue', null);
  }
}

// If modelValue is a string (existing image path), show it as preview
watch(() => props.modelValue, (newValue) => {
  if (typeof newValue === 'string' && newValue) {
    previewUrl.value = newValue;
    const pathParts = newValue.split('/');
    fileName.value = pathParts[pathParts.length - 1];
  }
}, { immediate: true });
</script>