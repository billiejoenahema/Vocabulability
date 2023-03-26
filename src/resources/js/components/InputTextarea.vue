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
const showInputCounter = computed(
  () => props.inputCounter === 'on' && props.maxlength
);
const textMuted = computed(() =>
  props.modelValue?.length === 0 ? 'text-muted' : ''
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
  <div class="form-text-wrapper">
    <div :id="`${id}HelpBlock`" class="form-text text-muted">
      {{ helperText }}
    </div>
    <div v-if="showInputCounter" :class="'form-text ' + textMuted">
      {{ modelValue?.length ?? 0 }}/{{ maxlength }}
    </div>
  </div>
  <div class="invalid-feedback">
    {{ invalidFeedback }}
  </div>
</template>

<style scoped>
.textarea {
  margin: 0;
}
.form-text-wrapper {
  display: flex;
  justify-content: space-between;
}
</style>
