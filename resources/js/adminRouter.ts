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
  {
    path: '/admin/products',
    name: 'admin.products.index',
    component: () => import('./pages/Admin/Products/Index.vue'),
    meta: { requiresAdminAuth: true, layout: 'admin' }
  },
  {
    path: '/admin/products/create',
    name: 'admin.products.create',
    component: () => import('./pages/Admin/Products/Create.vue'),
    meta: { requiresAdminAuth: true, layout: 'admin' }
  },
  {
    path: '/admin/products/:id/edit',
    name: 'admin.products.edit',
    component: () => import('./pages/Admin/Products/Edit.vue'),
    props: true,
    meta: { requiresAdminAuth: true, layout: 'admin' }
  },
  {
    path: '/admin/products/:id/parts/create',
    name: 'admin.parts.create',
    component: () => import('./pages/Admin/Products/CreatePart.vue'),
    props: true,
    meta: { requiresAdminAuth: true, layout: 'admin' }
  },
  {
    path: '/admin/parts/:id/options/create',
    name: 'admin.options.create',
    component: () => import('./pages/Admin/Products/CreateOption.vue'),
    props: true,
    meta: { requiresAdminAuth: true, layout: 'admin' }
  },
  {
    path: '/admin/parts/:id/edit',
    name: 'admin.parts.edit',
    component: () => import('./pages/Admin/Products/EditPart.vue'),
    props: true,
    meta: { requiresAdminAuth: true, layout: 'admin' }
  },
  {
    path: '/admin/options/:id/edit',
    name: 'admin.options.edit',
    component: () => import('./pages/Admin/Products/EditOption.vue'),
    props: true,
    meta: { requiresAdminAuth: true, layout: 'admin' }
  },
  {
    path: '/admin/products/:id/combinations',
    name: 'admin.products.combinations',
    component: () => import('./pages/Admin/Products/OptionCombinations.vue'),
    props: true,
    meta: { requiresAdminAuth: true, layout: 'admin' }
  },
  {
    path: '/admin/products/:id/price-rules',
    name: 'admin.products.price-rules',
    component: () => import('./pages/Admin/Products/PriceRules.vue'),
    props: true,
    meta: { requiresAdminAuth: true, layout: 'admin' }
  }
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