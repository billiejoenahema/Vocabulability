<script setup>
defineProps({
  meta: {
    type: Object,
    required: false,
    default: () => {},
  },
});
</script>

<template>
  <div v-if="meta" class="result-info">
    {{ meta.from + '-' + meta.to + ' of ' + meta.total }}
  </div>
</template>

<style>
.result-info {
  margin-bottom: 8px;
}
</style>
