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
  characterCount: {
    default: false,
    required: false,
    type: Boolean,
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
  helpText: {
    default: '',
    required: false,
    type: String,
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
const characterCountClassName = computed(() => {
  if (props.modelValue.length === 0) {
    return 'text-muted';
  }
});
</script>

<template>
  <div class="base-input">
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
      :list="id"
      :title="title"
      @input="updateModelValue"
    />
    <div class="option-area">
      <div>
        <small>{{ helpText }}</small>
      </div>
      <div class="character-length">
        <small
          v-if="characterCount && maxlength"
          :class="characterCountClassName"
          >{{ modelValue.length ?? 0 }}/{{ maxlength }}</small
        >
      </div>
    </div>
    <div class="invalid-feedback">
      <div v-for="(error, index) in invalidFeedback" :key="index">
        {{ error }}
      </div>
    </div>
  </div>
</template>

<style>
.base-input {
  margin-bottom: 1rem;
}
.option-area {
  display: flex;
  justify-content: space-between;
}
.character-length {
  font-size: 0.6rem;
}
</style>
