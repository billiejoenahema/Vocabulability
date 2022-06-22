import { createRouter, createWebHistory } from 'vue-router';
import MainPage from '../pages/MainPage';
import WordCreate from '../pages/WordCreate';

const routes = [
  {
    path: '/',
    component: MainPage,
  },
  {
    path: '/create',
    component: WordCreate,
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
