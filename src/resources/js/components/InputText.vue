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
  forIdValue: {
    default: '',
    required: true,
    type: String,
  },
  invalidFeedback: {
    default: '',
    required: false,
    type: String,
  },
  maxlength: {
    default: null,
    required: false,
    type: Number,
  },
  placeholder: {
    default: '',
    required: false,
    type: String,
  },
  modelValue: {
    default: '',
    required: false,
    type: [String, Number],
  },
  type: {
    default: 'text',
    required: false,
    validator(value) {
      return (
        value === 'text' ||
        value === 'email' ||
        value === 'password' ||
        value === 'month' ||
        value === 'date' ||
        value === 'time' ||
        value === 'datetime-local'
      );
    },
  },
});
const emit = defineEmits(['update:modelValue']);
const updateModelValue = (event) => {
  emit('update:modelValue', event.target.value);
};
</script>

<template>
  <input
    :id="forIdValue"
    class="form-control border-dark"
    :value="modelValue"
    :class="classValue"
    :type="type"
    :disabled="disabled"
    :aria-describedby="`${forIdValue}HelpBlock`"
    :maxlength="maxlength"
    :placeholder="placeholder"
    @input="updateModelValue"
  />
  <div class="invalid-feedback">
    {{ invalidFeedback }}
  </div>
</template>
