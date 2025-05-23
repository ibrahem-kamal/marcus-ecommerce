<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="flex-shrink-0 flex items-center">
              <h1 class="text-xl font-bold text-blue-600">Admin Panel</h1>
            </div>
            <div v-if="isAuthenticated" class="hidden sm:ml-6 sm:flex sm:space-x-8">
              <RouterLink
                to="/admin/dashboard"
                :class="[
                  $route.path === '/admin/dashboard'
                    ? 'border-blue-500 text-gray-900'
                    : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700',
                  'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium'
                ]"
              >
                Dashboard
              </RouterLink>
            </div>
          </div>
          <div class="hidden sm:ml-6 sm:flex sm:items-center">
            <div class="ml-3 relative">
              <div v-if="isAuthenticated" class="flex items-center">
                <span class="text-sm text-gray-500 mr-2">{{ admin.name }}</span>
                <form @submit.prevent="logout">
                  <button
                    type="submit"
                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  >
                    Logout
                  </button>
                </form>
              </div>
              <div v-else>
                <RouterLink
                  to="/admin/login"
                  class="inline-flex items-center px-3 py-1.5 border border-blue-300 text-sm font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  Login
                </RouterLink>
              </div>
            </div>
          </div>
          <div class="-mr-2 flex items-center sm:hidden">
            <!-- Mobile menu button -->
            <button
              @click="mobileMenuOpen = !mobileMenuOpen"
              class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
            >
              <span class="sr-only">Open main menu</span>
              <svg
                class="h-6 w-6"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                aria-hidden="true"
              >
                <path
                  v-if="mobileMenuOpen"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M6 18L18 6M6 6l12 12"
                />
                <path
                  v-else
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M4 6h16M4 12h16M4 18h16"
                />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile menu -->
      <div v-if="mobileMenuOpen" class="sm:hidden">
        <div v-if="isAuthenticated" class="pt-2 pb-3 space-y-1">
          <RouterLink
            to="/admin/dashboard"
            :class="[
              $route.path === '/admin/dashboard'
                ? 'bg-blue-50 border-blue-500 text-blue-700'
                : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800',
              'block pl-3 pr-4 py-2 border-l-4 text-base font-medium'
            ]"
          >
            Dashboard
          </RouterLink>
          <RouterLink
            to="/admin/products"
            :class="[
              $route.path.startsWith('/admin/products')
                ? 'bg-blue-50 border-blue-500 text-blue-700'
                : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800',
              'block pl-3 pr-4 py-2 border-l-4 text-base font-medium'
            ]"
          >
            Products
          </RouterLink>
        </div>
        <div v-else class="pt-2 pb-3 space-y-1">
          <RouterLink
            to="/admin/login"
            :class="[
              'bg-blue-50 border-blue-500 text-blue-700',
              'block pl-3 pr-4 py-2 border-l-4 text-base font-medium'
            ]"
          >
            Login
          </RouterLink>
        </div>
        <div v-if="isAuthenticated" class="pt-4 pb-3 border-t border-gray-200">
          <div class="flex items-center px-4">
            <div class="ml-3">
              <div class="text-base font-medium text-gray-800">{{ admin.name }}</div>
              <div class="text-sm font-medium text-gray-500">{{ admin.email }}</div>
            </div>
          </div>
          <div class="mt-3 space-y-1">
            <form @submit.prevent="logout" class="block">
              <button
                type="submit"
                class="block w-full text-left px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100"
              >
                Logout
              </button>
            </form>
          </div>
        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <main>
      <div v-if="!isAuthenticated && $route.path !== '/admin/login'" class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md mx-auto text-center">
          <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Admin Access Required</h2>
          <p class="text-lg text-gray-600 mb-6">Please log in to access the admin panel</p>
          <RouterLink
            to="/admin/login"
            class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            Go to Login
          </RouterLink>
        </div>
      </div>
      <router-view v-else></router-view>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter, RouterLink } from 'vue-router';
import axios from 'axios';

const router = useRouter();
const mobileMenuOpen = ref(false);
const admin = ref({ name: '', email: '' });

// Check if user is authenticated
const isAuthenticated = computed(() => {
  return !!localStorage.getItem('admin_token');
});

onMounted(async () => {
  if (isAuthenticated.value) {
    try {
      const response = await axios.get('/admin/user');
      admin.value = response.data;
    } catch (error) {
      console.error('Failed to fetch admin user:', error);
      // If token is invalid, remove it and redirect to login
      if (error.response && error.response.status === 401) {
        localStorage.removeItem('admin_token');
        router.push('/admin/login');
      }
    }
  }
});

async function logout() {
  try {
    await axios.post('/admin/logout');
    localStorage.removeItem('admin_token');
    router.push('/admin/login');
  } catch (error) {
    console.error('Failed to logout:', error);
  }
}
</script>
