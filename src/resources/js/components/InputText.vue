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
  // 入力中のプレースホルダー表示/非表示
  inputtingPlaceholder: {
    default: false,
    required: false,
    type: Boolean,
  },
  // 入力文字数カウント表示/非表示
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
const inputClassName = computed(() => {
  return props.inputtingPlaceholder
    ? `${props.classValue} show-inputting-placeholder`
    : props.classValue;
});
const inputLengthClassName = computed(() => {
  if (props.modelValue.length === 0) {
    return 'text-muted';
  }
});
</script>

<template>
  <div class="input-text-wrapper">
    <input
      :aria-describedby="`${id}HelpBlock`"
      :autocomplete="autocomplete"
      :autocorrect="autocorrect"
      :class="'form-control border-dark ' + inputClassName"
      :disabled="disabled"
      :id="id"
      :inputmode="inputmode"
      :maxlength="maxlength"
      :placeholder="placeholder"
      :type="type"
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
      <small v-if="inputLength && maxlength" :class="inputLengthClassName">
        {{ modelValue.length ?? 0 }}/{{ maxlength }}
      </small>
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
</style>
