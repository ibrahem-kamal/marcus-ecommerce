import { createRouter, createWebHistory } from 'vue-router';
import axios from 'axios';

const routes = [
  // Admin Routes
  {
    path: '/admin/login',
    name: 'admin.login',
    component: () => import('./pages/Admin/Login.vue'),
    meta: { layout: 'admin' }
  },
  {
    path: '/admin/dashboard',
    name: 'admin.dashboard',
    component: () => import('./pages/Admin/Dashboard.vue'),
    meta: { requiresAdminAuth: true, layout: 'admin' }
  },
 
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Navigation guards
router.beforeEach((to, from, next) => {
  // Check if the route requires admin authentication
  if (to.matched.some(record => record.meta.requiresAdminAuth)) {
    // Check if the admin is authenticated
    const token = localStorage.getItem('admin_token');

    if (!token) {
      // Redirect to admin login page
      next({ path: '/admin/login' });
    } else {
      // Verify that it's an admin token
      axios.get('/admin/user')
        .then(() => {
          next();
        })
        .catch(() => {
          localStorage.removeItem('admin_token');
          next({ path: '/admin/login' });
        });
    }
  } else {
    next();
  }
});

export default router;