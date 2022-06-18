import { createRouter, createWebHistory } from 'vue-router';
import MainPage from '../pages/MainPage';

const routes = [
  {
    path: '/',
    component: 'MainPage',
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes: routes,
  scrollBehavior() {
    return { x: 0, y: 0 };
  },
});

export default router;
