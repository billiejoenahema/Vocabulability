<script setup>
import { ref, watchEffect } from 'vue';

const props = defineProps({
  isAsc: {
    type: Boolean,
    required: false,
    default: '',
  },
  active: {
    type: Boolean,
    required: false,
    default: false,
  },
});
const sortDirectionTextValue = ref('');
watchEffect(() => {
  if (!props.active) {
    sortDirectionTextValue.value = 'sort';
    return;
  }
  if (props.isAsc) {
    sortDirectionTextValue.value = 'sort-up';
  } else {
    sortDirectionTextValue.value = 'sort-down';
  }
});
</script>

<template>
  <div class="sort-direction-icon-wrapper">
    <font-awesome-icon
      class="sort-direction-icon"
      :icon="sortDirectionTextValue"
    />
  </div>
</template>

<style scoped>
.sort-direction-icon-wrapper {
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 0 8px;
}
</style>
