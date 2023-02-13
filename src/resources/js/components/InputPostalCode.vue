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
    default: false,
    required: false,
    type: Boolean,
  },
  invalidFeedback: {
    default: () => [],
    required: false,
    type: Array,
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
  title: {
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
  return `${props.classValue}`;
});
// 不正な値が入力されたら入力欄を赤くする
const determineCorrectInput = (input) => {
  const containedString = input.match(/[^０-９0-9-－]/g);
  incorrectInput.value = containedString?.length ? ' is-invalid' : '';
};
</script>

<template>
  <div class="input-text-wrapper">
    <input
      :aria-describedby="`${id}HelpBlock`"
      autocorrect="postal-code"
      :autocomplete="autocomplete"
      :class="'form-control border-dark ' + inputClassName + incorrectInput"
      :disabled="disabled"
      :id="id"
      inputmode="numeric"
      maxlength="8"
      :placeholder="placeholder"
      type="text"
      :value="modelValue"
      :title="title"
      @input="updateModelValue"
    />
    <div class="option-area">
      <div>{{ helperText }}</div>
    </div>
    <div class="invalid-feedback">
      <div v-for="error in invalidFeedback" :key="error">
        {{ error }}
      </div>
    </div>
  </div>
</template>

<style scoped>
.input-text-wrapper {
  margin-bottom: 1rem;
  position: relative;
}
.input-text-wrapper > input {
  width: 100px;
}
.option-area {
  display: flex;
  justify-content: space-between;
}
</style>
