<script setup>
import { computed, onMounted, ref } from 'vue';
import { useStore } from 'vuex';
import InvalidFeedback from '../components/InvalidFeedback.vue';
import LoadingOverlay from '../components/LoadingOverlay.vue';
import Navigation from '../components/Navigation.vue';
import Toast from '../components/Toast.vue';
import { useDebounce } from '../functions/useDebounce';

const store = useStore();

onMounted(async () => {
  setIsLoading(true);
  await store.dispatch('question/get');
  setIsLoading(false);
});

store.dispatch('question/get');
const questions = computed(() => store.getters['question/data']);
const noMatch = '検索に一致する単語はありませんでした。';
const alphabets = [...'abcdefghijklmnopqrstuvwxyz'];
const invalidFeedback = computed(
  () => store.getters['question/invalidFeedback']
);
const hasErrors = computed(() => store.getters['question/hasErrors']);
const editable = ref([]);
const keyword = ref('');
const currentAlphabet = ref('');
const hasQuestions = computed(() => questions.value.length > 0);
const isLoading = computed(() => store.getters['loading/isLoading']);
const setIsLoading = (bool) => store.commit('loading/setIsLoading', bool);

const debounceSearch = useDebounce(() => {
  currentAlphabet.value = '';
  store.dispatch('question/get', { keyword: keyword.value });
});
const filter = (alphabet) => {
  keyword.value = '';
  editable.value = [];
  currentAlphabet.value = alphabet;
  store.dispatch('question/get', { filter: alphabet });
};
const toEditable = (index) => {
  editable.value = [];
  editable.value[index] = true;
};
const updateQuestion = async (question, index) => {
  setIsLoading(true);
  await store.dispatch('question/update', question);
  setIsLoading(false);
  if (hasErrors.value) {
    return;
  }
  editable.value[index] = false;
  store.dispatch('question/get');
};
const deleteQuestion = async (id) => {
  if (confirm('この問題を削除しますか？')) {
    setIsLoading(true);
    await store.dispatch('question/delete', id);
    setIsLoading(false);
    if (hasErrors.value) {
      return;
    }
    store.dispatch('question/get');
  }
};
const cancel = (index) => {
  store.dispatch('question/get');
  editable.value[index] = false;
};
</script>

<template>
  <LoadingOverlay :isLoading="isLoading" />
  <Toast />
  <Navigation />
  <div class="word-list">
    <div class="row header">
      <div class="title">登録済み単語リスト</div>
      <div class="search-input-wrapper">
        <input
          v-model="keyword"
          @input="debounceSearch()"
          placeholder="キーワード検索"
        />
      </div>
    </div>
    <div class="wrap">
      <div class="row" v-for="(alphabet, index) in alphabets" :key="index">
        <span v-if="index > 0">/</span>
        <div
          class="index-item"
          :class="alphabet === currentAlphabet && 'current-alphabet'"
          @click="filter(alphabet)"
        >
          {{ alphabet }}
        </div>
      </div>
    </div>
    <div class="row list-header">
      <div class="list-column-title">単語</div>
      <div class="list-column-title">正解</div>
    </div>
    <div v-if="!isLoading && !questions.length">{{ noMatch }}</div>
    <div v-else class="list-body">
      <div
        v-for="(question, index) in questions"
        :key="question.id"
        class="row list-row"
      >
        <input
          v-if="editable[index]"
          v-model="question.word"
          :class="invalidFeedback('word') && 'invalid'"
        />
        <div v-else @click="toEditable(index)" class="list-item">
          {{ question.word }}
        </div>
        <input
          v-if="editable[index]"
          v-model="question.correct_answer"
          :class="invalidFeedback('correct_answer') && 'invalid'"
        />
        <div v-else @click="toEditable(index)" class="list-item">
          {{ question.correct_answer }}
        </div>
        <button v-if="editable[index]" @click="updateQuestion(question, index)">
          更新
        </button>
        <div v-else @click="toEditable(index)"></div>
        <button v-if="editable[index]" class="cancel" @click="cancel(index)">
          キャンセル
        </button>
        <div v-else @click="toEditable(index)"></div>
        <button
          v-if="editable[index]"
          class="delete"
          @click="deleteQuestion(question.id)"
        >
          削除
        </button>
        <div v-else @click="toEditable(index)"></div>
        <InvalidFeedback
          v-if="editable[index] && invalidFeedback('word')"
          :errors="invalidFeedback('word')"
        />
        <InvalidFeedback
          v-if="editable[index] && invalidFeedback('correct_answer')"
          :errors="invalidFeedback('correct_answer')"
        />
      </div>
    </div>
  </div>
</template>
