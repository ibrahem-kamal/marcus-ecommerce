<template>
  <div class="flex justify-end">
    <button
      type="button"
      class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-3"
      @click="$emit('cancel')"
    >
      Cancel
    </button>
    <button
      type="submit"
      class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
      :disabled="processing"
    >
      {{ processing ? loadingText : submitText }}
    </button>
  </div>
</template>

<script setup>
defineProps({
  processing: {
    type: Boolean,
    default: false
  },
  submitText: {
    type: String,
    default: 'Save'
  },
  loadingText: {
    type: String,
    default: 'Saving...'
  }
});

defineEmits(['cancel']);
</script>