<script setup>
defineProps({
  invalidFeedback: {
    type: String,
    default: '',
  },
});
</script>

<template>
  <div class="invalid-feedback">
    {{ invalidFeedback }}
  </div>
</template>

<style scoped>
.invalidFeedback {
  color: red;
  font-size: 0.6rem;
}
</style>
