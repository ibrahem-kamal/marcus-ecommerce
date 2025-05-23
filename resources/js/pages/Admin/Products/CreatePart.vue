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

            <FormButtons
              :processing="processing"
              submit-text="Save Part"
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
