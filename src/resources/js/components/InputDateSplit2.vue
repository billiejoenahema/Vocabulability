<!-- リアルタイムバリデーションなし -->
<script setup>
import { computed, ref } from 'vue';

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
const onKeyDownEnter = (e) => {
  // 文字変換中なら何もしない
  if (e.isComposing) return;
  // 文字変換中でなければEnter押下で次の入力欄へ移動し入力値を選択した状態にする
  if (e.target === yearRef.value) monthRef.value.select();
  if (e.target === monthRef.value) dayRef.value.select();
  if (e.target === dayRef.value) dayRef.value.blur();
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
    @keydown.enter="onKeyDownEnter"
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
    @keydown.enter="onKeyDownEnter"
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
    @keydown.enter="onKeyDownEnter"
  />
  <span>日</span>
  <div class="invalid-feedback">
    {{ invalidFeedback }}
  </div>
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
