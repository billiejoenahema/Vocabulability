<!-- リアルタイムバリデーションあり -->
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
    default: '',
    required: false,
    type: String,
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
// propsを「年」「月」「日」に分割する
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
const invalidInputFeedback = ref('');
const setInvalidInputFeedback = () => {
  invalidInputFeedback.value = '無効な日付が入力されています。';
};
const onInput = (e) => {
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
  // 「日」が2桁入力されたらフォーカスを外す
  if (
    e.target === dayRef.value &&
    dayRef.value?.value.length === 2 &&
    !e.isComposing
  ) {
    dayRef.value.blur();
  }
  const updatedDate = `${yearRef.value.value}-${monthRef.value.value}-${dayRef.value.value}`;
  emit('update:modelValue', updatedDate);
};
// Enterキー押下で次の入力欄へフォーカスをを移動する
const onKeydownEnter = (e) => {
  // 文字変換中なら何もしない
  if (e.isComposing) return;
  // 文字変換中でなければEnter押下で次の入力欄へ移動し入力値を選択した状態にする
  if (e.target === yearRef.value) monthRef.value.select();
  if (e.target === monthRef.value) dayRef.value.select();
  if (e.target === dayRef.value) dayRef.value.blur();
};
// 「年」「月」「日」すべて入力済みかどうか
const isDateFilled = () => {
  return (
    yearRef.value.value !== '' &&
    monthRef.value.value !== '' &&
    dayRef.value.value !== ''
  );
};
// 無効な年かどうか
const isInvalidYear = () => {
  const year = yearRef.value.value;
  return (
    !year.match(/^[0-9]*$/) ||
    Number(year) < 1 ||
    Number(year) > 10000 ||
    year.startsWith('0')
  );
};
// 無効な月かどうか
const isInvalidMonth = () => {
  const month = monthRef.value.value;
  return !month.match(/^[0-9]*$/) || Number(month) < 1 || Number(month) > 12;
};
// 無効な日かどうか
const isInvalidDay = () => {
  const day = dayRef.value.value;
  return !day.match(/^[0-9]*$/) || Number(day) < 1 || Number(day) > 31;
};
// フォームからフォーカスが外れたら正しい入力値がどうかを判定する
const onBlur = (e) => {
  // エラー表示の初期化
  invalidInputFeedback.value = '';
  Object.keys(invalidInputClassName).forEach((key) => {
    invalidInputClassName[key] = '';
  });

  // 年月日すべてが入力済みでなければなにもしない
  if (!isDateFilled()) return;

  if (isInvalidYear()) {
    invalidInputClassName.year = 'is-invalid';
    setInvalidInputFeedback();
  }
  if (isInvalidMonth()) {
    invalidInputClassName.month = 'is-invalid';
    setInvalidInputFeedback();
  }
  if (isInvalidDay()) {
    invalidInputClassName.day = 'is-invalid';
    setInvalidInputFeedback();
  }
};
</script>

<template>
  <!-- 年 -->
  <input
    :aria-describedby="`${id}HelpBlockYear`"
    class="form-control border-dark input-year"
    :class="className.year"
    :disabled="disabled"
    :id="id"
    maxlength="4"
    :placeholder="placeholderYear"
    type="text"
    :value="date.year"
    ref="yearRef"
    @input="onInput"
    @keydown.enter="onKeydownEnter"
    @blur="onBlur"
  />
  <span>年</span>
  <!-- 月 -->
  <input
    :aria-describedby="`${id}HelpBlockMonth`"
    class="form-control border-dark input-month-day"
    :class="className.month"
    :disabled="disabled"
    :id="id"
    maxlength="2"
    :placeholder="placeholderMonth"
    type="text"
    :value="date.month"
    ref="monthRef"
    @input="onInput"
    @keydown.enter="onKeydownEnter"
    @blur="onBlur"
  />
  <span>月</span>
  <!-- 日 -->
  <input
    :aria-describedby="`${id}HelpBlockDay`"
    class="form-control border-dark input-month-day"
    :class="className.day"
    :disabled="disabled"
    :id="id"
    maxlength="2"
    :placeholder="placeholderDay"
    type="text"
    :value="date.day"
    ref="dayRef"
    @input="onInput"
    @keydown.enter="onKeydownEnter"
    @blur="onBlur"
  />
  <span>日</span>
  <div class="invalid-feedback">
    {{ invalidFeedback }}
  </div>
  <div class="invalid-feedback">{{ invalidInputFeedback }}</div>
  <small class="form-text">{{ helperText }}</small>
</template>

<style scoped>
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
</style>
