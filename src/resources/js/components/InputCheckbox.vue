<script setup>
defineProps({
  classValue: {
    default: '',
    required: false,
    type: String,
  },
  dataList: {
    default: () => [],
    required: false,
    type: Array,
  },
  disabled: {
    default: false,
    required: false,
    type: Boolean,
  },
  helpText: {
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
    default: () => [],
    required: false,
    type: Array,
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
  <fieldset class="mb-2 row">
    <legend>{{ legend }}</legend>
    <div class="checkbox-body">
      <div v-for="option in options" :key="option.id" class="options">
        <input
          type="radio"
          :id="'gender_' + option.id"
          :name="'gender_' + option.id"
          :value="option.id"
          :checked="option.id === modelValue"
          @input="updateModelValue"
        />
        <label :for="'gender_' + option.id">{{ option.name }}</label>
      </div>
      <small class="help-text">{{ helpText }}</small>
    </div>
  </fieldset>

  <div class="invalid-feedback">
    <div v-for="(error, index) in invalidFeedback" :key="index">
      {{ error }}
    </div>
  </div>
</template>

<style>
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
