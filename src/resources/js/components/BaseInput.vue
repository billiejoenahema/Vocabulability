<script setup>
import { ref } from 'vue';
defineProps({
  autocomplete: {
    default: 'off',
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
  dataList: {
    default: () => [],
    required: false,
    type: Array,
  },
  disabled: {
    default: false,
    required: false,
    type: Boolean,
  },
  exampleValue: {
    default: '',
    required: false,
    type: String,
  },
  hintText: {
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
const hintTextShow = ref(false);
const toggleHintTextShow = () => {
  hintTextShow.value = !hintTextShow.value;
};
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
    <datalist :id="'data_list_' + id">
      <option v-for="item in dataList" :key="item">
        {{ item }}
      </option>
    </datalist>
    <div class="hint-area" v-if="exampleText">
      <small class="input-example" @click="toggleHintTextShow()">{{
        exampleText
      }}</small>
      <div
        class="hint-text"
        v-if="hintText && hintTextShow"
        @click="toggleHintTextShow()"
      >
        {{ hintText }}
        <teleport to="body">
          <div class="backdrop" @click="toggleHintTextShow()"></div>
        </teleport>
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
.input-example {
  color: rgb(141, 141, 141);
  padding: 0 1rem;
}
.hint-area {
  position: relative;
}
.hint-text {
  position: absolute;
  top: 0;
  padding: 16px;
  background: rgb(222, 222, 222);
}
.backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
}
</style>
