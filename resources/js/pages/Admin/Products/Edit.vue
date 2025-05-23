<template>
    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
          <h1 class="text-2xl font-semibold text-gray-900">Edit Product: {{ product.name }}</h1>
          <RouterLink
            to="/admin/products"
            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Back to Products
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

            <div v-if="successMessage" class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
              <div class="flex">
                <div class="flex-shrink-0">
                  <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div class="ml-3">
                  <p class="text-sm font-medium text-green-800">
                    {{ successMessage }}
                  </p>
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
                @click="router.push('/admin/products')"
              >
                Cancel
              </button>
              <button
                type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                :disabled="processing"
              >
                {{ processing ? 'Saving...' : 'Update Product' }}
              </button>
            </div>
          </form>
        </div>

        <!-- Parts Section -->
        <div class="mt-8">
          <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-900">Parts</h2>
            <RouterLink
              :to="`/admin/products/${product.id}/parts/create`"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
              Add Part
            </RouterLink>
          </div>

          <div class="mt-4 bg-white shadow overflow-hidden sm:rounded-md">
            <ul role="list" class="divide-y divide-gray-200">
              <li v-if="product.parts && product.parts.length === 0" class="px-6 py-4 text-center text-gray-500">
                No parts found. Add parts to this product to get started.
              </li>
              <li v-for="part in product.parts" :key="part.id" class="px-6 py-4">
                <div class="flex items-center justify-between">
                  <div>
                    <h3 class="text-lg font-medium text-gray-900">{{ part.name }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ part.description || 'No description' }}</p>
                    <div class="mt-2 flex items-center text-sm text-gray-500">
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                        :class="part.required ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'">
                        {{ part.required ? 'Required' : 'Optional' }}
                      </span>
                      <span class="ml-4">{{ part.options ? part.options.length : 0 }} options</span>
                    </div>
                  </div>
                  <div class="flex space-x-2">
                    <RouterLink
                      :to="`/admin/parts/${part.id}/options/create`"
                      class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                      Add Option
                    </RouterLink>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue';
import { RouterLink, useRouter, useRoute } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const route = useRoute();
const product = ref({});

const form = reactive({
  name: '',
  description: '',
  active: false
});

const processing = ref(false);
const errors = reactive({});
const successMessage = ref('');

onMounted(async () => {
  try {
    const productId = route.params.id;
    const response = await axios.get(`/admin/products/${productId}`);
    product.value = response.data.product;

    // Initialize form with product data
    form.name = product.value.name;
    form.description = product.value.description || '';
    form.active = product.value.active;

    // Check for flash message from URL query params if available
    if (route.query.success) {
      successMessage.value = route.query.success;
    }
  } catch (error) {
    console.error('Failed to fetch product details:', error);
  }
});

async function submit() {
  processing.value = true;
  successMessage.value = '';
  Object.keys(errors).forEach(key => delete errors[key]);

  try {
    await axios.put(`/admin/products/${product.value.id}`, form);
    processing.value = false;
    successMessage.value = 'Product updated successfully.';
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
