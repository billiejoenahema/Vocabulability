<script setup>
import { computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const router = useRouter();

const isLogin = computed(() => store.getters['profile/isLogin']);
const logout = async () => {
  if (confirm('ログアウトしますか？')) {
    await store.dispatch('auth/logout');
    store.commit('profile/resetData');
    router.push('/login');
  }
};
onMounted(async () => {
  await store.dispatch('profile/getIfNeeded');
  if (!isLogin.value) {
    router.push('/login');
  }
});
</script>

<template>
  <nav>
    <router-link to="/">Home</router-link>
    <router-link to="/word_check">WordCheck</router-link>
    <router-link to="/word_list">WordList</router-link>
    <router-link to="/word_create">WordCreate</router-link>
    <router-link to="/item_list">ItemList</router-link>
    <router-link to="/item_create">ItemCreate</router-link>
    <a class="logout" @click.prevent.stop="logout()">Logout</a>
  </nav>
</template>
