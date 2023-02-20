<script setup>
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import CircleDotsLoader from './components/CircleDotsLoader';
import Toast from './components/Toast';
import Navigation from './views/Navigation';

const router = useRouter();
const store = useStore();

const isLoading = computed(() => store.getters['loading/isLoading']);
</script>

<template>
  <main>
    <CircleDotsLoader :isLoading="isLoading" />
    <Toast />
    <Navigation v-if="router.currentRoute.value.path !== '/login'" />
    <router-view />
  </main>
</template>
