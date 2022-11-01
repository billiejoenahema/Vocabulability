<script setup>
import { useRouter } from 'vue-router';
import Toast from './components/Toast';
import Navigation from './views/Navigation';
const router = useRouter();
</script>

<template>
  <main>
    <Toast />
    <Navigation v-if="router.currentRoute.value.path !== '/login'" />
    <router-view />
  </main>
</template>
