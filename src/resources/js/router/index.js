import { createRouter, createWebHistory } from 'vue-router';
import LoginPage from '../views/LoginPage';
import MainPage from '../views/MainPage';
import WordCheck from '../views/Word/WordCheck';
import WordCreate from '../views/Word/WordCreate';
import WordList from '../views/Word/WordList';
import ItemCreate from '../views/Item/ItemCreate';
import ItemList from '../views/Item/ItemList';
import PasswordReset from '../views/PasswordReset';

const routes = [
  {
    path: '/',
    component: MainPage,
  },
  {
    path: '/word_check',
    component: WordCheck,
  },
  {
    path: '/word_create',
    component: WordCreate,
  },
  {
    path: '/word_list',
    component: WordList,
  },
  {
    path: '/item_create',
    component: ItemCreate,
  },
  {
    path: '/item_list',
    component: ItemList,
  },
  {
    path: '/login',
    component: LoginPage,
  },
  {
    path: '/password-reset',
    component: PasswordReset,
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
