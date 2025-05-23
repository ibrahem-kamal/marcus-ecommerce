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

                    <CheckboxInput
                        label="Active"
                        id="active"
                        v-model="form.active"
                    />

                    <FormButtons
                        :processing="processing"
                        submit-text="Update Product"
                        loading-text="Saving..."
                        @cancel="router.push('/admin/products')"
                    />
                </form>
            </div>

            <!-- Advanced Configuration Section -->
            <div class="mt-8">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-900">Advanced Configuration</h2>
                    <div class="flex space-x-2">
                        <RouterLink
                            :to="`/admin/products/${product.id}/combinations`"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" viewBox="0 0 20 20"
                                 fill="currentColor">
                                <path
                                    d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            Option Combinations
                        </RouterLink>
                        <RouterLink
                            :to="`/admin/products/${product.id}/price-rules`"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" viewBox="0 0 20 20"
                                 fill="currentColor">
                                <path
                                    d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"
                                      clip-rule="evenodd" />
                            </svg>
                            Price Rules
                        </RouterLink>
                    </div>
                </div>
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
                        <li v-if="product.parts && product.parts.length === 0"
                            class="px-6 py-4 text-center text-gray-500">
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
                                        :to="`/admin/parts/${part.id}/edit`"
                                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5"
                                             viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                        Edit
                                    </RouterLink>
                                    <RouterLink
                                        :to="`/admin/parts/${part.id}/options/create`"
                                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5"
                                             viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                  d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                                  clip-rule="evenodd" />
                                        </svg>
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
import ErrorDisplay from '@/pages/Admin/components/ErrorDisplay.vue';
import SuccessMessage from '@/pages/Admin/components/SuccessMessage.vue';
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
