import '../css/app.css';
import { createApp } from 'vue';
import axios from 'axios';
import router from './appRouter';
import AppLayout from './layouts/AppLayout.vue';

// Configure axios
axios.defaults.baseURL = '/api';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Content-Type'] = 'application/json';
axios.defaults.headers.common['Accept'] = 'application/json';

// Add CSRF token to requests
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (csrfToken) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}

// Create Vue app
const app = createApp(AppLayout);

// Use Vue Router
app.use(router);

// Mount the app
app.mount('#app');

