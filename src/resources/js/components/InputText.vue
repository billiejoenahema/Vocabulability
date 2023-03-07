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
  // 入力文字数カウント表示/非表示
  inputLength: {
    default: 'off',
    required: false,
    type: String,
    validator(value) {
      return ['on', 'off'].includes(value);
    },
  },
  inputmode: {
    default: 'text',
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
  // 入力中のプレースホルダー表示/非表示
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
const showInputLength = computed(
  () => props.inputLength === 'on' && props.maxlength
);
const showInputtingPlaceholder = computed(
  () => props.inputtingPlaceholder === 'on' && props.modelValue
);
</script>

<template>
  <div class="input-text-wrapper">
    <input
      :aria-describedby="`${id}Help`"
      :autocomplete="autocomplete"
      :autocorrect="autocorrect"
      :class="'form-control border-dark ' + classValue"
      :disabled="disabled"
      :id="id"
      :inputmode="inputmode"
      :maxlength="maxlength"
      :placeholder="placeholder"
      :type="type"
      :value="modelValue"
      @input="updateModelValue"
    />
    <div
      v-if="showInputtingPlaceholder"
      class="inputting-placeholder text-muted"
    >
      {{ placeholder }}
    </div>
    <div class="option-area">
      <small :id="`${id}Help`">{{ helperText }}</small>
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
