import { createRouter, createWebHistory } from 'vue-router';
import MainPage from '../views/MainPage';
import WordCreate from '../views/WordCreate';
import WordList from '../views/WordList';

const routes = [
  {
    path: '/',
    component: MainPage,
  },
  {
    path: '/create',
    component: WordCreate,
  },
  {
    path: '/list',
    component: WordList,
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
