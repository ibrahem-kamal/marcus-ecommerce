<template>
    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
          <h1 class="text-2xl font-semibold text-gray-900">Add Part to {{ product.name }}</h1>
          <RouterLink
            :to="`/admin/products/${product.id}/edit`"
            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Back to Product
          </RouterLink>
        </div>

        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
          <form @submit.prevent="submit" class="p-6 space-y-6">
            <div v-if="Object.keys(errors).length > 0" class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
              <div class="flex">
                <div class="flex-shrink-0">
                  <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div class="ml-3">
                  <h3 class="text-sm font-medium text-red-800">
                    There were errors with your submission
                  </h3>
                  <div class="mt-2 text-sm text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                      <li v-for="(error, field) in errors" :key="field">{{ error }}</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>

            <div>
              <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
              <input
                type="text"
                name="name"
                id="name"
                v-model="form.name"
                class="mt-1 p-2 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                required
              />
            </div>

            <div>
              <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
              <textarea
                id="description"
                name="description"
                rows="3"
                v-model="form.description"
                class="mt-1 p-2 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
              ></textarea>
            </div>

            <div>
              <label for="display_order" class="block text-sm font-medium text-gray-700">Display Order</label>
              <input
                type="number"
                name="display_order"
                id="display_order"
                v-model="form.display_order"
                class="mt-1 p-2 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                min="0"
              />
              <p class="mt-1 text-sm text-gray-500">Lower numbers will be displayed first</p>
            </div>

            <div class="flex items-center">
              <input
                id="required"
                name="required"
                type="checkbox"
                v-model="form.required"
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
              />
              <label for="required" class="ml-2 block text-sm text-gray-900">
                Required
              </label>
              <p class="ml-4 text-sm text-gray-500">If checked, customers must select an option for this part</p>
            </div>

            <div class="flex items-center">
              <input
                id="active"
                name="active"
                type="checkbox"
                v-model="form.active"
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
              />
              <label for="active" class="ml-2 block text-sm text-gray-900">
                Active
              </label>
            </div>

            <div class="flex justify-end">
              <button
                type="button"
                class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-3"
                @click="router.push(`/admin/products/${product.id}/edit`)"
              >
                Cancel
              </button>
              <button
                type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                :disabled="processing"
              >
                {{ processing ? 'Saving...' : 'Save Part' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue';
import { RouterLink, useRouter, useRoute } from 'vue-router';
import axios from 'axios';
import AdminLayout from '@/layouts/AdminLayout.vue';

const router = useRouter();
const route = useRoute();
const product = ref({});

const form = reactive({
  name: '',
  description: '',
  display_order: 0,
  required: false,
  active: true
});

const processing = ref(false);
const errors = reactive({});

onMounted(async () => {
  try {
    const productId = route.params.id;
    const response = await axios.get(`/admin/products/${productId}`);
    product.value = response.data.product;
  } catch (error) {
    console.error('Failed to fetch product details:', error);
  }
});

async function submit() {
  processing.value = true;
  Object.keys(errors).forEach(key => delete errors[key]);

  try {
    await axios.post(`/admin/products/${product.value.id}/parts`, form);
    processing.value = false;
    router.push(`/admin/products/${product.value.id}/edit`);
  } catch (error) {
    processing.value = false;
    if (error.response && error.response.data && error.response.data.errors) {
      Object.entries(error.response.data.errors).forEach(([key, value]) => {
        errors[key] = Array.isArray(value) ? value[0] : value;
      });
    } else {
      errors.general = 'An error occurred. Please try again.';
    }
  }
}
</script>
