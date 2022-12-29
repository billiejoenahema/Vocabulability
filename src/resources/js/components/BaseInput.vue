<script setup>
defineProps({
  autocomplete: {
    default: 'off',
    required: false,
    type: [String],
    validator(value) {
      return ['on', 'off'].indexOf(value) !== -1;
    },
  },
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
  maxlength: {
    default: null,
    required: false,
    type: Number,
  },
  modelValue: {
    default: '',
    required: false,
    type: [String, Number],
  },
  placeholder: {
    default: '',
    required: false,
    type: String,
  },
  autocorrect: {
    default: 'off',
    required: false,
    type: [String],
    validator(value) {
      return (
        [
          'address-level1',
          'address-level2',
          'address-line1',
          'address-line2',
          'email',
          'family-name',
          'given-name',
          'name',
          'off',
          'organization',
          'postal-code',
        ].indexOf(value) !== -1
      );
    },
  },
  inputmode: {
    default: null,
    required: false,
    type: [String],
    validator(value) {
      return (
        ['decimal', 'email', 'numeric', 'search', 'tel', 'text', 'url'].indexOf(
          value
        ) !== -1
      );
    },
  },
  type: {
    default: 'text',
    required: false,
    validator(value) {
      return (
        [
          'date',
          'datetime-local',
          'email',
          'month',
          'password',
          'search',
          'text',
          'tel',
          'time',
          'url',
        ].indexOf(value) !== -1
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
    :aria-describedby="`${id}HelpBlock`"
    :autocomplete="autocomplete"
    :class="classValue"
    class="form-control border-dark"
    :disabled="disabled"
    :id="id"
    :inputmode="inputmode"
    :maxlength="maxlength"
    :placeholder="placeholder"
    :type="type"
    :value="modelValue"
    @input="updateModelValue"
  />
  <div class="invalid-feedback">
    <div v-for="(error, index) in invalidFeedback" :key="index">
      {{ error }}
    </div>
  </div>
</template>
