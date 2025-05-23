<template>
    <div class="py-8">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Admin Dashboard</h1>
        <p class="text-gray-500 text-sm mb-8">Welcome to your product management dashboard</p>

        <div class="mt-6">
          <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
            <div class="px-6 py-5 bg-gradient-to-r from-blue-50 to-indigo-50">
              <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                  <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                </svg>
                Overview
              </h3>
              <p class="mt-1 max-w-2xl text-sm text-gray-500">
                Quick summary of your product catalog.
              </p>
            </div>
            <div class="border-t border-gray-100">
              <dl>
                <div class="px-6 py-5 flex justify-between items-center hover:bg-gray-50 transition-colors duration-150">
                  <dt class="text-sm font-medium text-gray-500 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                    </svg>
                    Total Products
                  </dt>
                  <dd class="text-2xl font-bold text-blue-600">
                    {{ stats.productCount }}
                  </dd>
                </div>
              </dl>
            </div>
          </div>
        </div>

        <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2">
          <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100 transition-all duration-200 hover:shadow-xl hover:border-blue-100">
            <div class="px-6 py-6">
              <div class="flex items-center">
                <div class="ml-5 w-0 flex-1">
                  <h3 class="text-lg font-semibold text-gray-900 mb-1">
                    Manage Products
                  </h3>
                  <p class="text-sm text-gray-500 mb-3">
                    View and manage your existing products
                  </p>
                  <RouterLink to="/admin/products" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                    View Products
                  </RouterLink>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100 transition-all duration-200 hover:shadow-xl hover:border-green-100">
            <div class="px-6 py-6">
              <div class="flex items-center">
                <div class="ml-5 w-0 flex-1">
                  <h3 class="text-lg font-semibold text-gray-900 mb-1">
                    Add New Product
                  </h3>
                  <p class="text-sm text-gray-500 mb-3">
                    Create a new product in your catalog
                  </p>
                  <RouterLink to="/admin/products/create" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-150">
                    Create Product
                  </RouterLink>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { RouterLink } from 'vue-router';
import axios from 'axios';

const stats = ref({
  productCount: 0
});

onMounted(async () => {
  try {
    const response = await axios.get('/admin/dashboard');
    stats.value = response.data.stats;
  } catch (error) {
    console.error('Failed to fetch dashboard stats:', error);
  }
});
</script>
