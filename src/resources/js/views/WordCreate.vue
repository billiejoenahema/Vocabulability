<script setup>
import { computed, onUnmounted, reactive, ref } from 'vue';
import { useStore } from 'vuex';
import Navigation from '../components/Navigation.vue';
import Toast from '../components/Toast.vue';

const store = useStore();

onUnmounted(() => {
  store.commit('question/resetErrors');
});
const newQuestion = reactive({
  word: '',
  correct_answer: '',
  example: '',
});
const errors = computed(() => store.getters['question/errors']);
const hasErrors = computed(() => store.getters['question/hasErrors']);
const csv = ref(null);

const invalidFeedback = (message) => {
  return message ? message[0] : '';
};
const addWord = async () => {
  await store.dispatch('question/post', newQuestion);
  if (hasErrors.value) {
    return;
  }
  newQuestion.word = '';
  newQuestion.correct_answer = '';
  newQuestion.example = '';
  store.commit('question/resetErrors');
};
const importCSV = async () => {
  const formData = new FormData();

  formData.append('file', csv.value.files[0]);
  await store.dispatch('question/importCSV', formData);
};
</script>

<template>
  <Toast />
  <Navigation />
  <div class="word-create">
    <form @submit.prevent>
      <div class="row header">
        <div class="title">新規登録</div>
      </div>
      <div class="column">
        <label>単語</label>
        <input type="text" v-model="newQuestion.word" />
        <div class="invalid-feedback">{{ invalidFeedback(errors.word) }}</div>
      </div>
      <div class="column">
        <label>正解</label>
        <input type="text" v-model="newQuestion.correct_answer" />
        <div class="invalid-feedback">
          {{ invalidFeedback(errors.correct_answer) }}
        </div>
      </div>
      <div class="column">
        <label>例文</label>
        <input type="text" v-model="newQuestion.example" />
        <div class="invalid-feedback">
          {{ invalidFeedback(errors.example) }}
        </div>
      </div>
      <div class="button-area">
        <button @click.prevent="addWord()">追加</button>
      </div>
      <div class="csv-import">
        <label>CSVインポート</label>
        <input type="file" accept=".csv" ref="csv" />
        <button @click="importCSV()">CSVファイルをインポート</button>
      </div>
    </form>
  </div>
</template>
