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
  return props.inputtingPlaceholder
    ? `${props.classValue} ${incorrectInput.value} show-inputting-placeholder`
    : `${props.classValue} ${incorrectInput.value}`;
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
      :autocomplete="autocomplete"
      :class="'form-control border-dark ' + inputClassName"
      :disabled="disabled"
      :id="id"
      inputmode="tel"
      :maxlength="maxlength"
      :placeholder="placeholder"
      type="tel"
      :value="modelValue"
      :title="title"
      @input="updateModelValue"
    />
    <div
      v-if="inputtingPlaceholder && modelValue"
      class="inputting-placeholder text-muted"
    >
      {{ placeholder }}
    </div>
    <div class="option-area">
      <div>{{ helperText }}</div>
    </div>
    <div class="invalid-feedback">
      <div v-if="incorrectInput && !invalidFeedback.length">
        数字とハイフン以外は入力しないでください。
      </div>
      <div v-for="(error, index) in invalidFeedback" :key="index">
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
.show-inputting-placeholder {
  padding: 1rem 0.5rem 0.5rem;
}
.inputting-placeholder {
  position: absolute;
  top: 0;
  left: 8px;
  font-size: 0.6rem;
}
.option-area {
  display: flex;
  justify-content: space-between;
}
.character-length {
  font-size: 0.6rem;
}
</style>
