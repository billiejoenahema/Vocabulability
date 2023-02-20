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
const setIsLoading = (bool) => store.commit('loading/setIsLoading', bool);
const csv = ref(null);

const post = async () => {
  setIsLoading(true);
  await store.dispatch('question/post', newQuestion);
  if (!hasErrors.value) {
    newQuestion = { ...initialValue };
    store.commit('question/setErrors', {});
  }
  setIsLoading(false);
};
const importCSV = async () => {
  setIsLoading(true);
  const formData = new FormData();

  formData.append('file', csv.value.files[0]);
  await store.dispatch('question/importCSV', formData);
  setIsLoading(false);
};
onUnmounted(() => {
  store.commit('question/setErrors', {});
});
</script>

<template>
  <div class="container">
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
        <InvalidFeedback :invalid-feedback="invalidFeedback('word')" />
      </div>
      <div class="column">
        <label>正解</label>
        <input
          type="text"
          v-model="newQuestion.correct_answer"
          :class="isInvalid('correct_answer')"
          maxlength="255"
        />
        <InvalidFeedback
          :invalid-feedback="invalidFeedback('correct_answer')"
        />
      </div>
      <div class="column"></div>
      <div class="button-area">
        <button @click.prevent="post()" class="register">追加</button>
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
          <InvalidFeedback :invalid-feedback="invalidFeedback('file')" />
        </div>
        <button @click="importCSV()">CSVファイルをインポート</button>
      </div>
    </div>
  </div>
</template>
