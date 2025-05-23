<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-900">Edit Option: {{ option.name }}</h1>
        <RouterLink
          :to="`/admin/parts/${part.id}/edit`"
          class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        >
          Back to Part
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
            <label for="base_price" class="block text-sm font-medium text-gray-700">Base Price (EUR)</label>
            <div class="mt-1 relative rounded-md shadow-sm">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="text-gray-500 sm:text-sm">€</span>
              </div>
              <input
                type="number"
                name="base_price"
                id="base_price"
                v-model="form.base_price"
                class="p-2 focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                placeholder="0.00"
                step="0.01"
                min="0"
                required
              />
            </div>
          </div>

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

          <CheckboxInput
            label="In Stock"
            id="in_stock"
            v-model="form.in_stock"
          />

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
              Delete Option
            </button>
            <FormButtons
              :processing="processing"
              submit-text="Update Option"
              loading-text="Saving..."
              @cancel="router.push(`/admin/parts/${part.id}/edit`)"
            />
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <DeleteConfirmationModal
    :show="showDeleteModal"
    title="Delete Option"
    :message="`Are you sure you want to delete &quot;${option.name}&quot;? This action cannot be undone.`"
    :processing="deleteProcessing"
    @confirm="deleteOption"
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
const option = ref({});
const part = ref({});
const product = ref({});
const showDeleteModal = ref(false);
const deleteProcessing = ref(false);

const form = reactive({
  name: '',
  description: '',
  base_price: 0,
  display_order: 0,
  in_stock: true,
  active: true
});

const processing = ref(false);
const errors = reactive({});
const successMessage = ref('');

onMounted(async () => {
  try {
    const optionId = route.params.id;
    const response = await axios.get(`/admin/options/${optionId}`);
    option.value = response.data.option;
    part.value = response.data.part;
    product.value = response.data.product;

    // Initialize form with option data
    form.name = option.value.name;
    form.description = option.value.description || '';
    form.base_price = option.value.base_price;
    form.display_order = option.value.display_order;
    form.in_stock = option.value.in_stock;
    form.active = option.value.active;
  } catch (error) {
    console.error('Failed to fetch option details:', error);
  }
});

async function submit() {
  processing.value = true;
  successMessage.value = '';
  Object.keys(errors).forEach(key => delete errors[key]);

  try {
    await axios.put(`/admin/options/${option.value.id}`, form);
    processing.value = false;
    successMessage.value = 'Option updated successfully.';

    // Refresh option data
    const response = await axios.get(`/admin/options/${option.value.id}`);
    option.value = response.data.option;
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

async function deleteOption() {
  deleteProcessing.value = true;

  try {
    await axios.delete(`/admin/options/${option.value.id}`);
    deleteProcessing.value = false;
    showDeleteModal.value = false;

    // Redirect to part edit page
    router.push({
      path: `/admin/parts/${part.value.id}/edit`,
      query: { success: 'Option deleted successfully.' }
    });
  } catch (error) {
    deleteProcessing.value = false;
    console.error('Failed to delete option:', error);
    errors.general = 'Failed to delete option. Please try again.';
  }
}
</script>
