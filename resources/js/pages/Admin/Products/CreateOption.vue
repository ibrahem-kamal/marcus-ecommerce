<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-900">Add Option to {{ part.name }}</h1>
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
                class="focus:ring-blue-500 p-2 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
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

          <FormButtons
            :processing="processing"
            submit-text="Save Option"
            loading-text="Saving..."
            @cancel="router.push(`/admin/products/${product.id}/edit`)"
          />
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
import ErrorDisplay from '@/pages/Admin/components/ErrorDisplay.vue';
import TextInput from '@/pages/Admin/components/TextInput.vue';
import TextareaInput from '@/pages/Admin/components/TextareaInput.vue';
import CheckboxInput from '@/pages/Admin/components/CheckboxInput.vue';
import FormButtons from '@/pages/Admin/components/FormButtons.vue';

const router = useRouter();
const route = useRoute();
const part = ref({});
const product = ref({});

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

onMounted(async () => {
  try {
    const partId = route.params.id;
    const response = await axios.get(`/admin/parts/${partId}`);
    part.value = response.data.part;
    product.value = response.data.part.product_type;
  } catch (error) {
    console.error('Failed to fetch part details:', error);
  }
});

async function submit() {
  processing.value = true;
  Object.keys(errors).forEach(key => delete errors[key]);

  try {
    await axios.post(`/admin/parts/${part.value.id}/options`, form);
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
