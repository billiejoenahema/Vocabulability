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
const invalidFeedback = computed(
  () => store.getters['question/invalidFeedback']
);
const hasErrors = computed(() => store.getters['question/hasErrors']);
const csv = ref(null);

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
        <input
          type="text"
          v-model="newQuestion.word"
          :class="invalidFeedback('word') && 'invalid'"
        />
        <div class="invalid-feedback" v-if="invalidFeedback('word')">
          <div v-for="(error, index) in invalidFeedback('word')" :key="index">
            {{ error }}
          </div>
        </div>
      </div>
      <div class="column">
        <label>正解</label>
        <input
          type="text"
          v-model="newQuestion.correct_answer"
          :class="invalidFeedback('correct_answer') && 'invalid'"
        />
        <div class="invalid-feedback" v-if="invalidFeedback('correct_answer')">
          <div
            v-for="(error, index) in invalidFeedback('correct_answer')"
            :key="index"
          >
            {{ error }}
          </div>
        </div>
      </div>
      <div class="column">
        <label>例文</label>
        <input
          type="text"
          v-model="newQuestion.example"
          :class="invalidFeedback('example') && 'invalid'"
        />
        <div class="invalid-feedback" v-if="invalidFeedback('example')">
          <div
            v-for="(error, index) in invalidFeedback('example')"
            :key="index"
          >
            {{ error }}
          </div>
        </div>
      </div>
      <div class="button-area">
        <button @click.prevent="addWord()">追加</button>
      </div>
      <div class="csv-import">
        <div class="csv-import-input-area">
          <label>CSVインポート</label>
          <input
            type="file"
            accept=".csv"
            ref="csv"
            :class="invalidFeedback('file') && 'invalid'"
          />
          <div class="invalid-feedback" v-if="invalidFeedback('file')">
            <div v-for="(error, index) in invalidFeedback('file')" :key="index">
              {{ error }}
            </div>
          </div>
        </div>
        <button @click="importCSV()">CSVファイルをインポート</button>
      </div>
    </form>
  </div>
</template>
