<script setup>
defineProps({
  isLoading: {
    type: Boolean,
    default: false,
  },
});
</script>

<template>
  <div class="loading-overlay" v-show="isLoading">
    <div class="spinner-border" role="status">
      <span class="sr-only">Uploading...</span>
    </div>
  </div>
</template>

<style scoped>
.loading-overlay {
  display: flex;
  justify-content: center;
  align-items: center;
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  width: 100vw;
  z-index: 10000;
}
</style>
