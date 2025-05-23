<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-900">Edit Part: {{ part.name }}</h1>
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
              class="h-4 w-4 p-2 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
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
              class="bg-white py-2 px-4 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 mr-3"
              @click="confirmDelete"
            >
              Delete Part
            </button>
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
              {{ processing ? 'Saving...' : 'Update Part' }}
            </button>
          </div>
        </form>
      </div>

      <!-- Options Section -->
      <div class="mt-8">
        <div class="flex justify-between items-center">
          <h2 class="text-xl font-semibold text-gray-900">Options</h2>
          <RouterLink
            :to="`/admin/parts/${part.id}/options/create`"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Add Option
          </RouterLink>
        </div>

        <div class="mt-4 bg-white shadow overflow-hidden sm:rounded-md">
          <ul role="list" class="divide-y divide-gray-200">
            <li v-if="part.options && part.options.length === 0" class="px-6 py-4 text-center text-gray-500">
              No options found. Add options to this part to get started.
            </li>
            <li v-for="option in part.options" :key="option.id" class="px-6 py-4">
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="text-lg font-medium text-gray-900">{{ option.name }}</h3>
                  <p class="mt-1 text-sm text-gray-500">{{ option.description || 'No description' }}</p>
                  <div class="mt-2 flex items-center text-sm text-gray-500">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                      €{{ option.base_price }}
                    </span>
                    <span class="ml-4 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                      :class="option.in_stock ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                      {{ option.in_stock ? 'In Stock' : 'Out of Stock' }}
                    </span>
                    <span class="ml-4 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                      :class="option.active ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'">
                      {{ option.active ? 'Active' : 'Inactive' }}
                    </span>
                  </div>
                </div>
                <div class="flex space-x-2">
                  <RouterLink
                    :to="`/admin/options/${option.id}/edit`"
                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  >
                    Edit Option
                  </RouterLink>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div v-if="showDeleteModal" class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
      <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" style="transform: translateY(0)">
        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
              <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
              <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                Delete Part
              </h3>
              <div class="mt-2">
                <p class="text-sm text-gray-500">
                  Are you sure you want to delete "{{ part.name }}"? This action cannot be undone.
                </p>
                <p class="text-sm text-red-500 mt-2">
                  Warning: This will also delete all options associated with this part.
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
          <button 
            type="button" 
            class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-150"
            @click="deletePart"
            :disabled="deleteProcessing"
          >
            {{ deleteProcessing ? 'Deleting...' : 'Delete' }}
          </button>
          <button 
            type="button" 
            class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors duration-150"
            @click="showDeleteModal = false"
          >
            Cancel
          </button>
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
const part = ref({});
const product = ref({});
const showDeleteModal = ref(false);
const deleteProcessing = ref(false);

const form = reactive({
  name: '',
  description: '',
  display_order: 0,
  required: false,
  active: true
});

const processing = ref(false);
const errors = reactive({});
const successMessage = ref('');

onMounted(async () => {
  try {
    const partId = route.params.id;
    const response = await axios.get(`/admin/parts/${partId}`);
    part.value = response.data.part;
    product.value = response.data.product;

    // Initialize form with part data
    form.name = part.value.name;
    form.description = part.value.description || '';
    form.display_order = part.value.display_order;
    form.required = part.value.required;
    form.active = part.value.active;
  } catch (error) {
    console.error('Failed to fetch part details:', error);
  }
});

async function submit() {
  processing.value = true;
  successMessage.value = '';
  Object.keys(errors).forEach(key => delete errors[key]);

  try {
    await axios.put(`/admin/parts/${part.value.id}`, form);
    processing.value = false;
    successMessage.value = 'Part updated successfully.';

    // Refresh part data
    const response = await axios.get(`/admin/parts/${part.value.id}`);
    part.value = response.data.part;
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

function confirmDelete() {
  showDeleteModal.value = true;
}

async function deletePart() {
  deleteProcessing.value = true;

  try {
    await axios.delete(`/admin/parts/${part.value.id}`);
    deleteProcessing.value = false;
    showDeleteModal.value = false;

    // Redirect to product edit page
    router.push({
      path: `/admin/products/${product.value.id}/edit`,
      query: { success: 'Part deleted successfully.' }
    });
  } catch (error) {
    deleteProcessing.value = false;
    console.error('Failed to delete part:', error);
    errors.general = 'Failed to delete part. Please try again.';
  }
}
</script>
