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
  <div class="tooltip">
    <slot />
    <div class="description">{{ content }}</div>
  </div>
</template>

<style>
.tooltip {
  position: relative;
  cursor: pointer;
  display: inline-block;
}
.tooltip p {
  margin: 0;
  padding: 0;
}
.description {
  display: none;
  position: absolute;
  padding: 10px;
  font-size: 12px;
  line-height: 1.6em;
  color: #fff;
  border-radius: 5px;
  background: #000;
}

.tooltip:hover .description {
  display: flex;
}
</style>
