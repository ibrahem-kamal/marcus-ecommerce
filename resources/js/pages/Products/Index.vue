<template>
  <div>
    <div class="pb-5 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
      <h3 class="text-lg leading-6 font-medium text-gray-900">Products</h3>
    </div>
    
    <div v-if="loading" class="mt-6 text-center">
      <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-indigo-500"></div>
      <p class="mt-2 text-sm text-gray-500">Loading products...</p>
    </div>
    
    <div v-else-if="error" class="mt-6 bg-red-50 p-4 rounded-md">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-medium text-red-800">Failed to load products</h3>
          <div class="mt-2 text-sm text-red-700">
            <p>{{ error }}</p>
          </div>
        </div>
      </div>
    </div>
    
    <div v-else-if="products.length === 0" class="mt-6 text-center">
      <p class="text-sm text-gray-500">No products available.</p>
    </div>
    
    <div v-else class="mt-6 grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
      <div v-for="product in products" :key="product.id" class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <img v-if="product.image_path" :src="product.image_path" alt="Product image" class="h-12 w-12 rounded-md object-cover" />
              <div v-else class="h-12 w-12 rounded-md bg-gray-200 flex items-center justify-center">
                <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
            </div>
            <div class="ml-5 w-0 flex-1">
              <h3 class="text-lg font-medium text-gray-900 truncate">{{ product.name }}</h3>
            </div>
          </div>
          <div class="mt-4">
            <p class="text-sm text-gray-500 line-clamp-3">{{ product.description }}</p>
          </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
          <div class="text-sm">
            <router-link :to="{ name: 'products.show', params: { id: product.id } }" class="font-medium text-indigo-600 hover:text-indigo-500">
              Configure Product <span aria-hidden="true">&rarr;</span>
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const products = ref([]);
const loading = ref(true);
const error = ref(null);

onMounted(async () => {
  try {
    const response = await axios.get('/products');
    products.value = response.data.products;
  } catch (err) {
    console.error('Failed to fetch products:', err);
    error.value = err.response?.data?.message || 'An error occurred while fetching products.';
  } finally {
    loading.value = false;
  }
});
</script>