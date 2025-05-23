<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-900">Price Rules for {{ product.name }}</h1>
        <RouterLink
          :to="`/admin/products/${product.id}/edit`"
          class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        >
          Back to Product
        </RouterLink>
      </div>
      
      <p class="mt-2 text-sm text-gray-500">
        Price rules allow you to define special pricing when certain options are selected together.
      </p>

      <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-6 py-5 border-b border-gray-200">
          <h3 class="text-lg font-medium text-gray-900">Add New Price Rule</h3>
        </div>
        <form @submit.prevent="submitPriceRule" class="p-6 space-y-6">
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

          <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
            <div class="sm:col-span-3">
              <label for="primary_option_id" class="block text-sm font-medium text-gray-700">Primary Option</label>
              <select
                id="primary_option_id"
                name="primary_option_id"
                v-model="priceRuleForm.primary_option_id"
                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                required
              >
                <option value="" disabled>Select an option</option>
                <optgroup v-for="part in product.parts" :key="part.id" :label="part.name">
                  <option v-for="option in part.options" :key="option.id" :value="option.id">
                    {{ option.name }}
                  </option>
                </optgroup>
              </select>
            </div>

            <div class="sm:col-span-3">
              <label for="dependent_option_id" class="block text-sm font-medium text-gray-700">Dependent Option</label>
              <select
                id="dependent_option_id"
                name="dependent_option_id"
                v-model="priceRuleForm.dependent_option_id"
                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                required
              >
                <option value="" disabled>Select an option</option>
                <optgroup v-for="part in product.parts" :key="part.id" :label="part.name">
                  <option v-for="option in part.options" :key="option.id" :value="option.id">
                    {{ option.name }}
                  </option>
                </optgroup>
              </select>
            </div>

            <div class="sm:col-span-3">
              <label for="adjustment_type" class="block text-sm font-medium text-gray-700">Adjustment Type</label>
              <select
                id="adjustment_type"
                name="adjustment_type"
                v-model="priceRuleForm.adjustment_type"
                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                required
              >
                <option value="" disabled>Select an adjustment type</option>
                <option value="fixed">Fixed Amount</option>
                <option value="percentage">Percentage</option>
              </select>
            </div>

            <div class="sm:col-span-3">
              <label for="price_adjustment" class="block text-sm font-medium text-gray-700">
                Price Adjustment {{ priceRuleForm.adjustment_type === 'percentage' ? '(%)' : '(EUR)' }}
              </label>
              <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <span class="text-gray-500 sm:text-sm">{{ priceRuleForm.adjustment_type === 'percentage' ? '%' : '€' }}</span>
                </div>
                <input
                  type="number"
                  name="price_adjustment"
                  id="price_adjustment"
                  v-model="priceRuleForm.price_adjustment"
                  class="p-2 focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                  :placeholder="priceRuleForm.adjustment_type === 'percentage' ? '10' : '10.00'"
                  :step="priceRuleForm.adjustment_type === 'percentage' ? '1' : '0.01'"
                  required
                />
              </div>
              <p class="mt-1 text-sm text-gray-500">
                {{ priceRuleForm.adjustment_type === 'percentage' ? 'Positive values increase the price, negative values decrease it.' : 'Positive values add to the price, negative values subtract from it.' }}
              </p>
            </div>

            <div class="sm:col-span-6">
              <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
              <textarea
                id="description"
                name="description"
                rows="2"
                v-model="priceRuleForm.description"
                class="mt-1 p-2 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
              ></textarea>
            </div>

            <div class="sm:col-span-6">
              <div class="flex items-center">
                <input
                  id="active"
                  name="active"
                  type="checkbox"
                  v-model="priceRuleForm.active"
                  class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                />
                <label for="active" class="ml-2 block text-sm text-gray-900">
                  Active
                </label>
              </div>
            </div>
          </div>

          <div class="flex justify-end">
            <button
              type="submit"
              class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              :disabled="processing"
            >
              {{ processing ? 'Saving...' : 'Add Price Rule' }}
            </button>
          </div>
        </form>
      </div>

      <!-- Existing Price Rules -->
      <div class="mt-8">
        <h2 class="text-xl font-semibold text-gray-900">Existing Price Rules</h2>
        
        <div class="mt-4 bg-white shadow overflow-hidden sm:rounded-lg">
          <ul role="list" class="divide-y divide-gray-200">
            <li v-if="priceRules.length === 0" class="px-6 py-4 text-center text-gray-500">
              No price rules found. Add your first price rule above.
            </li>
            <li v-for="rule in priceRules" :key="rule.id" class="px-6 py-4">
              <div class="flex items-center justify-between">
                <div>
                  <div class="flex items-center">
                    <span class="font-medium text-gray-900">{{ getOptionName(rule.primary_option_id) }}</span>
                    <span class="mx-2 text-gray-500">+</span>
                    <span class="font-medium text-gray-900">{{ getOptionName(rule.dependent_option_id) }}</span>
                    <span class="ml-2 text-gray-500">=</span>
                    <span class="ml-2 font-medium text-blue-600">
                      {{ formatPriceAdjustment(rule.price_adjustment, rule.adjustment_type) }}
                    </span>
                  </div>
                  <p class="mt-1 text-sm text-gray-500">{{ rule.description || 'No description' }}</p>
                  <div class="mt-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                      :class="rule.active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                      {{ rule.active ? 'Active' : 'Inactive' }}
                    </span>
                  </div>
                </div>
                <div class="flex space-x-2">
                  <button
                    @click="editPriceRule(rule)"
                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  >
                    Edit
                  </button>
                  <button
                    @click="deletePriceRule(rule)"
                    class="inline-flex items-center px-3 py-1.5 border border-red-300 text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                  >
                    Delete
                  </button>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Edit Price Rule Modal -->
    <div v-if="showEditModal" class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" style="transform: translateY(0)">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                  Edit Price Rule
                </h3>
                <div class="mt-4 space-y-4">
                  <div>
                    <label for="edit_primary_option_id" class="block text-sm font-medium text-gray-700">Primary Option</label>
                    <select
                      id="edit_primary_option_id"
                      name="edit_primary_option_id"
                      v-model="editForm.primary_option_id"
                      class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                      required
                    >
                      <option value="" disabled>Select an option</option>
                      <optgroup v-for="part in product.parts" :key="part.id" :label="part.name">
                        <option v-for="option in part.options" :key="option.id" :value="option.id">
                          {{ option.name }}
                        </option>
                      </optgroup>
                    </select>
                  </div>

                  <div>
                    <label for="edit_dependent_option_id" class="block text-sm font-medium text-gray-700">Dependent Option</label>
                    <select
                      id="edit_dependent_option_id"
                      name="edit_dependent_option_id"
                      v-model="editForm.dependent_option_id"
                      class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                      required
                    >
                      <option value="" disabled>Select an option</option>
                      <optgroup v-for="part in product.parts" :key="part.id" :label="part.name">
                        <option v-for="option in part.options" :key="option.id" :value="option.id">
                          {{ option.name }}
                        </option>
                      </optgroup>
                    </select>
                  </div>

                  <div>
                    <label for="edit_adjustment_type" class="block text-sm font-medium text-gray-700">Adjustment Type</label>
                    <select
                      id="edit_adjustment_type"
                      name="edit_adjustment_type"
                      v-model="editForm.adjustment_type"
                      class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                      required
                    >
                      <option value="" disabled>Select an adjustment type</option>
                      <option value="fixed">Fixed Amount</option>
                      <option value="percentage">Percentage</option>
                    </select>
                  </div>

                  <div>
                    <label for="edit_price_adjustment" class="block text-sm font-medium text-gray-700">
                      Price Adjustment {{ editForm.adjustment_type === 'percentage' ? '(%)' : '(EUR)' }}
                    </label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">{{ editForm.adjustment_type === 'percentage' ? '%' : '€' }}</span>
                      </div>
                      <input
                        type="number"
                        name="edit_price_adjustment"
                        id="edit_price_adjustment"
                        v-model="editForm.price_adjustment"
                        class="p-2 focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                        :placeholder="editForm.adjustment_type === 'percentage' ? '10' : '10.00'"
                        :step="editForm.adjustment_type === 'percentage' ? '1' : '0.01'"
                        required
                      />
                    </div>
                  </div>

                  <div>
                    <label for="edit_description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea
                      id="edit_description"
                      name="edit_description"
                      rows="2"
                      v-model="editForm.description"
                      class="mt-1 p-2 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                    ></textarea>
                  </div>

                  <div class="flex items-center">
                    <input
                      id="edit_active"
                      name="edit_active"
                      type="checkbox"
                      v-model="editForm.active"
                      class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    />
                    <label for="edit_active" class="ml-2 block text-sm text-gray-900">
                      Active
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button 
              type="button" 
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
              @click="updatePriceRule"
              :disabled="editProcessing"
            >
              {{ editProcessing ? 'Saving...' : 'Save Changes' }}
            </button>
            <button 
              type="button" 
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              @click="showEditModal = false"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" style="transform: translateY(0)">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                  Delete Price Rule
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete this price rule? This action cannot be undone.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button 
              type="button" 
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
              @click="confirmDelete"
              :disabled="deleteProcessing"
            >
              {{ deleteProcessing ? 'Deleting...' : 'Delete' }}
            </button>
            <button 
              type="button" 
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              @click="showDeleteModal = false"
            >
              Cancel
            </button>
          </div>
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
const priceRules = ref([]);

const priceRuleForm = reactive({
  primary_option_id: '',
  dependent_option_id: '',
  price_adjustment: 0,
  adjustment_type: 'fixed',
  description: '',
  active: true
});

const editForm = reactive({
  id: null,
  primary_option_id: '',
  dependent_option_id: '',
  price_adjustment: 0,
  adjustment_type: 'fixed',
  description: '',
  active: true
});

const processing = ref(false);
const editProcessing = ref(false);
const deleteProcessing = ref(false);
const errors = reactive({});
const successMessage = ref('');
const showEditModal = ref(false);
const showDeleteModal = ref(false);
const priceRuleToDelete = ref(null);

onMounted(async () => {
  try {
    const productId = route.params.id;
    const [productResponse, priceRulesResponse] = await Promise.all([
      axios.get(`/admin/products/${productId}`),
      axios.get(`/admin/products/${productId}/price-rules`)
    ]);
    
    product.value = productResponse.data.product;
    priceRules.value = priceRulesResponse.data.priceRules;
  } catch (error) {
    console.error('Failed to fetch data:', error);
  }
});

async function submitPriceRule() {
  processing.value = true;
  successMessage.value = '';
  Object.keys(errors).forEach(key => delete errors[key]);

  try {
    const response = await axios.post(`/admin/products/${product.value.id}/price-rules`, priceRuleForm);
    processing.value = false;
    successMessage.value = 'Price rule added successfully.';
    
    // Add the new price rule to the list
    priceRules.value.push(response.data.priceRule);
    
    // Reset the form
    priceRuleForm.primary_option_id = '';
    priceRuleForm.dependent_option_id = '';
    priceRuleForm.price_adjustment = 0;
    priceRuleForm.adjustment_type = 'fixed';
    priceRuleForm.description = '';
    priceRuleForm.active = true;
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

function editPriceRule(priceRule) {
  editForm.id = priceRule.id;
  editForm.primary_option_id = priceRule.primary_option_id;
  editForm.dependent_option_id = priceRule.dependent_option_id;
  editForm.price_adjustment = priceRule.price_adjustment;
  editForm.adjustment_type = priceRule.adjustment_type;
  editForm.description = priceRule.description || '';
  editForm.active = priceRule.active;
  
  showEditModal.value = true;
}

async function updatePriceRule() {
  editProcessing.value = true;

  try {
    const response = await axios.put(`/admin/price-rules/${editForm.id}`, {
      primary_option_id: editForm.primary_option_id,
      dependent_option_id: editForm.dependent_option_id,
      price_adjustment: editForm.price_adjustment,
      adjustment_type: editForm.adjustment_type,
      description: editForm.description,
      active: editForm.active
    });
    
    // Update the price rule in the list
    const index = priceRules.value.findIndex(r => r.id === editForm.id);
    if (index !== -1) {
      priceRules.value[index] = response.data.priceRule;
    }
    
    showEditModal.value = false;
    successMessage.value = 'Price rule updated successfully.';
  } catch (error) {
    console.error('Failed to update price rule:', error);
    if (error.response && error.response.data && error.response.data.errors) {
      Object.entries(error.response.data.errors).forEach(([key, value]) => {
        errors[key] = Array.isArray(value) ? value[0] : value;
      });
    } else {
      errors.general = 'An error occurred. Please try again.';
    }
  } finally {
    editProcessing.value = false;
  }
}

function deletePriceRule(priceRule) {
  priceRuleToDelete.value = priceRule;
  showDeleteModal.value = true;
}

async function confirmDelete() {
  if (!priceRuleToDelete.value) return;
  
  deleteProcessing.value = true;
  
  try {
    await axios.delete(`/admin/price-rules/${priceRuleToDelete.value.id}`);
    
    // Remove the price rule from the list
    priceRules.value = priceRules.value.filter(r => r.id !== priceRuleToDelete.value.id);
    
    showDeleteModal.value = false;
    successMessage.value = 'Price rule deleted successfully.';
  } catch (error) {
    console.error('Failed to delete price rule:', error);
    errors.general = 'Failed to delete price rule. Please try again.';
  } finally {
    deleteProcessing.value = false;
  }
}

function getOptionName(optionId) {
  for (const part of product.value.parts || []) {
    for (const option of part.options || []) {
      if (option.id === optionId) {
        return option.name;
      }
    }
  }
  return 'Unknown Option';
}

function formatPriceAdjustment(adjustment, type) {
  if (type === 'percentage') {
    return `${adjustment > 0 ? '+' : ''}${adjustment}%`;
  } else {
    return `${adjustment > 0 ? '+' : ''}€${Math.abs(adjustment).toFixed(2)}`;
  }
}
</script>