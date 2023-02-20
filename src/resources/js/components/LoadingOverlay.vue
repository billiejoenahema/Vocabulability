<script setup>
import Loading from 'vue3-loading-overlay/dist/index';
import 'vue3-loading-overlay/dist/vue3-loading-overlay.css';

defineProps({
  loading: {
    type: Boolean,
    default: false,
  },
});
const { color, background, opacity } = {
  color: '#fff',
  background: '#fff',
  opacity: 0,
};
</script>

<template>
  <Loading
    :active="loading"
    :color="color"
    :background-color="background"
    :opacity="opacity"
  />
</template>
