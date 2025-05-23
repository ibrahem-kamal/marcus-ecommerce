<template>
  <div class="flex items-center">
    <input
      :id="id"
      :name="name || id"
      type="checkbox"
      :checked="modelValue"
      @change="$emit('update:modelValue', $event.target.checked)"
      class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
    />
    <label :for="id" class="ml-2 block text-sm text-gray-900">
      {{ label }}
    </label>
  </div>
</template>

<script setup>
defineProps({
  label: {
    type: String,
    required: true
  },
  modelValue: {
    type: Boolean,
    default: false
  },
  id: {
    type: String,
    required: true
  },
  name: {
    type: String,
    default: null
  }
});

defineEmits(['update:modelValue']);
</script>
