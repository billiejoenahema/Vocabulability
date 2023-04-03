<script setup>
import { computed, ref } from 'vue';
const props = defineProps({
  autocomplete: {
    default: 'on',
    required: false,
    type: [String],
    validator(value) {
      return ['on', 'off'].includes(value);
    },
  },
  autocorrect: {
    default: 'off',
    required: false,
    type: [String],
    validator(value) {
      return ['off', 'email'].includes(value);
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
  inputtingPlaceholder: {
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
});
const inputCorrectness = ref('');
const emailRegex =
  /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
const emit = defineEmits(['update:modelValue']);
const updateModelValue = (e) => {
  emit('update:modelValue', e.target.value);
};
const inputClassName = computed(() => {
  return props.inputtingPlaceholder === 'on'
    ? `${props.classValue} ${inputCorrectness.value} show-inputting-placeholder`
    : `${props.classValue} ${inputCorrectness.value}`;
});
const showInputtingPlaceholder = computed(
  () => props.inputtingPlaceholder === 'on' && props.modelValue
);
// 正しいメールアドレスかどうかを判定する
const determineInputValue = (e) => {
  if (e.target.value === '') {
    inputCorrectness.value = '';
    return;
  }
  inputCorrectness.value = emailRegex.test(e.target.value)
    ? 'is-valid'
    : 'is-invalid';
};
</script>

<template>
  <div class="input-wrapper">
    <input
      :aria-describedby="`${id}HelpBlock`"
      :autocomplete="autocomplete"
      :autocorrect="autocorrect"
      class="form-control border-dark"
      :class="inputClassName"
      :disabled="disabled"
      :id="id"
      inputmode="email"
      :maxlength="maxlength"
      :placeholder="placeholder"
      type="email"
      :value="modelValue"
      @input="updateModelValue"
      @blur="determineInputValue"
    />
    <div
      v-if="showInputtingPlaceholder"
      class="inputting-placeholder text-muted"
    >
      {{ placeholder }}
    </div>
    <div class="form-text-wrapper">
      <div :id="`${id}HelpBlock`" class="form-text text-muted">
        {{ helperText }}
      </div>
    </div>
    <div class="invalid-feedback">
      <div v-if="inputCorrectness === 'is-invalid' && !invalidFeedback">
        正しい形式で入力してください。
      </div>
      {{ invalidFeedback }}
    </div>
  </div>
</template>

<style scoped>
.input-wrapper {
  position: relative;
}
.inputting-placeholder {
  position: absolute;
  top: 0;
  left: 8px;
  font-size: 0.5rem;
}
.form-text-wrapper {
  display: flex;
  justify-content: space-between;
}
</style>
