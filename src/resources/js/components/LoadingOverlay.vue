<script setup>
import Loading from 'vue3-loading-overlay/dist/index';
import 'vue3-loading-overlay/dist/vue3-loading-overlay.css';

defineProps({
  isLoading: {
    type: Boolean,
    default: false,
  },
});
const { color, background, opacity } = {
  color: '#fff',
  background: '#fff',
  opacity: 0.1,
};
</script>

<template>
  <Loading
    :active="isLoading"
    :color="color"
    :background-color="background"
    :opacity="opacity"
  />
</template>
