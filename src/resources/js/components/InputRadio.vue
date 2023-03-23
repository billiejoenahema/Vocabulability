<script setup>
defineProps({
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
  legend: {
    default: '',
    required: false,
    type: String,
  },
  modelValue: {
    default: '',
    required: false,
    type: [String, Number],
  },
  options: {
    default: () => [],
    required: true,
    type: Array,
  },
});
const emit = defineEmits(['update:modelValue']);
const updateModelValue = (event) => {
  emit('update:modelValue', event.target.value);
};
</script>

<template>
  <fieldset class="mb-2">
    <legend>{{ legend }}</legend>
    <div class="checkbox-body">
      <div v-for="option in options" :key="option.id" class="options">
        <input
          type="radio"
          :class="classValue"
          :id="option.id"
          :name="id"
          :value="option.id"
          :checked="option.id === modelValue"
          @input="updateModelValue"
        />
        <label :for="option.id">{{ option.name }}</label>
      </div>
      <small class="helper-text">{{ helperText }}</small>
    </div>
    <div class="invalid-feedback">
      {{ invalidFeedback }}
    </div>
  </fieldset>
</template>

<style scoped>
.base-input {
  margin-bottom: 1rem;
}
.checkbox-body {
  display: flex;
  flex-direction: column;
}
.options {
  display: flex;
  flex-direction: row;
  margin: 8px 0;
}
</style>
