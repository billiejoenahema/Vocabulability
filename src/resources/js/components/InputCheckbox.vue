<!-- インラインバージョン -->
<script setup>
import { computed } from 'vue';

const props = defineProps({
  classValue: {
    default: '',
    required: false,
    type: String,
  },
  disabled: {
    default: false,
    required: false,
    type: Boolean,
  },
  helperText: {
    default: '',
    required: false,
    type: String,
  },
  id: {
    default: '',
    required: true,
    type: String,
  },
  invalidFeedback: {
    default: '',
    required: false,
    type: String,
  },
  modelValue: {
    default: () => [],
    required: false,
    type: Array,
  },
  options: {
    default: () => [],
    required: true,
    type: Array,
  },
});
const emit = defineEmits(['update:modelValue']);
const model = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value),
});
</script>

<template>
  <div
    v-for="option in options"
    :key="option.id"
    class="form-check form-check-inline"
  >
    <input
      class="form-check-input"
      :class="classValue"
      :checked="modelValue.includes(option.id)"
      :id="option.id"
      type="checkbox"
      :value="option.id"
      v-model="model"
    />
    <label class="form-check-label" :for="option.id">
      {{ option.name }}
    </label>
  </div>
  <small class="form-text">{{ helperText }}</small>
  <div class="invalid-feedback">
    {{ invalidFeedback }}
  </div>
</template>
