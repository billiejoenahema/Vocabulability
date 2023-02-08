<script setup>
import { computed, reactive, ref } from 'vue';

const props = defineProps({
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
    default: '1970-01-23',
    required: false,
    type: String,
  },
  tooltipTitle: {
    default: '',
    required: false,
    type: String,
  },
});
const emit = defineEmits(['update:modelValue']);
const [placeholderYear, placeholderMonth, placeholderDay] =
  props.placeholder.split('-');
const yearRef = ref(null);
const monthRef = ref(null);
const dayRef = ref(null);
const className = computed(() => ({
  year: `${invalidInputClassName.year} ${props.classValue}`,
  month: `${invalidInputClassName.month} ${props.classValue}`,
  day: `${invalidInputClassName.day} ${props.classValue}`,
}));
const invalidInputClassName = reactive({
  year: '',
  month: '',
  day: '',
});
const date = computed(() => {
  const [year, month, day] = props.modelValue
    ? props.modelValue.split('-')
    : ['', '', ''];
  return {
    year: year,
    month: month,
    day: day,
  };
});
// 整数または空文字であるかどうか
const isNumberOnly = (v) => v.match(/^[0-9]*$/);
// 有効な月であるかどうか
const validMonth = (v) => {
  return v === '' || (1 <= Number(v) && Number(v) <= 12);
};
// 有効な日であるかどうか
const validDay = (v) => {
  return v === '' || (1 <= Number(v) && Number(v) <= 31);
};
const onInput = (e) => {
  // 入力値が数字以外の文字を含む場合は入力欄を赤くする
  if (isNumberOnly(yearRef.value.value)) {
    invalidInputClassName.year = '';
  } else {
    invalidInputClassName.year = 'invalid-input';
  }
  if (isNumberOnly(monthRef.value.value) && validMonth(monthRef.value.value)) {
    invalidInputClassName.month = '';
  } else {
    invalidInputClassName.month = 'invalid-input';
  }
  if (isNumberOnly(dayRef.value.value) && validDay(dayRef.value.value)) {
    invalidInputClassName.day = '';
  } else {
    invalidInputClassName.day = 'invalid-input';
  }
  // 「年」が4桁入力されたら「月」に移動し入力値を選択した状態にする
  if (
    e.target === yearRef.value &&
    yearRef.value?.value.length === 4 &&
    !e.isComposing
  ) {
    monthRef.value.select();
  }
  // 「月」が2桁入力されたら「日」に移動し入力値を選択した状態にする
  if (
    e.target === monthRef.value &&
    monthRef.value?.value.length === 2 &&
    !e.isComposing
  ) {
    dayRef.value.select();
  }
  // 入力値を'Y-m-d'の形式に変換して親コンポーネントに渡す
  const updatedDate = `${yearRef.value.value}-${monthRef.value.value}-${dayRef.value.value}`;
  emit('update:modelValue', updatedDate);
};
// Enterキー押下で次の入力欄へフォーカスをを移動する
const onKeyDownEnter = (e) => {
  // 文字変換中なら何もしない
  if (e.isComposing || e.keyCode === 229) return;
  // 文字変換中でなければEnter押下で次の入力欄へ移動し入力値を選択した状態にする
  if (e.target === yearRef.value) monthRef.value.select();
  if (e.target === monthRef.value) dayRef.value.select();
  if (e.target === dayRef.value) dayRef.value.blur();
};
</script>

<template>
  <div class="base-input">
    <!-- 年 -->
    <input
      :aria-describedby="`${id}HelpBlockYear`"
      :class="'form-control border-dark input-year ' + className.year"
      :disabled="disabled"
      :id="id"
      maxlength="4"
      :placeholder="placeholderYear"
      type="text"
      :value="date.year"
      ref="yearRef"
      @input="onInput"
      @keydown.enter="onKeyDownEnter"
    />
    <span>年</span>
    <!-- 月 -->
    <input
      :aria-describedby="`${id}HelpBlockMonth`"
      :class="'form-control border-dark input-month-day ' + className.month"
      :disabled="disabled"
      :id="id"
      maxlength="2"
      :placeholder="placeholderMonth"
      type="text"
      :value="date.month"
      ref="monthRef"
      @input="onInput"
      @keydown.enter="onKeyDownEnter"
    />
    <span>月</span>
    <!-- 日 -->
    <input
      :aria-describedby="`${id}HelpBlockDay`"
      :class="'form-control border-dark input-month-day ' + className.day"
      :disabled="disabled"
      :id="id"
      maxlength="2"
      :placeholder="placeholderDay"
      type="text"
      :value="date.day"
      ref="dayRef"
      @input="onInput"
      @keydown.enter="onKeyDownEnter"
    />
    <span>日</span>
    <div class="invalid-feedback">
      <div v-for="(error, index) in invalidFeedback" :key="index">
        {{ error }}
      </div>
    </div>
    <small class="help-text">{{ helperText }}</small>
  </div>
</template>

<style>
.invalid-input {
  border-color: red;
}
.input-year {
  width: 4rem;
  margin-right: 8px;
  text-align: right;
}
.input-month-day {
  width: 3rem;
  margin-right: 8px;
  margin-left: 16px;
  text-align: right;
}
.base-input {
  margin-bottom: 1rem;
}
.help-text {
  color: rgb(141, 141, 141);
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
