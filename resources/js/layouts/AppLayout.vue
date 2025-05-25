<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="flex-shrink-0 flex items-center">
              <h1 class="text-xl font-bold text-gray-900">E-Commerce</h1>
            </div>
            <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
              <RouterLink 
                to="/" 
                class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                active-class="border-indigo-500 text-gray-900"
                inactive-class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700"
              >
                Products
              </RouterLink>
            </div>
          </div>
          <div class="flex items-center">
            <div class="hidden md:ml-4 md:flex-shrink-0 md:flex md:items-center">
              <button 
                v-if="cartItems.length > 0"
                class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
                <span class="sr-only">View cart</span>
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="absolute -mt-5 ml-4 px-2 py-1 text-xs font-bold rounded-full bg-indigo-500 text-white">{{ cartItems.length }}</span>
              </button>
            </div>
          </div>
          <div class="-mr-2 flex items-center sm:hidden">
            <!-- Mobile menu button -->
            <button 
              @click="mobileMenuOpen = !mobileMenuOpen" 
              class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
            >
              <span class="sr-only">Open main menu</span>
              <svg 
                :class="{'hidden': mobileMenuOpen, 'block': !mobileMenuOpen}" 
                class="h-6 w-6" 
                xmlns="http://www.w3.org/2000/svg" 
                fill="none" 
                viewBox="0 0 24 24" 
                stroke="currentColor" 
                aria-hidden="true"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
              <svg 
                :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen}" 
                class="h-6 w-6" 
                xmlns="http://www.w3.org/2000/svg" 
                fill="none" 
                viewBox="0 0 24 24" 
                stroke="currentColor" 
                aria-hidden="true"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile menu, show/hide based on menu state -->
      <div :class="{'block': mobileMenuOpen, 'hidden': !mobileMenuOpen}" class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
          <RouterLink 
            to="/" 
            class="bg-indigo-50 border-indigo-500 text-indigo-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
            active-class="bg-indigo-50 border-indigo-500 text-indigo-700"
            inactive-class="border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700"
          >
            Products
          </RouterLink>
        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <main>
      <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <router-view></router-view>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, provide } from 'vue';
import { useRouter, RouterLink } from 'vue-router';
import axios from 'axios';
import { v4 as uuidv4 } from 'uuid';

const router = useRouter();
const mobileMenuOpen = ref(false);
const cartItems = ref([]);
const cartId = ref('');
const isAddingToCart = ref(false);

// Generate or retrieve cart ID
function getCartId() {
  // Check if we already have a cart ID in localStorage
  const savedCartId = localStorage.getItem('cart_id');
  if (savedCartId) {
    cartId.value = savedCartId;
    return savedCartId;
  }

  // Generate a new cart ID
  const newCartId = uuidv4();
  cartId.value = newCartId;
  localStorage.setItem('cart_id', newCartId);
  return newCartId;
}

provide('cartItems', cartItems);
provide('cartId', cartId);

// Add to cart function
async function addToCart(product, selectedOptions) {
  if (isAddingToCart.value) return;

  try {
    isAddingToCart.value = true;

    // Get or generate cart ID
    const currentCartId = getCartId();

    // Prepare data for API
    const cartData = {
      cart_id: currentCartId,
      product_id: product.id,
      selected_options: selectedOptions,
      total_price: selectedOptions.totalPrice
    };

    // Send to backend
    const response = await axios.post('/cart', cartData);

    // Add to local cart items
    const cartItem = {
      id: Date.now(), // Unique ID for the cart item
      product,
      selectedOptions,
      timestamp: new Date()
    };

    cartItems.value.push(cartItem);

    // Save to localStorage
    localStorage.setItem('cart', JSON.stringify(cartItems.value));

    return response.data;
  } catch (error) {
    console.error('Failed to add item to cart:', error);
    throw error;
  } finally {
    isAddingToCart.value = false;
  }
}

// Provide addToCart function to child components
provide('addToCart', addToCart);

// Validate cart with backend and load from localStorage
async function validateCart() {
  const currentCartId = getCartId();

  try {
    // Call the API to validate the cart
    const response = await axios.get('/cart/validate', {
      params: { cart_id: currentCartId }
    });

    if (response.data.exists) {
      // Cart exists in the database, update local storage with the latest data
      const backendCartItems = response.data.cart_items;

      // Transform backend cart items to match the local format
      const transformedCartItems = backendCartItems.map(item => ({
        id: item.id,
        product: {
          id: item.product.id,
          name: item.product.name,
          image_path: item.product.image_path
        },
        selectedOptions: item.selected_options,
      }));

      // Update cart items
      cartItems.value = transformedCartItems;

      // Save updated cart to localStorage
      localStorage.setItem('cart', JSON.stringify(cartItems.value));
    } else {
      // Cart doesn't exist in the database, clear local storage
      cartItems.value = [];
      localStorage.removeItem('cart');
    }
  } catch (error) {
    console.error('Failed to validate cart with backend:', error);

    // If API call fails, still try to load from localStorage as fallback
    const savedCart = localStorage.getItem('cart');
    if (savedCart) {
      try {
        cartItems.value = JSON.parse(savedCart);
      } catch (e) {
        console.error('Failed to parse cart from localStorage:', e);
      }
    }
  }
}

// Load cart from localStorage on mount
onMounted(() => {
  // Get or generate cart ID
  getCartId();

  // Validate cart with backend
  validateCart();
});
</script>
