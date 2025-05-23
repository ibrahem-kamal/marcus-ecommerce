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
          <ErrorDisplay :errors="errors" />
          <SuccessMessage :message="successMessage" />

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
    <Modal
      :show="showEditModal"
      title="Edit Price Rule"
      confirm-button-text="Save Changes"
      :processing="editProcessing"
      @confirm="updatePriceRule"
      @cancel="showEditModal = false"
    >
      <div class="space-y-4">
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

        <TextareaInput
          label="Description"
          id="edit_description"
          v-model="editForm.description"
          :rows="2"
        />

        <CheckboxInput
          label="Active"
          id="edit_active"
          v-model="editForm.active"
        />
      </div>
    </Modal>

    <!-- Delete Confirmation Modal -->
    <DeleteConfirmationModal
      :show="showDeleteModal"
      title="Delete Price Rule"
      message="Are you sure you want to delete this price rule? This action cannot be undone."
      :processing="deleteProcessing"
      @confirm="confirmDelete"
      @cancel="showDeleteModal = false"
    />
  </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue';
import { RouterLink, useRouter, useRoute } from 'vue-router';
import axios from 'axios';
import ErrorDisplay from '@/pages/Admin/components/ErrorDisplay.vue';
import SuccessMessage from '@/pages/Admin/components/SuccessMessage.vue';
import TextareaInput from '@/pages/Admin/components/TextareaInput.vue';
import CheckboxInput from '@/pages/Admin/components/CheckboxInput.vue';
import Modal from '@/pages/Admin/components/Modal.vue';
import DeleteConfirmationModal from '@/pages/Admin/components/DeleteConfirmationModal.vue';

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
