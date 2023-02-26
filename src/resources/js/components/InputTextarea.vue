<script setup>
import { computed } from 'vue';
const props = defineProps({
  autocomplete: {
    default: 'on',
    required: false,
    type: [String],
    validator(value) {
      return ['on', 'off'].includes(value);
    },
  },
  classValue: {
    default: '',
    required: false,
    type: String,
  },
  cols: {
    default: '',
    required: false,
    type: [String, Number],
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
  inputLength: {
    default: false,
    required: false,
    type: Boolean,
  },
  invalidFeedback: {
    default: () => [],
    required: false,
    type: Array,
  },
  maxlength: {
    default: null,
    required: false,
    type: [String, Number],
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
  rows: {
    default: '',
    required: false,
    type: [String, Number],
  },
  title: {
    default: '',
    required: false,
    type: String,
  },
  autocorrect: {
    default: 'off',
    required: false,
    type: [String],
    validator(value) {
      return [
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
      ].includes(value);
    },
  },
  inputmode: {
    default: null,
    required: false,
    type: [String],
    validator(value) {
      return [
        'decimal',
        'email',
        'numeric',
        'search',
        'tel',
        'text',
        'url',
      ].includes(value);
    },
  },
  type: {
    default: 'text',
    required: false,
    validator(value) {
      return [
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
      ].includes(value);
    },
  },
});
const emit = defineEmits(['update:modelValue']);
const updateModelValue = (event) => {
  emit('update:modelValue', event.target.value);
};
const inputLengthClassName = computed(() => {
  if (props.modelValue.length === 0) {
    return 'text-muted';
  }
});
</script>

<template>
  <div class="base-input">
    <textarea
      :aria-describedby="`${id}HelpBlock`"
      :autocomplete="autocomplete"
      :class="'form-control border-dark textarea' + classValue"
      :cols="cols"
      :disabled="disabled"
      :id="id"
      :inputmode="inputmode"
      :maxlength="maxlength"
      :placeholder="placeholder"
      :rows="rows"
      :type="type"
      :value="modelValue"
      :title="title"
      @input="updateModelValue"
    ></textarea>
    <div class="option-area">
      <small class="text-white-50">{{ helperText }}</small>
      <small
        v-if="inputLength && maxlength"
        :class="'character-length ' + inputLengthClassName"
        >{{ modelValue.length ?? 0 }}/{{ maxlength }}</small
      >
    </div>
    <div class="invalid-feedback">
      <div v-for="error in invalidFeedback" :key="error">
        {{ error }}
      </div>
    </div>
  </div>
</template>

<style>
.base-input {
  margin-bottom: 1rem;
}
.textarea {
  margin: 0;
}
.option-area {
  display: flex;
  justify-content: space-between;
}
</style>
