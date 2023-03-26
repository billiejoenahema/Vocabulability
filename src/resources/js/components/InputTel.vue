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
const incorrectInput = ref('');
const emit = defineEmits(['update:modelValue']);
const updateModelValue = (event) => {
  emit('update:modelValue', event.target.value);
  determineCorrectInput(event.target.value);
};
const inputClassName = computed(() => {
  return props.inputtingPlaceholder === 'on'
    ? `${props.classValue} ${incorrectInput.value} show-inputting-placeholder`
    : `${props.classValue} ${incorrectInput.value}`;
});
const showInputtingPlaceholder = computed(
  () => props.inputtingPlaceholder === 'on' && props.modelValue
);
// 不正な値が入力されたら入力欄を赤くする
//   半角数字のみ許可 /[^0-9]/g
//   数字のみ許可 /[^０-９0-9]/g
//   数字とハイフンのみ許可 /[^０-９0-9-－]/g
const determineCorrectInput = (input) => {
  const inputtedString = input.match(/[^0-9]/g);
  incorrectInput.value = inputtedString?.length ? 'is-invalid' : '';
};
</script>

<template>
  <div class="input-wrapper">
    <input
      :aria-describedby="`${id}HelpBlock`"
      :autocomplete="autocomplete"
      class="form-control border-dark"
      :class="inputClassName"
      :disabled="disabled"
      :id="id"
      inputmode="tel"
      :maxlength="maxlength"
      :placeholder="placeholder"
      type="tel"
      :value="modelValue"
      @input="updateModelValue"
    />
    <div
      v-if="showInputtingPlaceholder"
      class="inputting-placeholder text-muted"
    >
      {{ placeholder }}
    </div>
    <div class="form-text-area">
      <div :id="`${id}HelpBlock`" class="form-text text-muted">
        {{ helperText }}
      </div>
    </div>
    <div class="invalid-feedback">
      <div v-if="incorrectInput && !invalidFeedback">
        半角数字のみで入力してください。
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
.form-text-area {
  display: flex;
  justify-content: space-between;
}
.form-text {
  font-size: 0.6em;
}
</style>
