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
          <ErrorDisplay :errors="errors" />
          <SuccessMessage :message="successMessage" />

          <TextInput
            label="Name"
            id="name"
            v-model="form.name"
            required
          />

          <TextareaInput
            label="Description"
            id="description"
            v-model="form.description"
          />

          <div>
            <TextInput
              label="Display Order"
              id="display_order"
              v-model="form.display_order"
              type="number"
              min="0"
            />
            <p class="mt-1 text-sm text-gray-500">Lower numbers will be displayed first</p>
          </div>

          <div>
            <CheckboxInput
              label="Required"
              id="required"
              v-model="form.required"
            />
            <p class="ml-4 text-sm text-gray-500">If checked, customers must select an option for this part</p>
          </div>

          <CheckboxInput
            label="Active"
            id="active"
            v-model="form.active"
          />

          <div class="flex justify-end">
            <button
              type="button"
              class="bg-white py-2 px-4 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 mr-3"
              @click="confirmDelete"
            >
              Delete Part
            </button>
            <FormButtons
              :processing="processing"
              submit-text="Update Part"
              loading-text="Saving..."
              @cancel="router.push(`/admin/products/${product.id}/edit`)"
            />
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
  <DeleteConfirmationModal
    :show="showDeleteModal"
    title="Delete Part"
    :message="`Are you sure you want to delete &quot;${part.name}&quot;? This action cannot be undone.
    Warning: This will also delete all options associated with this part.`"
    :processing="deleteProcessing"
    @confirm="deletePart"
    @cancel="showDeleteModal = false"
  />
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue';
import { RouterLink, useRouter, useRoute } from 'vue-router';
import axios from 'axios';
import ErrorDisplay from '@/pages/Admin/components/ErrorDisplay.vue';
import SuccessMessage from '@/pages/Admin/components/SuccessMessage.vue';
import TextInput from '@/pages/Admin/components/TextInput.vue';
import TextareaInput from '@/pages/Admin/components/TextareaInput.vue';
import CheckboxInput from '@/pages/Admin/components/CheckboxInput.vue';
import FormButtons from '@/pages/Admin/components/FormButtons.vue';
import DeleteConfirmationModal from '@/pages/Admin/components/DeleteConfirmationModal.vue';

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
