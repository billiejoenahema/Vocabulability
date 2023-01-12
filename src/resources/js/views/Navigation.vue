<script setup>
import { computed, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import Avatar from '../components/Avatar.vue';

const store = useStore();
const router = useRouter();
const user = computed(() => store.getters['profile/data']);
store.dispatch('consts/getIfNeeded');

const showMenu = ref(false);

const logout = async () => {
  if (confirm('ログアウトしますか？')) {
    await store.dispatch('auth/logout');
    await store.commit('profile/setData', {});
    router.push('/login');
  }
};
</script>

<template>
  <nav class="header-nav">
    <router-link to="/" class="brand-logo">Vocabulability</router-link>
    <router-link to="/word_check" class="nav-item">WordCheck</router-link>
    <router-link to="/word_list" class="nav-item">WordList</router-link>
    <router-link to="/word_create" class="nav-item">WordCreate</router-link>
    <router-link to="/item_list" class="nav-item">ItemList</router-link>
    <router-link to="/item_create" class="nav-item">ItemCreate</router-link>
    <div class="row nav-icon-group">
      <a class="logout" @click.prevent.stop="logout()">Logout</a>
      <div class="nav-item" @click="showMenu = !showMenu">
        <Avatar :avatar="user.avatar" />
      </div>
    </div>
  </nav>
  <teleport to="body">
    <div class="backdrop" v-if="showMenu" @click.self="showMenu = false">
      <ul class="dropdown-menu user-menu">
        <li><a class="dropdown-item" href="/">Home</a></li>
        <li><a class="dropdown-item" href="word_check">WordCheck</a></li>
        <li><a class="dropdown-item" href="word_list">WordList</a></li>
        <li><a class="dropdown-item" href="word_create">WordCreate</a></li>
        <li><a class="dropdown-item" href="item_list">ItemList</a></li>
        <li><a class="dropdown-item" href="item_create">ItemCreate</a></li>
        <li>
          <a class="dropdown-item text-decoration-none" href="profile"
            >Profile</a
          >
        </li>
      </ul>
    </div>
  </teleport>
</template>
