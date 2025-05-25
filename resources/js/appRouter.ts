import { createRouter, createWebHistory } from 'vue-router';
import axios from 'axios';

const routes = [
  {
    path: '/',
    name: 'products.index',
    component: () => import('./pages/Products/Index.vue')
  },
  {
    path: '/products/:id',
    name: 'products.show',
    component: () => import('./pages/Products/Show.vue'),
    props: true
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});


export default router;
