<script setup>
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import Avatar from '../components/Avatar.vue';

const store = useStore();
const router = useRouter();
const user = computed(() => store.getters['profile/data']);
store.dispatch('consts/getIfNeeded');

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
      <router-link to="/profile" class="nav-item">
        <Avatar :avatar="user.avatar" />
      </router-link>
    </div>
  </nav>
</template>
