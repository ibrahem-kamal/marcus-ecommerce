<template>
    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
          <h1 class="text-2xl font-semibold text-gray-900">Create Product</h1>
          <RouterLink
            to="/admin/products"
            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Back to Products
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

            <CheckboxInput
              label="Active"
              id="active"
              v-model="form.active"
            />

            <FileInput
              label="Product Image"
              id="image"
              v-model="form.image"
            />

            <FormButtons
              :processing="processing"
              submit-text="Save Product"
              loading-text="Saving..."
              @cancel="router.push('/admin/products')"
            />
          </form>
        </div>
      </div>
    </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { RouterLink, useRouter } from 'vue-router';
import axios from 'axios';
import AdminLayout from '@/layouts/AdminLayout.vue';
import ErrorDisplay from '@/pages/Admin/components/ErrorDisplay.vue';
import TextInput from '@/pages/Admin/components/TextInput.vue';
import TextareaInput from '@/pages/Admin/components/TextareaInput.vue';
import CheckboxInput from '@/pages/Admin/components/CheckboxInput.vue';
import FileInput from '@/pages/Admin/components/FileInput.vue';
import FormButtons from '@/pages/Admin/components/FormButtons.vue';

const router = useRouter();
const form = reactive({
  name: '',
  description: '',
  active: true,
  image: null
});

const processing = ref(false);
const errors = reactive({});

async function submit() {
  processing.value = true;
  Object.keys(errors).forEach(key => delete errors[key]);

  try {
    // Use FormData to handle file uploads
    const formData = new FormData();
    formData.append('name', form.name);
    formData.append('description', form.description || '');
    formData.append('active', form.active ? '1' : '0');

    if (form.image) {
      formData.append('image', form.image);
    }

    await axios.post('/admin/products', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });

    processing.value = false;
    router.push('/admin/products');
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
