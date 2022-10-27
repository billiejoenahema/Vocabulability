import { createRouter, createWebHistory } from 'vue-router';
import LoginPage from '../views/LoginPage';
import MainPage from '../views/MainPage';
import ResetPassword from '../views/ResetPassword';
import WordCheck from '../views/Word/WordCheck';
import WordCreate from '../views/Word/WordCreate';
import WordList from '../views/Word/WordList';
import ItemCreate from '../views/Item/ItemCreate';
import ItemList from '../views/Item/ItemList';
import { store } from '../store/index';

const routes = [
  {
    path: '/',
    component: MainPage,
    meta: { isPublic: false },
  },
  {
    path: '/word_check',
    component: WordCheck,
    meta: { isPublic: false },
  },
  {
    path: '/word_create',
    component: WordCreate,
    meta: { isPublic: false },
  },
  {
    path: '/word_list',
    component: WordList,
    meta: { isPublic: false },
  },
  {
    path: '/item_create',
    component: ItemCreate,
    meta: { isPublic: false },
  },
  {
    path: '/item_list',
    component: ItemList,
    meta: { isPublic: false },
  },
  {
    path: '/login',
    component: LoginPage,
    meta: { isPublic: true },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes: routes,
  scrollBehavior() {
    return { x: 0, y: 0 };
  },
});

router.beforeEach(async (to, _from, next) => {
  await store.dispatch('profile/getIfNeeded');

  const isLogin = store.getters['profile/isLogin'];
  if (isLogin && to.name === 'LoginPage') {
    // ログイン中にログインページにアクセスしたらトップページにリダイレクトさせる
    next({ name: 'Top' });
  } else if (!to.meta.isPublic && !isLogin) {
    // ログイン中にログインページにアクセスしたらトップページにリダイレクトさせる
    next({ name: 'Login' });
  } else {
    next();
  }
});

export default router;
