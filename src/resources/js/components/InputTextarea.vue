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
  // 入力文字数カウント表示/非表示
  inputCounter: {
    default: 'off',
    required: false,
    type: String,
    validator(value) {
      return ['on', 'off'].includes(value);
    },
  },
  invalidFeedback: {
    default: '',
    required: false,
    type: String,
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
});
const emit = defineEmits(['update:modelValue']);
const updateModelValue = (event) => {
  emit('update:modelValue', event.target.value);
};
const showInputLength = computed(
  () => props.inputLength === 'on' && props.maxlength
);
</script>

<template>
  <textarea
    :aria-describedby="`${id}HelpBlock`"
    :autocomplete="autocomplete"
    :class="'form-control border-dark textarea ' + classValue"
    :cols="cols"
    :disabled="disabled"
    :id="id"
    :maxlength="maxlength"
    :placeholder="placeholder"
    :rows="rows"
    :value="modelValue"
    @input="updateModelValue"
  ></textarea>
  <div class="option-area">
    <small class="text-white-50">{{ helperText }}</small>
    <small
      v-if="showInputLength"
      :class="modelValue.length === 0 ? 'text-muted' : ''"
    >
      {{ modelValue.length ?? 0 }}/{{ maxlength }}
    </small>
  </div>
  <div class="invalid-feedback">
    {{ invalidFeedback }}
  </div>
</template>

<style>
.textarea {
  margin: 0;
}
.option-area {
  display: flex;
  justify-content: space-between;
}
</style>
