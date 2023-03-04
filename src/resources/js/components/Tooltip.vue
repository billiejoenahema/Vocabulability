<script setup>
defineProps({
  content: {
    type: String,
    required: false,
    default: '',
  },
});
</script>

<template>
  <div class="tooltip-wrapper">
    <slot />
    <div class="tooltip-content">{{ content }}</div>
  </div>
</template>

<style>
.tooltip-wrapper {
  position: relative;
  cursor: pointer;
  display: inline-block;
}
.tooltip-wrapper p {
  margin: 0;
  padding: 0;
}
.tooltip-content {
  display: none;
  position: absolute;
  padding: 0.5rem;
  font-size: 0.8rem;
  color: #f1f1f1;
  border-radius: 5px;
  background: #333;
}

.tooltip-wrapper:hover .tooltip-content {
  display: flex;
}
</style>
