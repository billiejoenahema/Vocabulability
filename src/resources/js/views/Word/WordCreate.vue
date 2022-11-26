<script setup>
import { computed, onUnmounted, reactive, ref } from 'vue';
import { useStore } from 'vuex';
import InvalidFeedback from '../../components/InvalidFeedback';

const store = useStore();

onUnmounted(() => {
  store.commit('question/setErrors', {});
});
const initialValue = {
  word: '',
  correct_answer: '',
  example: '',
};
const newQuestion = reactive({ ...initialValue });
const invalidFeedback = computed(
  () => store.getters['question/invalidFeedback']
);
const hasErrors = computed(() => store.getters['question/hasErrors']);
const isInvalid = computed(() => store.getters['question/isInvalid']);
const csv = ref(null);

const addWord = async () => {
  await store.dispatch('question/post', newQuestion);
  if (hasErrors.value) return;
  newQuestion = { ...initialValue };
  store.commit('question/setErrors', {});
};
const importCSV = async () => {
  const formData = new FormData();

  formData.append('file', csv.value.files[0]);
  await store.dispatch('question/importCSV', formData);
};
onUnmounted(() => {
  store.commit('word/setErrors', {});
});
</script>

<template>
  <div class="word-create">
    <div>
      <div class="row header">
        <div class="title">新規登録</div>
      </div>
      <div class="column">
        <label>単語</label>
        <input
          type="text"
          v-model="newQuestion.word"
          :class="isInvalid('word')"
          maxlength="255"
        />
        <InvalidFeedback :errors="invalidFeedback('word')" />
      </div>
      <div class="column">
        <label>正解</label>
        <input
          type="text"
          v-model="newQuestion.correct_answer"
          :class="isInvalid('correct_answer')"
          maxlength="255"
        />
        <InvalidFeedback :errors="invalidFeedback('correct_answer')" />
      </div>
      <div class="column"></div>
      <div class="button-area">
        <button @click.prevent="addWord()">追加</button>
      </div>
      <hr />
      <div class="csv-import">
        <div class="csv-import-input-area">
          <label>CSVインポート</label>
          <input
            type="file"
            accept=".csv"
            ref="csv"
            :class="isInvalid('file')"
          />
          <InvalidFeedback :errors="invalidFeedback('file')" />
        </div>
        <button @click="importCSV()">CSVファイルをインポート</button>
      </div>
    </div>
  </div>
</template>
