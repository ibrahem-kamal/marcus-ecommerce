<template>
  <div>
    <label :for="id" class="block text-sm font-medium text-gray-700">{{ label }}</label>
    <textarea
      :name="name || id"
      :id="id"
      :rows="rows"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      class="mt-1 p-2 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
      :required="required"
      :placeholder="placeholder"
    ></textarea>
  </div>
</template>

<script setup>
defineProps({
  label: {
    type: String,
    required: true
  },
  modelValue: {
    type: String,
    default: ''
  },
  id: {
    type: String,
    required: true
  },
  name: {
    type: String,
    default: null
  },
  rows: {
    type: Number,
    default: 3
  },
  required: {
    type: Boolean,
    default: false
  },
  placeholder: {
    type: String,
    default: ''
  }
});

defineEmits(['update:modelValue']);
</script>
