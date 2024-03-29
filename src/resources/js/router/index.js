import { createRouter, createWebHistory } from 'vue-router';
import { store } from '../store/index';
import ItemCreate from '../views/Item/ItemCreate';
import ItemList from '../views/Item/ItemList';
import Login from '../views/Login';
import Main from '../views/Main';
import NotFound from '../views/NotFound';
import PasswordReset from '../views/PasswordReset';
import ProfileDetail from '../views/Profile/Detail';
import ProfileEdit from '../views/Profile/Edit';
import WordCheck from '../views/Word/WordCheck';
import WordCreate from '../views/Word/WordCreate';
import WordList from '../views/Word/WordList';

const routes = [
  {
    path: '/',
    component: Main,
    meta: { isPublic: false },
  },
  {
    path: '/profile',
    component: ProfileDetail,
    meta: { isPublic: false },
  },
  {
    path: '/profile/edit',
    component: ProfileEdit,
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
    component: Login,
    meta: { isPublic: true },
  },
  {
    path: '/password-reset',
    component: PasswordReset,
  },
  {
    path: '/:catchAll(.*)',
    component: NotFound,
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
  if (isLogin && to.path === '/login') {
    // ログイン中にログインページにアクセスしたらトップページにリダイレクトさせる
    next('/');
  } else if (!to.meta.isPublic && !isLogin) {
    // ログインせずに非公開ページにアクセスしたらログインページにリダイレクトさせる
    next('/login');
  } else {
    store.dispatch('consts/getIfNeeded');
    next();
  }
});

router.afterEach(() => {
  resetErrors();
});

// エラーメッセージを初期化
const resetErrors = () => {
  store.commit('auth/setErrors', {});
  store.commit('item/setErrors', {});
  store.commit('precedent/setErrors', {});
  store.commit('profile/setErrors', {});
  store.commit('question/setErrors', {});
  store.commit('test_mail/setErrors', {});
  store.commit('upload_file/setErrors', {});
};

export default router;
