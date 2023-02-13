<script setup>
defineProps({
  errors: {
    type: Array,
    default: () => [],
  },
});
</script>

<template>
  <div class="errors">
    <div v-for="error in errors" :key="error">
      {{ error }}
    </div>
  </div>
</template>

<style scoped>
.errors {
  color: red;
  font-size: 0.6rem;
}
</style>
