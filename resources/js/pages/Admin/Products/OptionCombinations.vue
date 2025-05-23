<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-semibold text-gray-900">Option Combinations for {{ product.name }}</h1>
        <RouterLink
          :to="`/admin/products/${product.id}/edit`"
          class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        >
          Back to Product
        </RouterLink>
      </div>
      
      <p class="mt-2 text-sm text-gray-500">
        Option combinations allow you to define rules for how different options work together.
      </p>

      <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-6 py-5 border-b border-gray-200">
          <h3 class="text-lg font-medium text-gray-900">Add New Combination</h3>
        </div>
        <form @submit.prevent="submitCombination" class="p-6 space-y-6">
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
              <label for="if_option_id" class="block text-sm font-medium text-gray-700">If Option</label>
              <select
                id="if_option_id"
                name="if_option_id"
                v-model="combinationForm.if_option_id"
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
              <label for="rule_type" class="block text-sm font-medium text-gray-700">Rule Type</label>
              <select
                id="rule_type"
                name="rule_type"
                v-model="combinationForm.rule_type"
                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                required
              >
                <option value="" disabled>Select a rule type</option>
                <option value="required">Required</option>
                <option value="prohibited">Prohibited</option>
              </select>
            </div>

            <div class="sm:col-span-3">
              <label for="then_part_id" class="block text-sm font-medium text-gray-700">Then Part</label>
              <select
                id="then_part_id"
                name="then_part_id"
                v-model="combinationForm.then_part_id"
                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                required
                @change="loadPartOptions"
              >
                <option value="" disabled>Select a part</option>
                <option v-for="part in product.parts" :key="part.id" :value="part.id">
                  {{ part.name }}
                </option>
              </select>
            </div>

            <div class="sm:col-span-3">
              <label for="then_option_id" class="block text-sm font-medium text-gray-700">Then Option</label>
              <select
                id="then_option_id"
                name="then_option_id"
                v-model="combinationForm.then_option_id"
                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                required
                :disabled="!combinationForm.then_part_id"
              >
                <option value="" disabled>Select an option</option>
                <option v-for="option in thenPartOptions" :key="option.id" :value="option.id">
                  {{ option.name }}
                </option>
              </select>
            </div>

            <div class="sm:col-span-6">
              <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
              <textarea
                id="description"
                name="description"
                rows="2"
                v-model="combinationForm.description"
                class="mt-1 p-2 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
              ></textarea>
            </div>

            <div class="sm:col-span-6">
              <div class="flex items-center">
                <input
                  id="active"
                  name="active"
                  type="checkbox"
                  v-model="combinationForm.active"
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
              {{ processing ? 'Saving...' : 'Add Combination' }}
            </button>
          </div>
        </form>
      </div>

      <!-- Existing Combinations -->
      <div class="mt-8">
        <h2 class="text-xl font-semibold text-gray-900">Existing Combinations</h2>
        
        <div class="mt-4 bg-white shadow overflow-hidden sm:rounded-lg">
          <ul role="list" class="divide-y divide-gray-200">
            <li v-if="combinations.length === 0" class="px-6 py-4 text-center text-gray-500">
              No combinations found. Add your first combination above.
            </li>
            <li v-for="combination in combinations" :key="combination.id" class="px-6 py-4">
              <div class="flex items-center justify-between">
                <div>
                  <div class="flex items-center">
                    <span class="font-medium text-gray-900">{{ getOptionName(combination.if_option_id) }}</span>
                    <span class="mx-2 text-gray-500">{{ getRuleTypeText(combination.rule_type) }}</span>
                    <span class="font-medium text-gray-900">{{ getOptionName(combination.then_option_id) }}</span>
                    <span class="ml-2 text-gray-500">({{ getPartName(combination.then_part_id) }})</span>
                  </div>
                  <p class="mt-1 text-sm text-gray-500">{{ combination.description || 'No description' }}</p>
                  <div class="mt-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                      :class="combination.active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                      {{ combination.active ? 'Active' : 'Inactive' }}
                    </span>
                  </div>
                </div>
                <div class="flex space-x-2">
                  <button
                    @click="editCombination(combination)"
                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteCombination(combination)"
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

    <!-- Edit Combination Modal -->
    <div v-if="showEditModal" class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" style="transform: translateY(0)">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                  Edit Combination
                </h3>
                <div class="mt-4 space-y-4">
                  <div>
                    <label for="edit_if_option_id" class="block text-sm font-medium text-gray-700">If Option</label>
                    <select
                      id="edit_if_option_id"
                      name="edit_if_option_id"
                      v-model="editForm.if_option_id"
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
                    <label for="edit_rule_type" class="block text-sm font-medium text-gray-700">Rule Type</label>
                    <select
                      id="edit_rule_type"
                      name="edit_rule_type"
                      v-model="editForm.rule_type"
                      class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                      required
                    >
                      <option value="" disabled>Select a rule type</option>
                      <option value="required">Required</option>
                      <option value="prohibited">Prohibited</option>
                    </select>
                  </div>

                  <div>
                    <label for="edit_then_part_id" class="block text-sm font-medium text-gray-700">Then Part</label>
                    <select
                      id="edit_then_part_id"
                      name="edit_then_part_id"
                      v-model="editForm.then_part_id"
                      class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                      required
                      @change="loadEditPartOptions"
                    >
                      <option value="" disabled>Select a part</option>
                      <option v-for="part in product.parts" :key="part.id" :value="part.id">
                        {{ part.name }}
                      </option>
                    </select>
                  </div>

                  <div>
                    <label for="edit_then_option_id" class="block text-sm font-medium text-gray-700">Then Option</label>
                    <select
                      id="edit_then_option_id"
                      name="edit_then_option_id"
                      v-model="editForm.then_option_id"
                      class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                      required
                      :disabled="!editForm.then_part_id"
                    >
                      <option value="" disabled>Select an option</option>
                      <option v-for="option in editThenPartOptions" :key="option.id" :value="option.id">
                        {{ option.name }}
                      </option>
                    </select>
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
              @click="updateCombination"
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
                  Delete Combination
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete this combination? This action cannot be undone.
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
import { reactive, ref, onMounted, computed } from 'vue';
import { RouterLink, useRouter, useRoute } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const route = useRoute();
const product = ref({});
const combinations = ref([]);
const thenPartOptions = ref([]);
const editThenPartOptions = ref([]);

const combinationForm = reactive({
  if_option_id: '',
  rule_type: '',
  then_part_id: '',
  then_option_id: '',
  description: '',
  active: true
});

const editForm = reactive({
  id: null,
  if_option_id: '',
  rule_type: '',
  then_part_id: '',
  then_option_id: '',
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
const combinationToDelete = ref(null);

onMounted(async () => {
  try {
    const productId = route.params.id;
    const [productResponse, combinationsResponse] = await Promise.all([
      axios.get(`/admin/products/${productId}`),
      axios.get(`/admin/products/${productId}/combinations`)
    ]);
    
    product.value = productResponse.data.product;
    combinations.value = combinationsResponse.data.combinations;
  } catch (error) {
    console.error('Failed to fetch data:', error);
  }
});

async function loadPartOptions() {
  if (!combinationForm.then_part_id) return;
  
  try {
    const response = await axios.get(`/admin/parts/${combinationForm.then_part_id}`);
    thenPartOptions.value = response.data.part.options;
  } catch (error) {
    console.error('Failed to fetch part options:', error);
  }
}

async function loadEditPartOptions() {
  if (!editForm.then_part_id) return;
  
  try {
    const response = await axios.get(`/admin/parts/${editForm.then_part_id}`);
    editThenPartOptions.value = response.data.part.options;
  } catch (error) {
    console.error('Failed to fetch part options:', error);
  }
}

async function submitCombination() {
  processing.value = true;
  successMessage.value = '';
  Object.keys(errors).forEach(key => delete errors[key]);

  try {
    const response = await axios.post(`/admin/products/${product.value.id}/combinations`, combinationForm);
    processing.value = false;
    successMessage.value = 'Combination added successfully.';
    
    // Add the new combination to the list
    combinations.value.push(response.data.combination);
    
    // Reset the form
    combinationForm.if_option_id = '';
    combinationForm.rule_type = '';
    combinationForm.then_part_id = '';
    combinationForm.then_option_id = '';
    combinationForm.description = '';
    combinationForm.active = true;
    thenPartOptions.value = [];
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

function editCombination(combination) {
  editForm.id = combination.id;
  editForm.if_option_id = combination.if_option_id;
  editForm.rule_type = combination.rule_type;
  editForm.then_part_id = combination.then_part_id;
  editForm.then_option_id = combination.then_option_id;
  editForm.description = combination.description || '';
  editForm.active = combination.active;
  
  loadEditPartOptions();
  showEditModal.value = true;
}

async function updateCombination() {
  editProcessing.value = true;

  try {
    const response = await axios.put(`/admin/combinations/${editForm.id}`, {
      if_option_id: editForm.if_option_id,
      rule_type: editForm.rule_type,
      then_part_id: editForm.then_part_id,
      then_option_id: editForm.then_option_id,
      description: editForm.description,
      active: editForm.active
    });
    
    // Update the combination in the list
    const index = combinations.value.findIndex(c => c.id === editForm.id);
    if (index !== -1) {
      combinations.value[index] = response.data.combination;
    }
    
    showEditModal.value = false;
    successMessage.value = 'Combination updated successfully.';
  } catch (error) {
    console.error('Failed to update combination:', error);
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

function deleteCombination(combination) {
  combinationToDelete.value = combination;
  showDeleteModal.value = true;
}

async function confirmDelete() {
  if (!combinationToDelete.value) return;
  
  deleteProcessing.value = true;
  
  try {
    await axios.delete(`/admin/combinations/${combinationToDelete.value.id}`);
    
    // Remove the combination from the list
    combinations.value = combinations.value.filter(c => c.id !== combinationToDelete.value.id);
    
    showDeleteModal.value = false;
    successMessage.value = 'Combination deleted successfully.';
  } catch (error) {
    console.error('Failed to delete combination:', error);
    errors.general = 'Failed to delete combination. Please try again.';
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

function getPartName(partId) {
  const part = (product.value.parts || []).find(p => p.id === partId);
  return part ? part.name : 'Unknown Part';
}

function getRuleTypeText(ruleType) {
  switch (ruleType) {
    case 'required': return 'required';
    case 'prohibited': return 'prohibited';
    default: return ruleType;
  }
}
</script>