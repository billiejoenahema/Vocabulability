<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import Avatar from '../components/Avatar.vue';

const store = useStore();
const router = useRouter();
const user = computed(() => store.getters['profile/data']);
const isLogin = computed(() => store.getters['profile/isLogin']);

onMounted(() => {
  // ログイン中のときのみ定数を取得する
  isLogin.value && store.dispatch('consts/getIfNeeded');
});

const showMenu = ref(false);
const avatarRef = ref(null);
const menuRef = ref(null);
const clickOutside = (e) => {
  if (avatarRef.value?.contains(e.target)) return;
  // メニュー以外をクリックしたらメニューを閉じる
  if (e.target instanceof Node && !menuRef.value?.contains(e.target)) {
    showMenu.value = false;
  }
};

// windowにセットしたイベントはremoveするのを忘れずに
onMounted(() => {
  addEventListener('click', clickOutside);
});
onBeforeUnmount(() => {
  removeEventListener('click', clickOutside);
});

const logout = async () => {
  if (confirm('ログアウトしますか？')) {
    await store.dispatch('auth/logout');
    await store.commit('profile/setData', {});
    router.push('/login');
  }
};
</script>

<template>
  <nav id="navigation">
    <router-link to="/" class="brand-logo">Vocabulability</router-link>
    <router-link to="/word_check" class="nav-item">WordCheck</router-link>
    <router-link to="/word_list" class="nav-item">WordList</router-link>
    <router-link to="/word_create" class="nav-item">WordCreate</router-link>
    <router-link to="/item_list" class="nav-item">ItemList</router-link>
    <router-link to="/item_create" class="nav-item">ItemCreate</router-link>
    <div class="row nav-icon-group">
      <a class="logout" @click.prevent.stop="logout()">Logout</a>
      <div class="nav-item" ref="avatarRef" @click="showMenu = !showMenu">
        <Avatar :avatar="user.avatar" />
      </div>
      <ul
        v-if="showMenu"
        ref="menuRef"
        @click.self="showMenu = false"
        class="dropdown-menu user-menu"
      >
        <li>
          <router-link to="/" class="dropdown-item" @click="showMenu = false"
            >Home</router-link
          >
        </li>
        <li>
          <router-link
            to="/word_check"
            class="dropdown-item"
            @click="showMenu = false"
            >WordCheck</router-link
          >
        </li>
        <li>
          <router-link
            to="/word_list"
            class="dropdown-item"
            @click="showMenu = false"
            >WordList</router-link
          >
        </li>
        <li>
          <router-link
            to="/word_create"
            class="dropdown-item"
            @click="showMenu = false"
            >WordCreate</router-link
          >
        </li>
        <li>
          <router-link
            to="/item_list"
            class="dropdown-item"
            @click="showMenu = false"
            >ItemList</router-link
          >
        </li>
        <li>
          <router-link
            to="/item_create"
            class="dropdown-item"
            @click="showMenu = false"
            >ItemCreate</router-link
          >
        </li>
        <li>
          <router-link
            to="/profile"
            class="dropdown-item text-decoration-none"
            @click="showMenu = false"
            >Profile</router-link
          >
        </li>
      </ul>
    </div>
  </nav>
</template>
