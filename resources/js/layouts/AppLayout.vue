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

const router = useRouter();
const mobileMenuOpen = ref(false);
const cartItems = ref([]);

provide('cartItems', cartItems);

// Add to cart function
function addToCart(product, selectedOptions) {
  const cartItem = {
    id: Date.now(), // Unique ID for the cart item
    product,
    selectedOptions,
    timestamp: new Date()
  };

  cartItems.value.push(cartItem);

  // Could save to localStorage here if needed
  localStorage.setItem('cart', JSON.stringify(cartItems.value));
}

// Provide addToCart function to child components
provide('addToCart', addToCart);

// Load cart from localStorage on mount
onMounted(() => {
  const savedCart = localStorage.getItem('cart');
  if (savedCart) {
    try {
      cartItems.value = JSON.parse(savedCart);
    } catch (e) {
      console.error('Failed to parse cart from localStorage:', e);
    }
  }
});
</script>
