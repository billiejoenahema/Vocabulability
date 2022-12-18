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
  <div class="data-count">
    {{ meta.from + '-' + meta.to + ' of ' + meta.total }}
  </div>
</template>

<style>
.data-count {
  margin-bottom: 8px;
}
</style>
