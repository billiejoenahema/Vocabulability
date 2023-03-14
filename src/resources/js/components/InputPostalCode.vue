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
  invalidFeedback: {
    default: '',
    required: false,
    type: String,
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
const search = () => {
  emit('search', props.modelValue);
};
const inputClassName = computed(() => {
  return `${props.classValue}`;
});
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
  <div class="input-text-wrapper">
    <form @submit.prevent="search" class="input-area">
      <input
        :aria-describedby="`${id}HelpBlock`"
        autocorrect="postal-code"
        :autocomplete="autocomplete"
        :class="
          'form-control border-dark me-2 ' + inputClassName + incorrectInput
        "
        :disabled="disabled"
        :id="id"
        inputmode="numeric"
        maxlength="8"
        :placeholder="placeholder"
        type="text"
        :value="modelValue"
        @input="updateModelValue"
      />
      <button class="address-search-button" type="button" @click="search">
        住所を検索
      </button>
    </form>
    <div class="form-text-area">
      <div :id="`${id}HelpBlock`" class="form-text text-muted">
        {{ helperText }}
      </div>
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
.input-area {
  display: flex;
  flex-direction: row;
}
.input-area > input {
  margin-right: 2rem;
}
.address-search-button {
  white-space: nowrap;
}
.form-text-area {
  display: flex;
  justify-content: space-between;
}
.form-text {
  font-size: 0.6em;
}
</style>
