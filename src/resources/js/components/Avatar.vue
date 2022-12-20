<script setup>
defineProps({
  avatar: {
    type: String,
    required: false,
    default: '',
  },
});
</script>

<template>
  <img :src="avatar ?? '/no_image.jpg'" class="avatar" />
</template>

<style>
.avatar {
  height: 24px;
  width: 24px;
  border-radius: 5px;
}
</style>
