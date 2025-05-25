<template>
  <div>
    <div v-if="loading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-indigo-500"></div>
      <p class="mt-2 text-sm text-gray-500">Loading product details...</p>
    </div>

    <div v-else-if="error" class="bg-red-50 p-4 rounded-md">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-medium text-red-800">Failed to load product</h3>
          <div class="mt-2 text-sm text-red-700">
            <p>{{ error }}</p>
          </div>
        </div>
      </div>
    </div>

    <div v-else>
      <!-- Product Header -->
      <div class="pb-5 border-b border-gray-200">
        <div class="sm:flex sm:items-center sm:justify-between">
          <h3 class="text-lg leading-6 font-medium text-gray-900">{{ product.name }}</h3>
          <div class="mt-3 sm:mt-0 sm:ml-4">
            <router-link to="/products" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
              Back to Products
            </router-link>
          </div>
        </div>
        <p class="mt-2 max-w-4xl text-sm text-gray-500">{{ product.description }}</p>
      </div>

      <!-- Product Configuration -->
      <div class="mt-6">
        <h4 class="text-base font-medium text-gray-900">Configure Your Product</h4>

        <div class="mt-4 space-y-8">
          <!-- Parts and Options -->
          <div v-for="part in product.parts" :key="part.id" class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6 bg-gray-50">
              <h5 class="text-sm font-medium text-gray-900">{{ part.name }}</h5>
              <p v-if="part.description" class="mt-1 text-xs text-gray-500">{{ part.description }}</p>
              <div v-if="part.required" class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                Required
              </div>
            </div>

            <div class="border-t border-gray-200">
              <div class="px-4 py-5 sm:p-6">
                <div v-if="part.options.length === 0" class="text-sm text-gray-500">
                  No options available for this part.
                </div>

                <div v-else class="space-y-4">
                  <div v-for="option in part.options" :key="option.id" class="relative flex items-start">
                    <div class="flex items-center h-5">
                      <input 
                        :id="`option-${option.id}`" 
                        :name="`part-${part.id}`" 
                        type="radio" 
                        :value="option.id" 
                        v-model="selectedOptions[part.id]"
                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300"
                        :disabled="!isOptionAvailable(part.id, option.id) || 
                                  (autoSelectedOptions[part.id] && selectedOptions[part.id] !== option.id) ||
                                  (hasRequiredOption(part.id) && selectedOptions[part.id] !== option.id)"
                      >
                    </div>
                    <div class="ml-3 flex justify-between w-full">
                      <label :for="`option-${option.id}`" class="text-sm font-medium text-gray-700" :class="{ 'text-gray-400': !isOptionAvailable(part.id, option.id) }">
                        {{ option.name }}
                        <span v-if="option.description" class="block text-xs text-gray-500">{{ option.description }}</span>

                        <!-- Display auto-selected indicator -->
                        <span 
                          v-if="autoSelectedOptions[part.id] && selectedOptions[part.id] === option.id" 
                          class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 ml-2"
                        >
                          Auto-selected
                        </span>

                        <!-- Display requirement reason if available -->
                        <span 
                          v-if="getOptionRequirementReason(part.id, option.id)" 
                          class="block text-xs mt-1"
                          :class="{
                            'text-green-600': getOptionRequirementReason(part.id, option.id).type === 'required',
                            'text-red-600': getOptionRequirementReason(part.id, option.id).type === 'prohibited'
                          }"
                        >
                          <template v-if="getOptionRequirementReason(part.id, option.id).type === 'required'">
                            Required: 
                          </template>
                          <template v-else>
                            Not available: 
                          </template>
                          {{ getOptionRequirementReason(part.id, option.id).description || 
                             `This option is ${getOptionRequirementReason(part.id, option.id).type === 'required' ? 'required' : 'not available'} when ${getOptionRequirementReason(part.id, option.id).ifOptionName} is selected.` }}
                        </span>
                      </label>
                      <span class="text-sm font-medium text-gray-900" :class="{ 'text-gray-400': !isOptionAvailable(part.id, option.id) }">
                        {{ formatPrice(option.base_price) }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Price Summary -->
          <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6 bg-gray-50">
              <h5 class="text-sm font-medium text-gray-900">Price Summary</h5>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
              <dl class="space-y-2">
                <div v-for="(optionId, partId) in selectedOptions" :key="partId" class="flex justify-between text-sm">
                  <dt class="text-gray-500">{{ getPartName(partId) }} - {{ getOptionName(optionId) }}</dt>
                  <dd class="font-medium text-gray-900">{{ formatPrice(getOptionPrice(optionId)) }}</dd>
                </div>

                <div v-if="priceAdjustments.length > 0" class="pt-2 border-t border-gray-200">
                  <h6 class="text-xs font-medium text-gray-700 mb-2">Price Adjustments</h6>
                  <div v-for="(adjustment, index) in priceAdjustments" :key="index" class="flex justify-between text-sm">
                    <dt class="text-gray-500">{{ adjustment.description }}</dt>
                    <dd class="font-medium text-gray-900">{{ formatPrice(adjustment.amount) }}</dd>
                  </div>
                </div>

                <div class="pt-2 border-t border-gray-200 flex justify-between text-sm font-medium">
                  <dt class="text-gray-900">Total Price</dt>
                  <dd class="text-indigo-600">{{ formatPrice(totalPrice) }}</dd>
                </div>
              </dl>
            </div>
          </div>

          <!-- Add to Cart -->
          <div class="flex justify-end">
            <button 
              @click="addToCartHandler" 
              :disabled="!isConfigurationValid" 
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Add to Cart
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, inject, watch, nextTick } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

const props = defineProps({
  id: {
    type: [String, Number],
    required: true
  }
});

const router = useRouter();
const addToCart = inject('addToCart');

const product = ref(null);
const loading = ref(true);
const error = ref(null);
const selectedOptions = ref({});
const priceAdjustments = ref([]);
const autoSelectedOptions = ref({});

// Fetch product details
onMounted(async () => {
  try {
    const response = await axios.get(`/products/${props.id}`);
    product.value = response.data.product;
  } catch (err) {
    console.error('Failed to fetch product:', err);
    error.value = err.response?.data?.message || 'An error occurred while fetching the product.';
  } finally {
    loading.value = false;
  }
});

// Check if an option is available based on option combinations
function isOptionAvailable(partId, optionId) {
  if (!product.value) return false;

  // If no options are selected yet, all options are available
  if (Object.keys(selectedOptions.value).length === 0) return true;

  // Check if there are any option combinations that affect this option
  const affectingCombinations = ( product.value.option_combinations || []).filter(combo => 
    combo.then_part_id === partId && combo.then_option_id === optionId
  );

  if (affectingCombinations.length === 0) return true;

  // Check if any of the affecting combinations match the current selection
  for (const combo of affectingCombinations) {
    const ifOptionId = combo.if_option_id;
    const ifPartId = getPartIdForOption(ifOptionId);

    if (ifPartId && selectedOptions.value[ifPartId] === ifOptionId) {
      // If rule_type is 'required', the option should be available
      // If rule_type is 'prohibited', the option should not be available
      return combo.rule_type === 'required';
    }
  }

  // If no matching combinations were found, the option is available
  return true;
}

// Get the part ID for a given option ID
function getPartIdForOption(optionId) {
  if (!product.value) return null;

  for (const part of product.value.parts) {
    for (const option of part.options) {
      if (option.id === optionId) {
        return part.id;
      }
    }
  }

  return null;
}

// Get part name by ID
function getPartName(partId) {
  if (!product.value) return '';

  const part = product.value.parts.find(p => p.id === parseInt(partId));
  return part ? part.name : '';
}

// Get option name by ID
function getOptionName(optionId) {
  if (!product.value) return '';

  for (const part of product.value.parts) {
    const option = part.options.find(o => o.id === parseInt(optionId));
    if (option) return option.name;
  }

  return '';
}

// Get option price by ID
function getOptionPrice(optionId) {
  if (!product.value) return 0;

  for (const part of product.value.parts) {
    const option = part.options.find(o => o.id === parseInt(optionId));
    if (option) return parseFloat(option.base_price);
  }

  return 0;
}

// Get the reason why an option is required or prohibited
function getOptionRequirementReason(partId, optionId) {
  if (!product.value) return null;

  // If no options are selected yet, there's no reason
  if (Object.keys(selectedOptions.value).length === 0) return null;

  // Find option combinations that affect this option
  const affectingCombinations = ( product.value.option_combinations || []).filter(combo => 
    combo.then_part_id === partId && combo.then_option_id === optionId
  );

  if (affectingCombinations.length === 0) return null;

  // Check if any of the affecting combinations match the current selection
  for (const combo of affectingCombinations) {
    const ifOptionId = combo.if_option_id;
    const ifPartId = getPartIdForOption(ifOptionId);

    if (ifPartId && selectedOptions.value[ifPartId] === ifOptionId) {
      return {
        type: combo.rule_type,
        description: combo.description,
        ifOptionName: getOptionName(ifOptionId)
      };
    }
  }

  return null;
}

// Find options that should be automatically selected based on current selections
function findRequiredOptions() {
  if (!product.value) return {};

  const requiredOptions = {};
  const combinations = product.value.option_combinations || [];

  // Check all combinations with rule_type 'required'
  const requiredCombinations = combinations.filter(combo => combo.rule_type === 'required');

  for (const combo of requiredCombinations) {
    const ifOptionId = combo.if_option_id;
    const ifPartId = getPartIdForOption(ifOptionId);
    const thenPartId = combo.then_part_id;
    const thenOptionId = combo.then_option_id;

    // If the 'if' option is selected, then the 'then' option should be automatically selected
    if (ifPartId && selectedOptions.value[ifPartId] === ifOptionId) {
      requiredOptions[thenPartId] = thenOptionId;
    }
  }

  return requiredOptions;
}

// Check if a part has a required option based on current selections
function hasRequiredOption(partId) {
  if (!product.value) return false;

  const combinations = product.value.optionCombinations || product.value.option_combinations || [];

  // Check all combinations with rule_type 'required'
  const requiredCombinations = combinations.filter(combo => combo.rule_type === 'required');

  for (const combo of requiredCombinations) {
    const ifOptionId = combo.if_option_id;
    const ifPartId = getPartIdForOption(ifOptionId);
    const thenPartId = combo.then_part_id;

    // If the 'if' option is selected and this affects the given part
    if (ifPartId && selectedOptions.value[ifPartId] === ifOptionId && thenPartId === partId) {
      return true;
    }
  }

  return false;
}

// Calculate price adjustments based on selected options and price rules
const calculatePriceAdjustments = computed(() => {
  if (!product.value) return [];

  const adjustments = [];
  // Apply price rules
  for (const rule of product.value.price_rules) {
    const primaryOptionId = rule.primary_option_id;
    const dependentOptionId = rule.dependent_option_id;

    const primaryPartId = getPartIdForOption(primaryOptionId);
    const dependentPartId = getPartIdForOption(dependentOptionId);

    // Check if both options are selected
    if (
      primaryPartId && 
      dependentPartId && 
      selectedOptions.value[primaryPartId] === primaryOptionId && 
      selectedOptions.value[dependentPartId] === dependentOptionId
    ) {
      const amount = parseFloat(rule.price_adjustment);
      const description = rule.description || `Price adjustment for ${getOptionName(primaryOptionId)} with ${getOptionName(dependentOptionId)}`;

      adjustments.push({
        description,
        amount
      });
    }
  }

  return adjustments;
});

// Update price adjustments when calculated
priceAdjustments.value = calculatePriceAdjustments.value;

// Function to scroll to an element by ID
function scrollToElement(elementId) {
  nextTick(() => {
    const element = document.getElementById(elementId);
    if (element) {
      element.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  });
}

// Watch for changes in selected options to recalculate price adjustments and auto-select required options
watch(selectedOptions, () => {
  // Recalculate price adjustments
  priceAdjustments.value = calculatePriceAdjustments.value;

  // Find options that should be automatically selected
  const requiredOptions = findRequiredOptions();

  // Update autoSelectedOptions ref
  autoSelectedOptions.value = {};

  // Apply required options
  for (const partId in requiredOptions) {
    const optionId = requiredOptions[partId];

    // Only auto-select if the option is different from the current selection
    if (selectedOptions.value[partId] !== optionId) {
      // Update the selection
      selectedOptions.value[partId] = optionId;

      // Mark this option as auto-selected
      autoSelectedOptions.value[partId] = true;

      // Scroll to the auto-selected option
      scrollToElement(`option-${optionId}`);
    }
  }
}, { deep: true });

// Calculate total price
const totalPrice = computed(() => {
  let total = 0;

  // Add base prices of selected options
  for (const partId in selectedOptions.value) {
    const optionId = selectedOptions.value[partId];
    total += getOptionPrice(optionId);
  }

  // Add price adjustments
  for (const adjustment of priceAdjustments.value) {
    total += adjustment.amount;
  }

  return total;
});

// Check if configuration is valid (all required parts have options selected)
const isConfigurationValid = computed(() => {
  if (!product.value) return false;

  for (const part of product.value.parts) {
    if (part.required && !selectedOptions.value[part.id]) {
      return false;
    }
  }

  return true;
});

// Format price as currency
function formatPrice(price) {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(price);
}

// Add to cart handler
async function addToCartHandler() {
  if (!isConfigurationValid.value) return;

  const selectedOptionsData = {};

  // Prepare selected options data with part and option details
  for (const partId in selectedOptions.value) {
    const optionId = selectedOptions.value[partId];

    selectedOptionsData[partId] = {
      partId: parseInt(partId),
      partName: getPartName(partId),
      optionId: parseInt(optionId),
      optionName: getOptionName(optionId),
      price: getOptionPrice(optionId)
    };
  }

  try {
    // Add product to cart
    await addToCart(
      {
        id: product.value.id,
        name: product.value.name,
        image_path: product.value.image_path
      }, 
      {
        options: selectedOptionsData,
        price_adjustments: priceAdjustments.value,
        totalPrice: totalPrice.value
      }
    );

    // Show confirmation and redirect to products page
    alert('Product added to cart!');
    router.push('/');
  } catch (error) {
    console.error('Failed to add product to cart:', error);
    alert('Failed to add product to cart. Please try again.');
  }
}
</script>
