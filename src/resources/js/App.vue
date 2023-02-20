<script setup>
import { computed } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import CircleDotsLoader from './components/CircleDotsLoader';
import Toast from './components/Toast';
import Navigation from './views/Navigation';

const router = useRouter();
const store = useStore();

const loading = computed(() => store.getters['loading/loading']);
</script>

<template>
  <main>
    <CircleDotsLoader :loading="loading" />
    <Toast />
    <Navigation v-if="router.currentRoute.value.path !== '/login'" />
    <router-view />
  </main>
</template>
