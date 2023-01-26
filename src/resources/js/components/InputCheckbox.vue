<script setup>
import { ref } from 'vue';
defineProps({
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
  legend: {
    default: '',
    required: false,
    type: String,
  },
  modelValue: {
    default: '',
    required: false,
    type: [String, Number],
  },
  options: {
    default: () => [],
    required: true,
    type: Array,
  },
  title: {
    default: '',
    required: false,
    type: String,
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
  <fieldset class="mb-2 row">
    <legend>{{ legend }}</legend>
    <div v-for="option in options" :key="option.id" class="mr-2">
      <input
        type="radio"
        id="male"
        name="gender"
        :value="option.id"
        :checked="option.id === modelValue"
        @input="updateModelValue"
      />
      <label for="male">{{ option.name }}</label>
    </div>
  </fieldset>
  <datalist :id="'data_list_' + id">
    <option v-for="item in dataList" :key="item">
      {{ item }}
    </option>
  </datalist>
  <div class="hint-area" v-if="exampleValue">
    <small class="input-example" @click="toggleHintTextShow()">{{
      exampleValue
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
