<script setup>
import { computed, ref } from 'vue';

const props = defineProps({
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
  helpText: {
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
  modelValue: {
    default: '',
    required: false,
    type: String,
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
const emit = defineEmits(['update:modelValue']);
const hintTextShow = ref(false);
const toggleHintTextShow = () => {
  hintTextShow.value = !hintTextShow.value;
};
const yearRef = ref(null);
const monthRef = ref(null);
const dayRef = ref(null);

const date = computed(() => {
  const splittedDate = props.modelValue?.split('-') ?? [];
  return {
    year: splittedDate[0] ?? null,
    month: splittedDate[1] ?? null,
    day: splittedDate[2] ?? null,
  };
});
const onInput = (e) => {
  if (e.target === yearRef.value && yearRef.value?.value.length === 4) {
    monthRef.value.focus();
  }
  if (e.target === monthRef.value && monthRef.value?.value.length === 2) {
    dayRef.value.focus();
  }
  const updatedDate = `${yearRef.value.value}-${monthRef.value.value}-${dayRef.value.value}`;
  emit('update:modelValue', updatedDate);
};
</script>

<template>
  <div class="base-input">
    <input
      :aria-describedby="`${id}HelpBlock`"
      :class="classValue"
      class="form-control border-dark input-year"
      :disabled="disabled"
      :id="id"
      maxlength="4"
      :placeholder="placeholder"
      type="text"
      :value="date.year"
      :list="id"
      :title="title"
      ref="yearRef"
      @input="onInput"
    />
    <span>年</span>
    <input
      :aria-describedby="`${id}HelpBlock`"
      :class="classValue"
      class="form-control border-dark input-month-day"
      :disabled="disabled"
      :id="id"
      maxlength="2"
      :placeholder="placeholder"
      type="text"
      :value="date.month"
      :list="id"
      :title="title"
      ref="monthRef"
      @input="onInput"
    />
    <span>月</span>
    <input
      :aria-describedby="`${id}HelpBlock`"
      :class="classValue"
      class="form-control border-dark input-month-day"
      :disabled="disabled"
      :id="id"
      maxlength="2"
      :placeholder="placeholder"
      type="text"
      :value="date.day"
      :list="id"
      :title="title"
      ref="dayRef"
      @input="onInput"
    />
    <span>日</span>
    <datalist :id="'data_list_' + id">
      <option v-for="item in dataList" :key="item">
        {{ item }}
      </option>
    </datalist>
    <div class="hint-area" v-if="helpText">
      <small class="help-text" @click="toggleHintTextShow()">{{
        helpText
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
.input-year {
  width: 4rem;
  margin-right: 8px;
  margin-bottom: 8px;
  text-align: right;
}
.input-month-day {
  width: 3rem;
  margin-right: 8px;
  margin-left: 16px;
  margin-bottom: 8px;
  text-align: right;
}
.base-input {
  margin-bottom: 1rem;
}
.help-text {
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
